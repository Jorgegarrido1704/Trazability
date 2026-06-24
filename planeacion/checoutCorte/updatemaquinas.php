<?php

require 'app/conection.php';

// --- FUNCIONES AUXILIARES ---

/**
 * Cascada de tiempos que balancea minutos Y TAMBIÉN mueve los IDs de los registros correspondientes.
 */
function aplicarCascadaConIds(array &$raw, array &$rawIds, float $ocupadoSello10_12 = 0, float $bloque = 450, int $maxRondas = 20): void
{
    $target = $bloque;

    for ($ronda = 0; $ronda < $maxRondas; $ronda++) {
        $huboTransferencia = false;

        // 1) 10_12 <- 14_16
        $cargaReal10_12 = $raw['10_12'] + $ocupadoSello10_12;
        if ($cargaReal10_12 < $target && $raw['14_16'] > 0) {
            $prestamo = min($target - $cargaReal10_12, $raw['14_16']);
            if ($prestamo > 0) {
                $raw['10_12'] += $prestamo;
                $raw['14_16'] -= $prestamo;
                
                // Mover IDs físicamente en el array proporcionalmente
                $elementosAMover = ceil(count($rawIds['14_16']) * ($prestamo / ($raw['14_16'] + $prestamo)));
                for ($i = 0; $i < $elementosAMover; $i++) {
                    if (!empty($rawIds['14_16'])) {
                        $rawIds['10_12'][] = array_pop($rawIds['14_16']);
                    }
                }
                $huboTransferencia = true;
            }
        }

        // 2) 14_16 <- 18_24
        if ($raw['14_16'] < $target && $raw['18_24'] > 0) {
            $prestamo = min($target - $raw['14_16'], $raw['18_24']);
            if ($prestamo > 0) {
                $raw['14_16'] += $prestamo;
                $raw['18_24'] -= $prestamo;
                
                $elementosAMover = ceil(count($rawIds['18_24']) * ($prestamo / ($raw['18_24'] + $prestamo)));
                for ($i = 0; $i < $elementosAMover; $i++) {
                    if (!empty($rawIds['18_24'])) {
                        $rawIds['14_16'][] = array_pop($rawIds['18_24']);
                    }
                }
                $huboTransferencia = true;
            }
        }

        // 3) 18_24 <- 14_16
        if ($raw['18_24'] < $target && $raw['14_16'] > 0) {
            $prestamo = min($target - $raw['18_24'], $raw['14_16']);
            if ($prestamo > 0) {
                $raw['18_24'] += $prestamo;
                $raw['14_16'] -= $prestamo;
                
                $elementosAMover = ceil(count($rawIds['14_16']) * ($prestamo / ($raw['14_16'] + $prestamo)));
                for ($i = 0; $i < $elementosAMover; $i++) {
                    if (!empty($rawIds['14_16'])) {
                        $rawIds['18_24'][] = array_pop($rawIds['14_16']);
                    }
                }
                $huboTransferencia = true;
            }
        }

        if (!$huboTransferencia) {
            $cargaReal10_12 = $raw['10_12'] + $ocupadoSello10_12;
            if ($cargaReal10_12 >= $target && $raw['14_16'] >= $target && $raw['18_24'] >= $target) {
                $target += $bloque;
                continue;
            }
            break;
        }
    }
}

/**
 * Función para ejecutar el UPDATE masivo en la base de datos por máquina
 */
function actualizarMaquinaEnBD($con, array $ids, string $nombreMaquina): void {
    if (empty($ids)) return;
    
    // Sanitizar los IDs para evitar inyecciones si son strings o números
    $idsFormateados = array_map(function($id) use ($con) {
        return "'" . mysqli_real_escape_string($con, $id) . "'";
    }, $ids);
    
    $listaIds = implode(',', $idsFormateados);
    
    $sql = "UPDATE corte SET maq_asignada = '$nombreMaquina' WHERE id IN ($listaIds)";
    mysqli_query($con, $sql);
}


// --- FLUJO PRINCIPAL ---

try {
    $maquinas = [
        "MCUT-10"   => 0, "MCUT-1"  => 0, "MCUT-5" => 0,
        "MCUT-6"    => 0, "MCUT-4"  => 0, ">10"    => 0, 
        "TOTAL_MAQUINAS" => 0
    ];

    // Arrays para guardar los IDs de cada registro según su asignación final
    $idsPorMaquina = [
        "MCUT-10" => [], "MCUT-1"  => [], "MCUT-5" => [],
        "MCUT-6"  => [], "MCUT-4"  => [], ">10"    => []
    ];

    $selloNegra = 0; $selloBlanca = 0;

    $rawTimes = [
        'NEGRA'  => ['10_12' => 0, '14_16' => 0, '18_24' => 0],
        'BLANCA' => ['10_12' => 0, '14_16' => 0, '18_24' => 0],
    ];

    // Estructura espejo para almacenar los IDs temporalmente antes de la cascada
    $rawIds = [
        'NEGRA'  => ['10_12' => [], '14_16' => [], '18_24' => []],
        'BLANCA' => ['10_12' => [], '14_16' => [], '18_24' => []],
    ];

    $stmtListas = mysqli_prepare($con, "SELECT c.id, c.np, c.color, c.aws, c.cons, c.tipo, c.tamano, c.term1, c.term2, c.tintaColor, c.qty, c.time_ruteo 
                                         FROM corte c
                                         WHERE c.cutStatus != 'Cortado' AND c.tamano > 0 
                                         ORDER BY c.aws, c.color, c.term1, c.term2 DESC");

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

        $tiempo   = round($rowlistas['time_ruteo'] / 60, 2);
        $setUp_routing = 5;
        $tiempoTotal = $tiempo + $setUp_routing;

        // --- LÓGICA DE ASIGNACIÓN DE RANGO ---
        if (stripos($term1, "Sello") !== false || stripos($term2, "Sello") !== false) {
            $rango = 'sello';
        } elseif (stripos($cons, "Corte") !== false || stripos($cons, "CORTE") !== false) {
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

        // --- ACUMULACIÓN EN POZOS TEMPORALES ---
        if ($rango === 'sello') {
            if ($tinta === 'BLANCA') {
                // Sello Blanca va directo a MCUT-10 inicialmente
                $maquinas['MCUT-10'] += $tiempoTotal;
                $idsPorMaquina['MCUT-10'][] = $idCorte; 
                $selloBlanca += $tiempoTotal;
            } elseif ($tinta === 'NEGRA') {
                // Sello Negra va con su respectiva máquina inicial de Negra (MCUT-6)
                $maquinas['MCUT-6'] += $tiempoTotal;
                $idsPorMaquina['MCUT-6'][] = $idCorte;
                $selloNegra += $tiempoTotal;
            } else {
                $maquinas['>10'] += $tiempoTotal;
                $idsPorMaquina['>10'][] = $idCorte;
            }
        } elseif ($rango === '>10') {
            $maquinas['>10'] += $tiempoTotal;
            $idsPorMaquina['>10'][] = $idCorte;
        } else {
            if (isset($rawTimes[$tinta][$rango])) {
                $rawTimes[$tinta][$rango] += $tiempoTotal;
                $rawIds[$tinta][$rango][] = $idCorte; 
            } else {
                $maquinas['>10'] += $tiempoTotal;
                $idsPorMaquina['>10'][] = $idCorte;
            }
        }

        $maquinas['TOTAL_MAQUINAS'] += $tiempoTotal;
    }
    mysqli_stmt_close($stmtListas);

    // --- CASCADA DE TIEMPOS Y DE IDs ---
    aplicarCascadaConIds($rawTimes['NEGRA'], $rawIds['NEGRA'], $selloNegra);
    aplicarCascadaConIds($rawTimes['BLANCA'], $rawIds['BLANCA'], $selloBlanca);

    // --- MAPEO FINAL DE TIEMPOS Y AGRUPACIÓN DE IDs POST-CASCADA ---
    
    // ==========================================
    // SECCIÓN TINTA NEGRA
    // ==========================================
    // MCUT-6 (10_12 Negra + lo que tenga de Sello Negra)
    $maquinas['MCUT-6'] += $rawTimes['NEGRA']['10_12'];
    $idsPorMaquina['MCUT-6'] = array_merge($idsPorMaquina['MCUT-6'], $rawIds['NEGRA']['10_12']);

    // MCUT-5 (14_16 Negra)
    $maquinas['MCUT-5'] += $rawTimes['NEGRA']['14_16'];
    $idsPorMaquina['MCUT-5'] = array_merge($idsPorMaquina['MCUT-5'], $rawIds['NEGRA']['14_16']);

    // MCUT-4 (18_24 Negra)
    $maquinas['MCUT-4'] += $rawTimes['NEGRA']['18_24'];
    $idsPorMaquina['MCUT-4'] = array_merge($idsPorMaquina['MCUT-4'], $rawIds['NEGRA']['18_24']);


    // ==========================================
    // SECCIÓN TINTA BLANCA
    // ==========================================
    // MCUT-1 (10_12 Blanca)
    $maquinas['MCUT-1'] += $rawTimes['BLANCA']['10_12'];
    $idsPorMaquina['MCUT-1'] = array_merge($idsPorMaquina['MCUT-1'], $rawIds['BLANCA']['10_12']);

    // MCUT-10 (Comparte / absorbe rangos de Blanca + Sello Blanca)
    $maquinas['MCUT-10'] += $rawTimes['BLANCA']['14_16'] + $rawTimes['BLANCA']['18_24'];
    $idsPorMaquina['MCUT-10'] = array_merge($idsPorMaquina['MCUT-10'], $rawIds['BLANCA']['14_16'], $rawIds['BLANCA']['18_24']);


    $maquinas["MCUT-10TT"] = $maquinas["MCUT-10"];

    // --- EJECUCIÓN DEL UPDATE MASIVO EN BD ---
    foreach ($idsPorMaquina as $nombreMaquina => $arregloIds) {
        actualizarMaquinaEnBD($con, $arregloIds, $nombreMaquina);
    }

    // Devolver JSON limpio al front-end
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
?>