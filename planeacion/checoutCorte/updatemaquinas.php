<?php

require 'app/conection.php';

/**
 * REGLAS DE NEGOCIO ACTUALIZADAS:
 *
 * - TOPE 540 min: MCUT-1, MCUT-2, MCUT-3, MCUT-4, MCUT-5, MCUT-6.
 *   Si llegan al tope, inician de nuevo a cargar desde MCUT-1 y MCUT-3.
 * - SIN TOPE (Destino final / desborde): MCUT-7.
 *
 * Flujo de asignación:
 * 1. Sello: MCUT-3 (todo el sello va a MCUT-3 sin importar calibre).
 * 2. Normal: Generalistas (MCUT-1 y/o MCUT-3 hasta 540 min).
 * 3. Desborde Generalistas: Especialista del calibre/tinta (hasta 540 min).
 * 4. Segunda vuelta / Desborde Especialista: Vuelve a MCUT-1 y MCUT-3.
 * 5. Destino final / Casos especiales: MCUT-7 (calibres fuera de 10-24 y cons con 'C'/'c') -> Cualquier activa (último recurso).
 */

const TOPE_BLOQUE = 540.0;

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
        'G18_22' => [
            'BLANCA' => ['ids' => [], 'time' => 0.0],
            'NEGRA'  => ['ids' => [], 'time' => 0.0],
        ],
        'G24' => [
            'BLANCA' => ['ids' => [], 'time' => 0.0],
            'NEGRA'  => ['ids' => [], 'time' => 0.0],
        ],
    ];

    $poolMCUT7 = [
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

        // Destino MCUT-7: cons que empiece con C/c o calibres fuera de 10-24 (1,2,4,6,8,etc.)
        if ($consEmpiezaC || !$calibreValido) {
            $poolMCUT7[$tinta]['ids'][] = $idCorte;
            $poolMCUT7[$tinta]['time'] += $tiempoTotal;
            continue;
        }

        // Regla 1: Todo el sello va a MCUT-3
        if ($esSello) {
            $poolSello['ids'][] = $idCorte;
            $poolSello['time'] += $tiempoTotal;
            continue;
        }

        // Clasificación por rangos normales
        if ($calibre === 10 || $calibre === 12) {
            $bucket = 'G10_12';
        } elseif ($calibre === 14 || $calibre === 16) {
            $bucket = 'G14_16';
        } elseif ($calibre === 24) {
            $bucket = 'G24';
        } else {
            $bucket = 'G18_22';
        }

        $poolGaugeNormal[$bucket][$tinta]['ids'][] = $idCorte;
        $poolGaugeNormal[$bucket][$tinta]['time'] += $tiempoTotal;
    }
    mysqli_stmt_close($stmtListas);

    // =========================================================
    // 1. ASIGNACIÓN DE SELLO (DIRECTO A MCUT-3)
    // =========================================================
    if (!empty($poolSello['ids'])) {
        if (in_array('MCUT-3', $maquinasActivasInput, true)) {
            $idsPorMaquina['MCUT-3'] = array_merge($idsPorMaquina['MCUT-3'], $poolSello['ids']);
            $maquinas['MCUT-3'] += $poolSello['time'];
        } else {
            // Si MCUT-3 está inactiva, pasa a MCUT-1 o MCUT-7
            $fallbackSello = in_array('MCUT-1', $maquinasActivasInput, true) ? ['MCUT-1'] : ['MCUT-7'];
            $destinoSello = array_intersect($fallbackSello, $maquinasActivasInput);
            if (empty($destinoSello)) $destinoSello = $maquinasActivasInput;
            repartirEntreMaquinas($poolSello['ids'], $poolSello['time'], $destinoSello, $idsPorMaquina, $maquinas);
        }
    }

    // =========================================================
    // 2. ASIGNACIÓN TRABAJO NORMAL (CASCADA + SEGUNDA VUELTA)
    // =========================================================
    $especialistasColor = [
        'BLANCA' => ['MCUT-2', 'MCUT-4'],
        'NEGRA'  => ['MCUT-5', 'MCUT-6'],
    ];

    $especialistaExacto = [
        'G10_12' => ['BLANCA' => null,     'NEGRA' => null],
        'G14_16' => ['BLANCA' => 'MCUT-2', 'NEGRA' => 'MCUT-5'],
        'G18_22' => ['BLANCA' => 'MCUT-4', 'NEGRA' => 'MCUT-6'],
        'G24'    => ['BLANCA' => 'MCUT-4', 'NEGRA' => 'MCUT-6'],
    ];

    foreach (['G10_12', 'G14_16', 'G18_22', 'G24'] as $bucket) {
        $generalistas = ($bucket === 'G24') ? ['MCUT-3'] : ['MCUT-1', 'MCUT-3'];

        foreach (['BLANCA', 'NEGRA'] as $tinta) {
            $data = $poolGaugeNormal[$bucket][$tinta];
            if (empty($data['ids'])) continue;

            // Paso 2: Generalistas hasta 540 min
            $leftoverGen = asignarConTope(
                $data['ids'], $data['time'],
                $generalistas, $maquinasActivasInput, TOPE_BLOQUE,
                $idsPorMaquina, $maquinas
            );

            if (empty($leftoverGen['ids'])) continue;

            // Paso 3: Especialista correspondiente hasta 540 min
            $esp = $especialistaExacto[$bucket][$tinta];
            $maquinasEsp = $esp !== null ? [$esp] : $especialistasColor[$tinta];

            $leftoverEsp = asignarConTope(
                $leftoverGen['ids'], $leftoverGen['time'],
                $maquinasEsp, $maquinasActivasInput, TOPE_BLOQUE,
                $idsPorMaquina, $maquinas
            );

            if (empty($leftoverEsp['ids'])) continue;

            // Paso 3b: Si el especialista exacto se llena, intentar con la otra especialista del mismo color hasta 540 min
            $otrasEsp = array_diff($especialistasColor[$tinta], $maquinasEsp);
            if (!empty($otrasEsp)) {
                $leftoverEsp = asignarConTope(
                    $leftoverEsp['ids'], $leftoverEsp['time'],
                    $otrasEsp, $maquinasActivasInput, TOPE_BLOQUE,
                    $idsPorMaquina, $maquinas
                );
            }

            if (empty($leftoverEsp['ids'])) continue;

            // Paso 4 (Reinicio de ciclo): Si todas llegaron a 540 min, inician de nuevo desde MCUT-1 y MCUT-3
            $leftoverVuelta2 = asignarConTope(
                $leftoverEsp['ids'], $leftoverEsp['time'],
                $generalistas, $maquinasActivasInput, TOPE_BLOQUE * 2,
                $idsPorMaquina, $maquinas
            );

            if (empty($leftoverVuelta2['ids'])) continue;

            // Paso 5: Desborde total va a MCUT-7 (sin tope) o activas como último recurso
            $destinoFinal = in_array('MCUT-7', $maquinasActivasInput, true) ? ['MCUT-7'] : $maquinasActivasInput;
            repartirEntreMaquinas($leftoverVuelta2['ids'], $leftoverVuelta2['time'], $destinoFinal, $idsPorMaquina, $maquinas);
        }
    }

    // =========================================================
    // 3. ASIGNACIÓN POOL MCUT-7 (CALIBRES FUERA DE RANGO / CONS "C")
    // =========================================================
    foreach (['BLANCA', 'NEGRA'] as $tinta) {
        $pool = $poolMCUT7[$tinta];
        if (empty($pool['ids'])) continue;

        if (in_array('MCUT-7', $maquinasActivasInput, true)) {
            $idsPorMaquina['MCUT-7'] = array_merge($idsPorMaquina['MCUT-7'], $pool['ids']);
            $maquinas['MCUT-7'] += $pool['time'];
            continue;
        }

        // Si MCUT-7 no está activa: llena generalistas -> especialistas -> activas
        $leftoverGen = asignarConTope(
            $pool['ids'], $pool['time'],
            ['MCUT-1', 'MCUT-3'], $maquinasActivasInput, TOPE_BLOQUE,
            $idsPorMaquina, $maquinas
        );

        if (empty($leftoverGen['ids'])) continue;

        $leftoverEsp = asignarConTope(
            $leftoverGen['ids'], $leftoverGen['time'],
            $especialistasColor[$tinta], $maquinasActivasInput, TOPE_BLOQUE,
            $idsPorMaquina, $maquinas
        );

        if (empty($leftoverEsp['ids'])) continue;

        repartirEntreMaquinas($leftoverEsp['ids'], $leftoverEsp['time'], $maquinasActivasInput, $idsPorMaquina, $maquinas);
    }

    // Guardar asignaciones en Base de Datos
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