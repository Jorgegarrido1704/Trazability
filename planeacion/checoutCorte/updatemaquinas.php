<?php

require 'app/conection.php';


function repartirEntreMaquinas(array $ids, float $tiempoTotal, array $maquinasDestino, array &$idsPorMaquina, array &$maquinas): void {
    $total = count($ids);
    if ($total === 0 || empty($maquinasDestino)) {
        return;
    }

    $numMaquinas = count($maquinasDestino);
    $elementosPorMaquina = (int) ceil($total / $numMaquinas);
    $chunks = array_chunk($ids, max($elementosPorMaquina, 1));

    foreach ($maquinasDestino as $index => $maq) {
        if (isset($chunks[$index])) {
            $idsPorMaquina[$maq] = array_merge($idsPorMaquina[$maq], $chunks[$index]);
            $proporcion = count($chunks[$index]) / $total;
            $maquinas[$maq] += $tiempoTotal * $proporcion;
        }
    }
}

/** Asigna el pool completo a una sola máquina. */
function asignarTodoAMaquina(array $ids, float $tiempoTotal, string $maquina, array &$idsPorMaquina, array &$maquinas): void {
    if (empty($ids)) {
        return;
    }
    $idsPorMaquina[$maquina] = array_merge($idsPorMaquina[$maquina], $ids);
    $maquinas[$maquina] += $tiempoTotal;
}

/**
 * Recorre los "tiers" de prioridad en orden y devuelve el primero que
 * tenga al menos una máquina activa (filtrado a solo las activas de
 * ese tier). Si ningún tier tiene nada activo, devuelve [].
 */
function resolverDestino(array $tiers, array $maquinasActivas): array {
    foreach ($tiers as $tier) {
        $activasEnTier = array_values(array_intersect($tier, $maquinasActivas));
        if (!empty($activasEnTier)) {
            return $activasEnTier;
        }
    }
    return [];
}

/** Cadena de prioridad para calibres 10-12, 14-16 y 18-22 (no sello). */
function tiersRangoGeneral(string $tinta, ?string $especialista): array {
    $tiers = [
        ['MCUT-1', 'MCUT-3'], // generalistas: prioridad total
    ];
    if ($especialista !== null) {
        $tiers[] = [$especialista];
    }
    $tiers[] = ['MCUT-7']; // último recurso antes del fallback genérico
    return $tiers;
}

/** Cadena de prioridad para calibre 24 (no sello): fuera del rango de MCUT-1. */
function tiersG24(string $especialista): array {
    return [
        ['MCUT-3'],
        [$especialista],
        ['MCUT-7'],
    ];
}

/** Cadena de prioridad para el pool de MCUT-7 (calibres fuera de 10-24 / cons "C"). */
function tiersOther(string $tinta): array {
    $tiers = [['MCUT-7']];
    if ($tinta === 'BLANCA') {
        $tiers[] = ['MCUT-1', 'MCUT-3', 'MCUT-2', 'MCUT-4'];
    } else {
        $tiers[] = ['MCUT-1', 'MCUT-3', 'MCUT-5', 'MCUT-6'];
    }
    return $tiers;
}

function actualizarMaquinaEnBD($con, array $ids, string $nombreMaquina): void {
    if (empty($ids)) return;
    $idsFormateados = array_map(function($id) use ($con) {
        return "'" . mysqli_real_escape_string($con, $id) . "'";
    }, $ids);
    $listaIds = implode(',', $idsFormateados);
    $sql = "UPDATE corte SET maq_asignada = '$nombreMaquina' WHERE id IN ($listaIds)";
    mysqli_query($con, $sql);
}

try {
    $maquinasQuery = isset($_GET['maquinas']) ? $_GET['maquinas'] : [];
    $maquinasActivasInput = [];
    foreach ($maquinasQuery as $value) {
        $maquinasActivasInput[] = strtoupper(trim($value));
    }

    // Si viene vacío por URL, por seguridad se asumen todas activas
    if (empty($maquinasActivasInput)) {
        $maquinasActivasInput = ["MCUT-1", "MCUT-2", "MCUT-3", "MCUT-4", "MCUT-5", "MCUT-6", "MCUT-7"];
    }

    $mcut3Activa = in_array("MCUT-3", $maquinasActivasInput, true);

    $maquinas = [
        "MCUT-1" => 0, "MCUT-2" => 0, "MCUT-3" => 0,
        "MCUT-4" => 0, "MCUT-5" => 0, "MCUT-6" => 0,
        "MCUT-7" => 0, "TOTAL_MAQUINAS" => 0
    ];

    $idsPorMaquina = [
        "MCUT-1" => [], "MCUT-2" => [], "MCUT-3" => [],
        "MCUT-4" => [], "MCUT-5" => [], "MCUT-6" => [],
        "MCUT-7" => []
    ];

    // Pool exclusivo de sello (solo se llena si MCUT-3 está activa;
    // si no, cada renglón con sello cae directo al pool de su rango normal)
    $poolSello = ['ids' => [], 'time' => 0.0];

    // Pools por rango de calibre y tinta (para MCUT-1/3 + especialistas)
    $poolGauge = [
        'G10_12' => ['BLANCA' => ['ids' => [], 'time' => 0.0], 'NEGRA' => ['ids' => [], 'time' => 0.0]],
        'G14_16' => ['BLANCA' => ['ids' => [], 'time' => 0.0], 'NEGRA' => ['ids' => [], 'time' => 0.0]],
        'G18_22' => ['BLANCA' => ['ids' => [], 'time' => 0.0], 'NEGRA' => ['ids' => [], 'time' => 0.0]],
        'G24'    => ['BLANCA' => ['ids' => [], 'time' => 0.0], 'NEGRA' => ['ids' => [], 'time' => 0.0]],
    ];

    // Pool "OTHER" -> MCUT-7 (calibres 1,2,4,6,8, fuera de 10-24, o cons que empieza con C)
    $poolOther = [
        'BLANCA' => ['ids' => [], 'time' => 0.0],
        'NEGRA'  => ['ids' => [], 'time' => 0.0],
    ];

    $query = "SELECT c.id, c.np, c.color, c.aws, c.cons, c.tipo, c.tamano, c.term1, c.term2, c.tintaColor, c.qty, c.time_ruteo 
          FROM corte c
          WHERE c.cutStatus != 'Cortado' 
            AND c.tamano > 0 
            AND NOT EXISTS (SELECT 1 FROM carga_congelada cc WHERE cc.id_corte = c.id)
          ORDER BY c.aws, c.color, c.term1, c.term2 DESC";

    $stmtListas = mysqli_prepare($con, $query);

    if (!$stmtListas) {
        throw new Exception("Error al preparar la consulta: " . mysqli_error($con));
    }

    mysqli_stmt_execute($stmtListas);
    $resListas = mysqli_stmt_get_result($stmtListas);

    while ($rowlistas = mysqli_fetch_assoc($resListas)) {
        $idCorte  = $rowlistas['id'];
        $calibre  = (int)$rowlistas['aws'];
        $term1    = $rowlistas['term1'];
        $term2    = $rowlistas['term2'];
        $cons     = trim((string)$rowlistas['cons']);
        $tinta    = trim(strtoupper($rowlistas['tintaColor']));

        if ($tinta !== 'BLANCA' && $tinta !== 'NEGRA') {
            $tinta = 'NEGRA';
        }

        $tiempoTotal = round($rowlistas['time_ruteo'] / 60, 2) + 5;
        $maquinas['TOTAL_MAQUINAS'] += $tiempoTotal;

        $esSello       = (stripos($term1, "Sello") !== false || stripos($term2, "Sello") !== false);
        $consEmpiezaC  = ($cons !== '' && strtoupper($cons[0]) === 'C');
        $calibreValido = in_array($calibre, [10, 12, 14, 16, 18, 20, 22, 24], true);

        // 1) MCUT-7: calibres fuera de la lista válida (incluye 1,2,4,6,8) o cons que empieza con C
        if ($consEmpiezaC || !$calibreValido) {
            $poolOther[$tinta]['ids'][] = $idCorte;
            $poolOther[$tinta]['time'] += $tiempoTotal;
            continue;
        }

        // 2) Sello exclusivo de MCUT-3 (solo si MCUT-3 está activa; si no, sigue como calibre normal)
        if ($esSello && $mcut3Activa) {
            $poolSello['ids'][] = $idCorte;
            $poolSello['time'] += $tiempoTotal;
            continue;
        }

        // 3) Ruteo normal por calibre + tinta
        if ($calibre === 10 || $calibre === 12) {
            $bucket = 'G10_12';
        } elseif ($calibre === 14 || $calibre === 16) {
            $bucket = 'G14_16';
        } elseif ($calibre === 24) {
            $bucket = 'G24';
        } else { // 18, 20, 22
            $bucket = 'G18_22';
        }

        $poolGauge[$bucket][$tinta]['ids'][] = $idCorte;
        $poolGauge[$bucket][$tinta]['time'] += $tiempoTotal;
    }
    mysqli_stmt_close($stmtListas);

    // --- Asignación: sello exclusivo MCUT-3 ---
    if ($mcut3Activa && !empty($poolSello['ids'])) {
        asignarTodoAMaquina($poolSello['ids'], $poolSello['time'], 'MCUT-3', $idsPorMaquina, $maquinas);
    }

    // --- Asignación: rangos generales (10-12, 14-16, 18-22) ---
    $especialistaPorTintaYRango = [
        'G10_12' => ['BLANCA' => null,      'NEGRA' => null],
        'G14_16' => ['BLANCA' => 'MCUT-2',  'NEGRA' => 'MCUT-5'],
        'G18_22' => ['BLANCA' => 'MCUT-4',  'NEGRA' => 'MCUT-6'],
    ];

    foreach ($especialistaPorTintaYRango as $bucket => $porTinta) {
        foreach ($porTinta as $tinta => $especialista) {
            $pool = $poolGauge[$bucket][$tinta];
            if (empty($pool['ids'])) continue;

            $tiers = tiersRangoGeneral($tinta, $especialista);
            $destino = resolverDestino($tiers, $maquinasActivasInput);

            if (empty($destino)) {
                // Último recurso: ninguna máquina de la cadena de prioridad está activa
                $destino = $maquinasActivasInput;
            }

            repartirEntreMaquinas($pool['ids'], $pool['time'], $destino, $idsPorMaquina, $maquinas);
        }
    }

    // --- Asignación: calibre 24 (fuera del rango de MCUT-1) ---
    $especialista24 = ['BLANCA' => 'MCUT-4', 'NEGRA' => 'MCUT-6'];
    foreach (['BLANCA', 'NEGRA'] as $tinta) {
        $pool = $poolGauge['G24'][$tinta];
        if (empty($pool['ids'])) continue;

        $tiers = tiersG24($especialista24[$tinta]);
        $destino = resolverDestino($tiers, $maquinasActivasInput);

        if (empty($destino)) {
            $destino = $maquinasActivasInput;
        }

        repartirEntreMaquinas($pool['ids'], $pool['time'], $destino, $idsPorMaquina, $maquinas);
    }

    // --- Asignación: pool MCUT-7 (fuera de 10-24 / cons "C") ---
    foreach (['BLANCA', 'NEGRA'] as $tinta) {
        $pool = $poolOther[$tinta];
        if (empty($pool['ids'])) continue;

        $tiers = tiersOther($tinta);
        $destino = resolverDestino($tiers, $maquinasActivasInput);

        if (empty($destino)) {
            $destino = $maquinasActivasInput;
        }

        repartirEntreMaquinas($pool['ids'], $pool['time'], $destino, $idsPorMaquina, $maquinas);
    }

    foreach ($idsPorMaquina as $nombreMaquina => $arregloIds) {
        actualizarMaquinaEnBD($con, $arregloIds, $nombreMaquina);
    }

    header('Content-Type: application/json');
    echo json_encode($maquinas);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        "error" => "Ocurrió un error interno en el servidor.",
        "detalle" => $e->getMessage()
    ]);
}