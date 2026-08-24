<?php

require 'app/conection.php';


const CAPACIDAD_MAX_GENERALISTA = 540.0; // minutos, tope individual de MCUT-1 y MCUT-3

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
        // Ninguna de las máquinas candidatas tiene espacio libre
        return ['ids' => $ids, 'time' => $tiempoTotal];
    }

    $restanteIds = $ids;

    if ($avgTime > 0 && $tiempoTotal > $capacidadTotal) {
        // No cabe todo: llenar cada máquina hasta su tope; lo que sobre se devuelve
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

    // Cabe todo: reparto proporcional a la capacidad libre de cada máquina
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

    // Los ids que sobran por redondeo van a la máquina con más espacio libre
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

/** Reparte un pool SIN tope entre un conjunto de máquinas, proporcional a la cantidad de IDs. */
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

/**
 * Recorre los "tiers" de prioridad en orden y devuelve el primero que
 * tenga al menos una máquina activa (filtrado a solo las activas).
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

    // Pools por rango de calibre y tinta, separando sello de trabajo normal
    $poolGauge = [
        'G10_12' => [
            'BLANCA' => ['idsSello' => [], 'timeSello' => 0.0, 'idsNormal' => [], 'timeNormal' => 0.0],
            'NEGRA'  => ['idsSello' => [], 'timeSello' => 0.0, 'idsNormal' => [], 'timeNormal' => 0.0],
        ],
        'G14_16' => [
            'BLANCA' => ['idsSello' => [], 'timeSello' => 0.0, 'idsNormal' => [], 'timeNormal' => 0.0],
            'NEGRA'  => ['idsSello' => [], 'timeSello' => 0.0, 'idsNormal' => [], 'timeNormal' => 0.0],
        ],
        'G18_22' => [
            'BLANCA' => ['idsSello' => [], 'timeSello' => 0.0, 'idsNormal' => [], 'timeNormal' => 0.0],
            'NEGRA'  => ['idsSello' => [], 'timeSello' => 0.0, 'idsNormal' => [], 'timeNormal' => 0.0],
        ],
        'G24' => [
            'BLANCA' => ['idsSello' => [], 'timeSello' => 0.0, 'idsNormal' => [], 'timeNormal' => 0.0],
            'NEGRA'  => ['idsSello' => [], 'timeSello' => 0.0, 'idsNormal' => [], 'timeNormal' => 0.0],
        ],
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

        // 2) Bucket de calibre
        if ($calibre === 10 || $calibre === 12) {
            $bucket = 'G10_12';
        } elseif ($calibre === 14 || $calibre === 16) {
            $bucket = 'G14_16';
        } elseif ($calibre === 24) {
            $bucket = 'G24';
        } else { // 18, 20, 22
            $bucket = 'G18_22';
        }

        if ($esSello) {
            $poolGauge[$bucket][$tinta]['idsSello'][] = $idCorte;
            $poolGauge[$bucket][$tinta]['timeSello'] += $tiempoTotal;
        } else {
            $poolGauge[$bucket][$tinta]['idsNormal'][] = $idCorte;
            $poolGauge[$bucket][$tinta]['timeNormal'] += $tiempoTotal;
        }
    }
    mysqli_stmt_close($stmtListas);

    // =========================================================
    // PROCESAMIENTO PRINCIPAL (orden: 10-12, 14-16, 18-22, 24, luego OTHER->MCUT-7)
    // =========================================================
    $especialistaPorBucketYTinta = [
        'G10_12' => ['BLANCA' => null,      'NEGRA' => null],
        'G14_16' => ['BLANCA' => 'MCUT-2',  'NEGRA' => 'MCUT-5'],
        'G18_22' => ['BLANCA' => 'MCUT-4',  'NEGRA' => 'MCUT-6'],
        'G24'    => ['BLANCA' => 'MCUT-4',  'NEGRA' => 'MCUT-6'],
    ];

    foreach (['G10_12', 'G14_16', 'G18_22', 'G24'] as $bucket) {
        $generalistas = ($bucket === 'G24') ? ['MCUT-3'] : ['MCUT-1', 'MCUT-3'];

        foreach (['BLANCA', 'NEGRA'] as $tinta) {
            $data = $poolGauge[$bucket][$tinta];
            $especialista = $especialistaPorBucketYTinta[$bucket][$tinta];

            // 1) Sello: exclusivo de MCUT-3 mientras tenga espacio bajo su tope
            $selloLeftover = asignarConTope(
                $data['idsSello'], $data['timeSello'],
                ['MCUT-3'], $maquinasActivasInput, CAPACIDAD_MAX_GENERALISTA,
                $idsPorMaquina, $maquinas
            );

            // El sello que no cupo en MCUT-3 se une al trabajo normal del mismo rango
            $idsNormal = array_merge($data['idsNormal'], $selloLeftover['ids']);
            $timeNormal = $data['timeNormal'] + $selloLeftover['time'];

            if (empty($idsNormal)) continue;

            // 2) Trabajo normal (+ sello desbordado): generalistas con tope
            $leftoverGeneralistas = asignarConTope(
                $idsNormal, $timeNormal,
                $generalistas, $maquinasActivasInput, CAPACIDAD_MAX_GENERALISTA,
                $idsPorMaquina, $maquinas
            );

            if (empty($leftoverGeneralistas['ids'])) continue;

            // 3) Lo que no cupo en los generalistas: especialista (sin tope) -> MCUT-7 -> último recurso
            $tiers = [];
            if ($especialista !== null) {
                $tiers[] = [$especialista];
            }
            $tiers[] = ['MCUT-7'];

            $destino = resolverDestino($tiers, $maquinasActivasInput);
            if (empty($destino)) {
                $destino = $maquinasActivasInput; // último recurso
            }

            repartirEntreMaquinas($leftoverGeneralistas['ids'], $leftoverGeneralistas['time'], $destino, $idsPorMaquina, $maquinas);
        }
    }

    // --- Pool MCUT-7 (calibres fuera de 10-24 / cons "C") ---
    foreach (['BLANCA', 'NEGRA'] as $tinta) {
        $pool = $poolOther[$tinta];
        if (empty($pool['ids'])) continue;

        if (in_array('MCUT-7', $maquinasActivasInput, true)) {
            $idsPorMaquina['MCUT-7'] = array_merge($idsPorMaquina['MCUT-7'], $pool['ids']);
            $maquinas['MCUT-7'] += $pool['time'];
            continue;
        }

        // MCUT-7 apagada: generalistas con tope primero...
        $leftoverGeneralistas = asignarConTope(
            $pool['ids'], $pool['time'],
            ['MCUT-1', 'MCUT-3'], $maquinasActivasInput, CAPACIDAD_MAX_GENERALISTA,
            $idsPorMaquina, $maquinas
        );

        if (empty($leftoverGeneralistas['ids'])) continue;

        // ...luego especialistas del color (sin tope) como último recurso normal
        $especialistas = ($tinta === 'BLANCA') ? ['MCUT-2', 'MCUT-4'] : ['MCUT-5', 'MCUT-6'];
        $destino = resolverDestino([$especialistas], $maquinasActivasInput);
        if (empty($destino)) {
            $destino = $maquinasActivasInput; // último recurso absoluto
        }

        repartirEntreMaquinas($leftoverGeneralistas['ids'], $leftoverGeneralistas['time'], $destino, $idsPorMaquina, $maquinas);
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