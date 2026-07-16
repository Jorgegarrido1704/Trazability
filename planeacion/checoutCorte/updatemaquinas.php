<?php

require 'app/conection.php';

function balancearCargaPorTinta(array $poolIds, array $poolTimes, array $maquinasActivas, array &$idsPorMaquina, array &$maquinas): void {
    if (empty($maquinasActivas)) return;

    $tiempoTotalPool = array_sum($poolTimes);
    $numMaquinas = count($maquinasActivas);
    $cargaIdealPorMaquina = $tiempoTotalPool / $numMaquinas;

    // Consolidar todos los IDs secuencialmente para su distribución uniforme
    $todosLosIds = [];
    foreach (['10_12', '14_16', '18_24'] as $rango) {
        if (!empty($poolIds[$rango])) {
            $todosLosIds = array_merge($todosLosIds, $poolIds[$rango]);
        }
    }

    $totalElementos = count($todosLosIds);
    if ($totalElementos === 0) return;

    // CRÍTICO: Reparto equitativo de IDs basado en la proporción de tiempo
    $elementosPorMaquina = ceil($totalElementos / $numMaquinas);
    $chunksIds = array_chunk($todosLosIds, $elementosPorMaquina);

    foreach ($maquinasActivas as $index => $maq) {
        if (isset($chunksIds[$index])) {
            $idsPorMaquina[$maq] = array_merge($idsPorMaquina[$maq], $chunksIds[$index]);
            // Asignación proporcional del tiempo estimado
            $proporcionTiempo = count($chunksIds[$index]) / $totalElementos;
            $maquinas[$maq] += ($tiempoTotalPool * $proporcionTiempo);
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

    $activaBlancas = array_intersect(["MCUT-1", "MCUT-2", "MCUT-3"], $maquinasActivasInput);
    $activaNegras  = array_intersect(["MCUT-4", "MCUT-5", "MCUT-6"], $maquinasActivasInput);
    $activaMayor10 = in_array("MCUT-7", $maquinasActivasInput);

    $rawTimes = [
        'NEGRA'  => ['10_12' => 0, '14_16' => 0, '18_24' => 0, 'sello' => 0],
        'BLANCA' => ['10_12' => 0, '14_16' => 0, '18_24' => 0, 'sello' => 0],
    ];

    $rawIds = [
        'NEGRA'  => ['10_12' => [], '14_16' => [], '18_24' => [], 'sello' => []],
        'BLANCA' => ['10_12' => [], '14_16' => [], '18_24' => [], 'sello' => []],
    ];

    // CRÍTICO: se separa la carga ">10" por tinta para no mezclar blanca/negra
    // al momento de repartirla entre máquinas (misma exclusividad que el resto del pool).
    $tiempoMayor10 = ['NEGRA' => 0, 'BLANCA' => 0];
    $idsMayor10 = ['NEGRA' => [], 'BLANCA' => []];

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
        $cons     = $rowlistas['cons'];
        $tinta    = trim(strtoupper($rowlistas['tintaColor']));

        if ($tinta !== 'BLANCA' && $tinta !== 'NEGRA') {
            $tinta = 'NEGRA'; 
        }

        $tiempoTotal = round($rowlistas['time_ruteo'] / 60, 2) + 5;

        if (stripos($term1, "Sello") !== false || stripos($term2, "Sello") !== false) {
            $rango = 'sello';
        } elseif (stripos($cons, "C") !== false || stripos($cons, "C") !== false) {
            $rango = '>10';
        } else {
            if ($calibre == 10 || $calibre == 12) {
                $rango = '10_12';
            } elseif ($calibre == 14 || $calibre == 16) {
                $rango = '14_16';
            } elseif ($calibre >= 18 && $calibre <= 24 && $calibre % 2 == 0) {
                $rango = '18_24';
            } else {
                $rango = '>10';
            }
        }

        if ($rango === '>10') {
            $tiempoMayor10[$tinta] += $tiempoTotal;
            $idsMayor10[$tinta][] = $idCorte;
        } else {
            $rawTimes[$tinta][$rango] += $tiempoTotal;
            $rawIds[$tinta][$rango][] = $idCorte;
        }

        $maquinas['TOTAL_MAQUINAS'] += $tiempoTotal;
    }
    mysqli_stmt_close($stmtListas);

    // CRÍTICO: Si no hay máquinas de un color específico activas, su carga pasa al pool del color opuesto
    if (empty($activaBlancas) && !empty($activaNegras)) {
        foreach (['10_12', '14_16', '18_24', 'sello'] as $r) {
            $rawTimes['NEGRA'][$r] += $rawTimes['BLANCA'][$r];
            $rawIds['NEGRA'][$r] = array_merge($rawIds['NEGRA'][$r], $rawIds['BLANCA'][$r]);
            $rawTimes['BLANCA'][$r] = 0; $rawIds['BLANCA'][$r] = [];
        }
    } elseif (empty($activaNegras) && !empty($activaBlancas)) {
        foreach (['10_12', '14_16', '18_24', 'sello'] as $r) {
            $rawTimes['BLANCA'][$r] += $rawTimes['NEGRA'][$r];
            $rawIds['BLANCA'][$r] = array_merge($rawIds['BLANCA'][$r], $rawIds['NEGRA'][$r]);
            $rawTimes['NEGRA'][$r] = 0; $rawIds['NEGRA'][$r] = [];
        }
    }

    // Integrar Sellos a los flujos principales antes de balancear
    foreach (['BLANCA', 'NEGRA'] as $t) {
        if (!empty($rawIds[$t]['sello'])) {
            $rawTimes[$t]['10_12'] += $rawTimes[$t]['sello'];
            $rawIds[$t]['10_12'] = array_merge($rawIds[$t]['10_12'], $rawIds[$t]['sello']);
        }
    }

    // Ejecutar balance dinámico según máquinas encendidas
    // MCUT-1/2/3 (activaBlancas) solo reciben pool BLANCA; MCUT-4/5/6 (activaNegras) solo pool NEGRA.
    balancearCargaPorTinta($rawIds['BLANCA'], $rawTimes['BLANCA'], array_values($activaBlancas), $idsPorMaquina, $maquinas);
    balancearCargaPorTinta($rawIds['NEGRA'], $rawTimes['NEGRA'], array_values($activaNegras), $idsPorMaquina, $maquinas);

    // CRÍTICO: Gestión de contingencia para la carga mayor a 10 (>10) si MCUT-7 está apagada.
    // Se respeta la exclusividad de tinta: lo BLANCA solo va a MCUT-1/2/3 y lo NEGRA solo a
    // MCUT-4/5/6. Solo si un color no tiene NINGUNA máquina activa, su carga ">10" se
    // reasigna al color opuesto como último recurso (igual que ya hacía el resto del script).
    $tiempoMayor10Total = $tiempoMayor10['NEGRA'] + $tiempoMayor10['BLANCA'];
    if ($tiempoMayor10Total > 0) {
        if ($activaMayor10) {
            $maquinas['MCUT-7'] = $tiempoMayor10Total;
            $idsPorMaquina['MCUT-7'] = array_merge($idsMayor10['NEGRA'], $idsMayor10['BLANCA']);
        } else {
            $destinoPorTinta = [];

            if (!empty($activaBlancas)) {
                $destinoPorTinta['BLANCA'] = array_values($activaBlancas);
            } elseif (!empty($activaNegras)) {
                $destinoPorTinta['BLANCA'] = array_values($activaNegras); // fallback: no hay máquinas blancas encendidas
            } else {
                $destinoPorTinta['BLANCA'] = [];
            }

            if (!empty($activaNegras)) {
                $destinoPorTinta['NEGRA'] = array_values($activaNegras);
            } elseif (!empty($activaBlancas)) {
                $destinoPorTinta['NEGRA'] = array_values($activaBlancas); // fallback: no hay máquinas negras encendidas
            } else {
                $destinoPorTinta['NEGRA'] = [];
            }

            foreach (['NEGRA', 'BLANCA'] as $tintaKey) {
                $idsTinta = $idsMayor10[$tintaKey];
                $tiempoTinta = $tiempoMayor10[$tintaKey];
                $maquinasDestino = $destinoPorTinta[$tintaKey];

                if (empty($idsTinta) || empty($maquinasDestino)) continue;

                $numMaquinasGral = count($maquinasDestino);
                $chunksGral = array_chunk($idsTinta, (int) ceil(count($idsTinta) / $numMaquinasGral));
                foreach ($maquinasDestino as $gIdx => $gMaq) {
                    if (isset($chunksGral[$gIdx])) {
                        $idsPorMaquina[$gMaq] = array_merge($idsPorMaquina[$gMaq], $chunksGral[$gIdx]);
                        $maquinas[$gMaq] += ($tiempoTinta * (count($chunksGral[$gIdx]) / count($idsTinta)));
                    }
                }
            }
        }
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