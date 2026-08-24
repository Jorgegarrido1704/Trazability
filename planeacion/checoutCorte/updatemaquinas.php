<?php

require 'app/conection.php';

const TOPE_BLOQUE = 540.0;

/**
 * Asigna IDs respetando el tope de minutos en las máquinas candidatas.
 */
function asignarConTope(array $ids, float $tiempoTotal, array $maquinasCandidatas, array $maquinasActivas, float $tope, array &$idsPorMaquina, array &$maquinas): array {
    if (empty($ids)) {
        return ['ids' => [], 'time' => 0.0];
    }

    $activas = array_values(array_intersect($maquinasCandidatas, $maquinasActivas));
    if (empty($activas)) {
        return ['ids' => $ids, 'time' => $tiempoTotal];
    }

    $totalIds = count($ids);
    $avgTime = $totalIds > 0 ? $tiempoTotal / $totalIds : 0.0;

    $capacidades = [];
    $capacidadTotal = 0.0;
    foreach ($activas as $maq) {
        $libre = max(0.0, $tope - $maquinas[$maq]);
        $capacidades[$maq] = $libre;
        $capacidadTotal += $libre;
    }

    if ($capacidadTotal <= 0.0) {
        return ['ids' => $ids, 'time' => $tiempoTotal];
    }

    $restanteIds = $ids;

    if ($avgTime > 0 && $tiempoTotal > $capacidadTotal) {
        foreach ($activas as $maq) {
            if ($capacidades[$maq] <= 0 || empty($restanteIds)) continue;
            $cantidad = (int) floor($capacidades[$maq] / $avgTime);
            $cantidad = max(0, min($cantidad, count($restanteIds)));
            if ($cantidad > 0) {
                $chunk = array_splice($restanteIds, 0, $cantidad);
                $tiempoChunk = count($chunk) * $avgTime;
                $idsPorMaquina[$maq] = array_merge($idsPorMaquina[$maq], $chunk);
                $maquinas[$maq] += $tiempoChunk;
            }
        }
        $tiempoRestante = count($restanteIds) * $avgTime;
        return ['ids' => $restanteIds, 'time' => $tiempoRestante];
    }

    $asignaciones = [];
    $sumaAsignada = 0;
    foreach ($activas as $maq) {
        if ($capacidades[$maq] <= 0) {
            $asignaciones[$maq] = 0;
            continue;
        }
        $cantidad = (int) floor($totalIds * ($capacidades[$maq] / $capacidadTotal));
        $asignaciones[$maq] = $cantidad;
        $sumaAsignada += $cantidad;
    }

    $faltantes = $totalIds - $sumaAsignada;
    if ($faltantes > 0) {
        $capacidadesOrdenadas = $capacidades;
        arsort($capacidadesOrdenadas);
        $maqConMasEspacio = array_key_first($capacidadesOrdenadas);
        $asignaciones[$maqConMasEspacio] += $faltantes;
    }

    foreach ($asignaciones as $maq => $cantidad) {
        if ($cantidad <= 0) continue;
        $chunk = array_splice($restanteIds, 0, $cantidad);
        $tiempoChunk = count($chunk) * $avgTime;
        $idsPorMaquina[$maq] = array_merge($idsPorMaquina[$maq], $chunk);
        $maquinas[$maq] += $tiempoChunk;
    }

    return ['ids' => [], 'time' => 0.0];
}

/**
 * Reparte IDs entre máquinas sin aplicar tope (para balancear desbordes finales).
 */
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

    if (empty($maquinasActivasInput)) {
        $maquinasActivasInput = ["MCUT-1", "MCUT-2", "MCUT-3", "MCUT-4", "MCUT-5", "MCUT-6", "MCUT-7"];
    }

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

    $poolSello = ['ids' => [], 'time' => 0.0];

    $poolGaugeNormal = [
        'G10_12' => [
            'BLANCA' => ['ids' => [], 'time' => 0.0],
            'NEGRA'  => ['ids' => [], 'time' => 0.0],
        ],
        'G14_16' => [
            'BLANCA' => ['ids' => [], 'time' => 0.0],
            'NEGRA'  => ['ids' => [], 'time' => 0.0],
        ],
        'G18_24' => [
            'BLANCA' => ['ids' => [], 'time' => 0.0],
            'NEGRA'  => ['ids' => [], 'time' => 0.0],
        ],
    ];

    $poolMCUT7 = ['ids' => [], 'time' => 0.0];

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

        // 1) MCUT-7 EXCLUSIVO: calibres fuera de 10-24 (1,2,4,6,8) o cons que empieza con C/c
        if ($consEmpiezaC || !$calibreValido) {
            $poolMCUT7['ids'][] = $idCorte;
            $poolMCUT7['time'] += $tiempoTotal;
            continue;
        }

        // 2) SELLO: Todo va a MCUT-3
        if ($esSello) {
            $poolSello['ids'][] = $idCorte;
            $poolSello['time'] += $tiempoTotal;
            continue;
        }

        // 3) CLASIFICACIÓN NORMAL (10 a 24)
        if ($calibre === 10 || $calibre === 12) {
            $bucket = 'G10_12';
        } elseif ($calibre === 14 || $calibre === 16) {
            $bucket = 'G14_16';
        } else { // 18, 20, 22, 24
            $bucket = 'G18_24';
        }

        $poolGaugeNormal[$bucket][$tinta]['ids'][] = $idCorte;
        $poolGaugeNormal[$bucket][$tinta]['time'] += $tiempoTotal;
    }
    mysqli_stmt_close($stmtListas);

    // =========================================================
    // 1. ASIGNACIÓN EXCLUSIVA DE MCUT-7
    // =========================================================
    if (!empty($poolMCUT7['ids']) && in_array('MCUT-7', $maquinasActivasInput, true)) {
        $idsPorMaquina['MCUT-7'] = array_merge($idsPorMaquina['MCUT-7'], $poolMCUT7['ids']);
        $maquinas['MCUT-7'] += $poolMCUT7['time'];
    }

    // =========================================================
    // 2. ASIGNACIÓN DE SELLO (TODO A MCUT-3)
    // =========================================================
    if (!empty($poolSello['ids'])) {
        if (in_array('MCUT-3', $maquinasActivasInput, true)) {
            $idsPorMaquina['MCUT-3'] = array_merge($idsPorMaquina['MCUT-3'], $poolSello['ids']);
            $maquinas['MCUT-3'] += $poolSello['time'];
        } else {
            // Si MCUT-3 está apagada, se envía a MCUT-1 (sin tocar MCUT-7)
            repartirEntreMaquinas($poolSello['ids'], $poolSello['time'], array_intersect(['MCUT-1'], $maquinasActivasInput), $idsPorMaquina, $maquinas);
        }
    }

    // =========================================================
    // 3. ASIGNACIÓN TRABAJO NORMAL (10 a 24) ENTRE MCUT-1 A MCUT-6
    // =========================================================
    $especialistas = [
        'G10_12' => ['BLANCA' => null,     'NEGRA' => null],
        'G14_16' => ['BLANCA' => 'MCUT-2', 'NEGRA' => 'MCUT-5'],
        'G18_24' => ['BLANCA' => 'MCUT-4', 'NEGRA' => 'MCUT-6'],
    ];

    foreach (['G10_12', 'G14_16', 'G18_24'] as $bucket) {
        foreach (['BLANCA', 'NEGRA'] as $tinta) {
            $data = $poolGaugeNormal[$bucket][$tinta];
            if (empty($data['ids'])) continue;

            // Paso A: Reparto en Generalistas MCUT-1 y MCUT-3 (hasta 540 min)
            $leftover = asignarConTope(
                $data['ids'], $data['time'],
                ['MCUT-1', 'MCUT-3'], $maquinasActivasInput, TOPE_BLOQUE,
                $idsPorMaquina, $maquinas
            );

            if (empty($leftover['ids'])) continue;

            // Paso B: Especialista correspondiente (MCUT-2, 4, 5 o 6 hasta 540 min)
            $esp = $especialistas[$bucket][$tinta];
            if ($esp !== null) {
                $leftover = asignarConTope(
                    $leftover['ids'], $leftover['time'],
                    [$esp], $maquinasActivasInput, TOPE_BLOQUE,
                    $idsPorMaquina, $maquinas
                );
            }

            if (empty($leftover['ids'])) continue;

            // Paso C: Segunda vuelta a Generalistas MCUT-1 y MCUT-3 (hasta 1080 min)
            $leftover = asignarConTope(
                $leftover['ids'], $leftover['time'],
                ['MCUT-1', 'MCUT-3'], $maquinasActivasInput, TOPE_BLOQUE * 2,
                $idsPorMaquina, $maquinas
            );

            if (empty($leftover['ids'])) continue;

            // Paso D: Desborde final -> Solo entre las máquinas compatibles activas (MCUT-1 a MCUT-6)
            $compatibles = ['MCUT-1', 'MCUT-3'];
            if ($esp !== null) {
                $compatibles[] = $esp;
            }
            $destinoFinal = array_values(array_intersect($compatibles, $maquinasActivasInput));

            if (empty($destinoFinal)) {
                // Si no hay ninguna compatible activa, usar cualquier máquina activa de corte (1 a 6)
                $destinoFinal = array_values(array_intersect(['MCUT-1', 'MCUT-2', 'MCUT-3', 'MCUT-4', 'MCUT-5', 'MCUT-6'], $maquinasActivasInput));
            }

            // Reparto final sin tope (NUNCA pasa a MCUT-7)
            repartirEntreMaquinas($leftover['ids'], $leftover['time'], $destinoFinal, $idsPorMaquina, $maquinas);
        }
    }

    // =========================================================
    // 4. GUARDAR CAMBIOS EN BD
    // =========================================================
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