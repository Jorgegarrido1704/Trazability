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

    // Pools de trabajo normal (sin sello) por máquina especialista
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

        // Restricción MCUT-7: fuera de calibres 10-24 (p.ej. 1,2,4,6,8) o cons con 'C'/'c'.
        // El calibre (gauge) manda sobre el terminal/sello: si el calibre cae en MCUT-7,
        // se va a MCUT-7 sin importar si tiene sello o no.
        if ($consEmpiezaC || !$calibreValido) {
            $poolMCUT7['ids'][] = $idCorte;
            $poolMCUT7['time'] += $tiempoTotal;
            continue;
        }

        // Restricción MCUT-3: todo el sello (calibre 10-24, cualquier tinta), exclusivo.
        if ($esSello) {
            $poolSello['ids'][] = $idCorte;
            $poolSello['time'] += $tiempoTotal;
            continue;
        }

        // Clasificación por especialistas (siempre sin sello, cons ya validado arriba)
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
    // Orden por ronda: (MCUT-2 y MCUT-5, calibre 14-16) -> (MCUT-4 y MCUT-6, calibre 18-24)
    //                   -> MCUT-1 (sobrante sin sello, cualquier calibre/tinta 10-24)
    //                   -> MCUT-3 (todo lo que tenga sello, exclusivo)
    //
    // IMPORTANTE: el pool de sello (con sello) NUNCA se mezcla con el pool "sin sello"
    // que llena MCUT-1. Cada uno avanza por su propio camino ronda a ronda; solo se
    // reparten juntos entre las máquinas de corte como último recurso si en una ronda
    // ya no hay más capacidad para abrir (p.ej. porque MCUT-1 o MCUT-3 están apagadas).
    // =========================================================

    $ordenEspecialistas = ['MCUT-2', 'MCUT-5', 'MCUT-4', 'MCUT-6'];
    $ordenMaquinasCorte = ['MCUT-1', 'MCUT-2', 'MCUT-3', 'MCUT-4', 'MCUT-5', 'MCUT-6'];

    $restoNormal = ['ids' => [], 'time' => 0.0]; // sin sello -> candidato exclusivo de MCUT-1
    $restoSello  = $poolSello;                    // con sello -> candidato exclusivo de MCUT-3

    $ronda = 1;
    $rondaMaxima = 500; // resguardo anti-loop infinito

    while ($ronda <= $rondaMaxima) {
        $limiteActual = $ronda * TAMANO_BATCH;

        // 1-4. Llena especialistas en orden: MCUT-2, MCUT-5, MCUT-4, MCUT-6
        foreach ($ordenEspecialistas as $maqEsp) {
            if (!empty($poolEsp[$maqEsp]['ids'])) {
                $res = asignarConTope(
                    $poolEsp[$maqEsp]['ids'], $poolEsp[$maqEsp]['time'],
                    [$maqEsp], $maquinasActivasInput, $limiteActual,
                    $idsPorMaquina, $maquinas
                );
                // Lo que no cupo en la especialista pasa al lote "sin sello" (candidato a MCUT-1)
                $restoNormal['ids'] = array_merge($restoNormal['ids'], $res['ids']);
                $restoNormal['time'] += $res['time'];
                $poolEsp[$maqEsp] = ['ids' => [], 'time' => 0.0];
            }
        }

        // Calibres 10-12 (sin especialista) se suman al lote "sin sello"
        if (!empty($poolEsp['G10_12']['ids'])) {
            $restoNormal['ids'] = array_merge($restoNormal['ids'], $poolEsp['G10_12']['ids']);
            $restoNormal['time'] += $poolEsp['G10_12']['time'];
            $poolEsp['G10_12'] = ['ids' => [], 'time' => 0.0];
        }

        // 5. LLENA MCUT-1 (todo lo sin sello, calibre 10-24, cualquier tinta)
        if (!empty($restoNormal['ids'])) {
            $restoNormal = asignarConTope(
                $restoNormal['ids'], $restoNormal['time'],
                ['MCUT-1'], $maquinasActivasInput, $limiteActual,
                $idsPorMaquina, $maquinas
            );
        }

        // 6. LLENA MCUT-3 (exclusivo: todo lo que tiene sello) — camino totalmente aparte
        if (!empty($restoSello['ids'])) {
            $restoSello = asignarConTope(
                $restoSello['ids'], $restoSello['time'],
                ['MCUT-3'], $maquinasActivasInput, $limiteActual,
                $idsPorMaquina, $maquinas
            );
        }

        if (empty($restoNormal['ids']) && empty($restoSello['ids'])) {
            break; // Todo el material normal y de sello quedó asignado
        }

        $activasCorte = array_values(array_intersect($ordenMaquinasCorte, $maquinasActivasInput));
        if (empty($activasCorte)) {
            break; // No hay máquinas de corte activas; no se puede seguir repartiendo
        }

        $espacioLibreRonda = 0.0;
        foreach ($activasCorte as $m) {
            $espacioLibreRonda += max(0.0, $limiteActual - $maquinas[$m]);
        }

        if ($espacioLibreRonda <= 0.0) {
            // Todas las máquinas de corte llegaron a su tope en esta ronda: abrir el
            // siguiente batch de 540 min y reintentar (restoNormal/restoSello se conservan).
            $ronda++;
            continue;
        }

        // Hay espacio libre en la ronda pero no se pudo colocar (p.ej. MCUT-1 o MCUT-3
        // están apagadas): reparto de último recurso, sin mezclar sello con lo demás.
        if (!empty($restoNormal['ids'])) {
            repartirEntreMaquinas($restoNormal['ids'], $restoNormal['time'], $activasCorte, $idsPorMaquina, $maquinas);
            $restoNormal = ['ids' => [], 'time' => 0.0];
        }
        if (!empty($restoSello['ids'])) {
            $activasSello = in_array('MCUT-3', $maquinasActivasInput, true) ? ['MCUT-3'] : $activasCorte;
            repartirEntreMaquinas($restoSello['ids'], $restoSello['time'], $activasSello, $idsPorMaquina, $maquinas);
            $restoSello = ['ids' => [], 'time' => 0.0];
        }
        break;
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