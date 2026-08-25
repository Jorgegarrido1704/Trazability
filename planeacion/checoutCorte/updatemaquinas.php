<?php

require 'app/conection.php';

const TAMANO_BATCH = 540.0; // Tamaño de cada lote/ronda en minutos

/**
 * Asigna IDs respetando el tope acumulado en las máquinas candidatas.
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

    // Pools por categorías
    $poolSello = ['ids' => [], 'time' => 0.0];
    $poolMCUT7 = ['ids' => [], 'time' => 0.0];

    // Pools de trabajo normal por máquina especialista
    $poolEsp = [
        'MCUT-2' => ['ids' => [], 'time' => 0.0], // Blanca 14-16
        'MCUT-4' => ['ids' => [], 'time' => 0.0], // Blanca 18-24
        'MCUT-5' => ['ids' => [], 'time' => 0.0], // Negra 14-16
        'MCUT-6' => ['ids' => [], 'time' => 0.0], // Negra 18-24
        'G10_12' => ['ids' => [], 'time' => 0.0], // Calibres 10-12 (Blanca/Negra sin especialista)
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

        // Restricción MCUT-7: Fuera de 10-24 (1,2,4,6,8) o cons con 'C'/'c'
        if ($consEmpiezaC || !$calibreValido) {
            $poolMCUT7['ids'][] = $idCorte;
            $poolMCUT7['time'] += $tiempoTotal;
            continue;
        }

        // Restricción MCUT-3: Todo el sello
        if ($esSello) {
            $poolSello['ids'][] = $idCorte;
            $poolSello['time'] += $tiempoTotal;
            continue;
        }

        // Clasificación por Especialistas
        if ($calibre === 10 || $calibre === 12) {
            $poolEsp['G10_12']['ids'][] = $idCorte;
            $poolEsp['G10_12']['time'] += $tiempoTotal;
        } elseif ($calibre === 14 || $calibre === 16) {
            $destinoEsp = ($tinta === 'BLANCA') ? 'MCUT-2' : 'MCUT-5';
            $poolEsp[$destinoEsp]['ids'][] = $idCorte;
            $poolEsp[$destinoEsp]['time'] += $tiempoTotal;
        } else { // 18, 20, 22, 24
            $destinoEsp = ($tinta === 'BLANCA') ? 'MCUT-4' : 'MCUT-6';
            $poolEsp[$destinoEsp]['ids'][] = $idCorte;
            $poolEsp[$destinoEsp]['time'] += $tiempoTotal;
        }
    }
    mysqli_stmt_close($stmtListas);

    // =========================================================
    // PROCESAMIENTO SECUENCIAL POR RONDAS DE 540 MIN
    // Orden: MCUT-2 -> MCUT-4 -> MCUT-5 -> MCUT-6 -> MCUT-1 -> MCUT-3
    // =========================================================
    
    // Carga inicial de sellos a MCUT-3 con su primer tope de 540 min
    $selloLeftover = ['ids' => [], 'time' => 0.0];
    if (!empty($poolSello['ids'])) {
        $selloLeftover = asignarConTope(
            $poolSello['ids'], $poolSello['time'],
            ['MCUT-3'], $maquinasActivasInput, TAMANO_BATCH,
            $idsPorMaquina, $maquinas
        );
    }

    $ronda = 1;
    $ordenEspecialistas = ['MCUT-2', 'MCUT-4', 'MCUT-5', 'MCUT-6'];
    
    while (true) {
        $limiteActual = $ronda * TAMANO_BATCH;
        $desbordeRonda = ['ids' => [], 'time' => 0.0];

        // 1. LLENA MCUT-2, 2. LLENA MCUT-4, 3. LLENA MCUT-5, 4. LLENA MCUT-6
        foreach ($ordenEspecialistas as $maqEsp) {
            if (!empty($poolEsp[$maqEsp]['ids'])) {
                $res = asignarConTope(
                    $poolEsp[$maqEsp]['ids'], $poolEsp[$maqEsp]['time'],
                    [$maqEsp], $maquinasActivasInput, $limiteActual,
                    $idsPorMaquina, $maquinas
                );
                // Si la especialista se llena a 540 min, lo que sobra pasa a desborde
                $desbordeRonda['ids'] = array_merge($desbordeRonda['ids'], $res['ids']);
                $desbordeRonda['time'] += $res['time'];
                $poolEsp[$maqEsp] = ['ids' => [], 'time' => 0.0];
            }
        }

        // Agregar calibres 10-12 y sellos que no alcanzaron en la vuelta anterior
        $desbordeRonda['ids'] = array_merge($desbordeRonda['ids'], $poolEsp['G10_12']['ids'], $selloLeftover['ids']);
        $desbordeRonda['time'] += ($poolEsp['G10_12']['time'] + $selloLeftover['time']);
        $poolEsp['G10_12'] = ['ids' => [], 'time' => 0.0];
        $selloLeftover = ['ids' => [], 'time' => 0.0];

        if (empty($desbordeRonda['ids'])) {
            break; // No hay más material normal pendiente
        }

        // 5. LLENA MCUT-1 (Ambas tintas, calibres 10-24 hasta 540 min)
        $resMCUT1 = asignarConTope(
            $desbordeRonda['ids'], $desbordeRonda['time'],
            ['MCUT-1'], $maquinasActivasInput, $limiteActual,
            $idsPorMaquina, $maquinas
        );

        // 6. LLENA MCUT-3 (Lo que sobre de MCUT-1 hasta 540 min)
        $resMCUT3 = asignarConTope(
            $resMCUT1['ids'], $resMCUT1['time'],
            ['MCUT-3'], $maquinasActivasInput, $limiteActual,
            $idsPorMaquina, $maquinas
        );

        if (empty($resMCUT3['ids'])) {
            break; // Se asignó todo correctamente
        }

        // Si todavía sobra material después de llenar la 1 y la 3, iniciamos otra ronda desde la 2
        // Re-clasificamos los sobrantes para la siguiente vuelta
        $selloLeftover = ['ids' => [], 'time' => 0.0];
        $poolEsp['G10_12'] = ['ids' => [], 'time' => 0.0];
        $ordenMaquinasCorte = ['MCUT-1', 'MCUT-2', 'MCUT-3', 'MCUT-4', 'MCUT-5', 'MCUT-6'];
        $activasCorte = array_values(array_intersect($ordenMaquinasCorte, $maquinasActivasInput));

        // Checar si hay espacio libre en esta ronda
        $espacioLibreRonda = 0.0;
        foreach ($activasCorte as $m) {
            $espacioLibreRonda += max(0.0, $limiteActual - $maquinas[$m]);
        }

        if ($espacioLibreRonda <= 0.0) {
            $ronda++; // Abre el siguiente batch (+540 min) empezando de nuevo por MCUT-2
            $poolEsp['G10_12'] = $resMCUT3; // Se mandan a la siguiente ronda
        } else {
            // Residuo indivisible final repartido entre activas de corte (sin tocar MCUT-7)
            repartirEntreMaquinas($resMCUT3['ids'], $resMCUT3['time'], $activasCorte, $idsPorMaquina, $maquinas);
            break;
        }
    }

    // =========================================================
    // 7. LLENA MCUT-7 (AL FINAL CON SUS RESTRICCIONES EXCLUSIVAS)
    // =========================================================
    if (!empty($poolMCUT7['ids'])) {
        if (in_array('MCUT-7', $maquinasActivasInput, true)) {
            $idsPorMaquina['MCUT-7'] = array_merge($idsPorMaquina['MCUT-7'], $poolMCUT7['ids']);
            $maquinas['MCUT-7'] += $poolMCUT7['time'];
        } else {
            // Si MCUT-7 está apagada, se reparte entre las máquinas activas
            repartirEntreMaquinas($poolMCUT7['ids'], $poolMCUT7['time'], $maquinasActivasInput, $idsPorMaquina, $maquinas);
        }
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