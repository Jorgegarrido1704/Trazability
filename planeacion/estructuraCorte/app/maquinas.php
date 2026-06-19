<?php

require 'conection.php';

try {
    $maquinas = [
        "MCUT-10" => 0, "MCUT-1" => 0, "MCUT-5" => 0,
        "MCUT-6"  => 0, "MCUT-4" => 0,
        ">10"     => 0, "MCUT-10SN" => 0, "MCUT-10SB" => 0, "TOTAL_MAQUINAS" => 0
    ];

    // Acumuladores "crudos" por tinta y rango, ANTES de aplicar la cascada.
    $rawTimes = [
        'NEGRA'  => ['10_12' => 0, '14_16' => 0, '18_24' => 0],
        'BLANCA' => ['10_12' => 0, '14_16' => 0, '18_24' => 0],
    ];

    // Mapeo de sello (no participa en la cascada, va directo a su máquina)
    $maquinaMapping = [
        'NEGRA' => [
            'sello' => 'MCUT-10SN',
        ],
        'BLANCA' => [
            'sello' => 'MCUT-10SB',
        ]
    ];

    $stmtListas = mysqli_prepare($con, "SELECT c.np, c.color, c.aws, c.cons, c.tipo, c.tamano, c.term1, c.term2, c.tintaColor, c.qty, c.time_ruteo 
                                             FROM corte c 
          JOIN registro r ON c.wo = r.wo 
                                              WHERE c.cutStatus != 'Cortado'  AND c.tamano > 0
            AND r.programado = 1  ORDER BY c.aws, c.color, c.term1, c.term2 DESC");

    if (!$stmtListas) {
        throw new Exception("Error al preparar la consulta: " . mysqli_error($con));
    }

    mysqli_stmt_execute($stmtListas);
    $resListas = mysqli_stmt_get_result($stmtListas);

    while ($rowlistas = mysqli_fetch_assoc($resListas)) {

        $calibre  = (int)$rowlistas['aws'];
        $color    = $rowlistas['color'];
        $term1    = $rowlistas['term1'];
        $term2    = $rowlistas['term2'];
        $cons     = $rowlistas['cons'];
        $tinta    = trim(strtoupper($rowlistas['tintaColor']));

        $tiempo   = round($rowlistas['time_ruteo'] / 60, 2);
        $setUp_routing = 5;
        $tiempoTotal = $tiempo + $setUp_routing;

        // --- LÓGICA DE ASIGNACIÓN DE RANGO ---

        // 1. Prioridad Máxima: ¿Tiene Sello en alguna de las terminales?
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

        // --- ACUMULACIÓN ---
        if ($rango === 'sello') {
            // El sello va directo a su máquina, no participa en la cascada.
            if (isset($maquinaMapping[$tinta]['sello'])) {
                $maquina = $maquinaMapping[$tinta]['sello'];
                $maquinas[$maquina] += $tiempoTotal;
            } else {
                $maquinas['>10'] += $tiempoTotal;
            }
        } elseif ($rango === '>10') {
            $maquinas['>10'] += $tiempoTotal;
        } else {
            // 10_12 / 14_16 / 18_24 van al acumulador crudo de su tinta.
            if (isset($rawTimes[$tinta][$rango])) {
                $rawTimes[$tinta][$rango] += $tiempoTotal;
            } else {
                $maquinas['>10'] += $tiempoTotal;
            }
        }

        $maquinas['TOTAL_MAQUINAS'] += $tiempoTotal;
    }
    mysqli_stmt_close($stmtListas);

    // --- CASCADA DE TIEMPOS ENTRE RANGOS, EN BLOQUES DE 450, RESPETANDO CONEXIONES DIRECTAS ---
    // Conexiones permitidas:
    //   10_12  <- 14_16             (10_12 SOLO puede recibir de 14_16)
    //   14_16  <- 18_24             (14_16 puede recibir de 18_24)
    //   18_24  <- 14_16 (remanente) (18_24 puede recuperar de lo que le quede a 14_16)
    // Se repite en bloques de 450 mientras sigan ocurriendo transferencias,
    // para que el excedente se siga acomodando en bloques adicionales si hay
    // suficiente trabajo+oferta en la cadena.
    //
    // $ocupadoSello10_12: minutos de SELLO que YA están cargados en la misma
    // máquina física que 10_12 (MCUT-3). No se mueven ni se prestan, pero SÍ
    // cuentan para decidir si 10_12 ya tiene suficiente carga y no necesita
    // seguir jalando minutos de 14_16.
    function aplicarCascada(array &$raw, float $ocupadoSello10_12 = 0, float $bloque = 450, int $maxRondas = 20): void
    {
        $target = $bloque;

        for ($ronda = 0; $ronda < $maxRondas; $ronda++) {
            $huboTransferencia = false;

            // 1) 10_12 <- 14_16 (se considera lo que el sello ya ocupa en esta máquina)
            $cargaReal10_12 = $raw['10_12'] + $ocupadoSello10_12;
            if ($cargaReal10_12 < $target && $raw['14_16'] > 0) {
                $prestamo = min($target - $cargaReal10_12, $raw['14_16']);
                if ($prestamo > 0) {
                    $raw['10_12'] += $prestamo;
                    $raw['14_16'] -= $prestamo;
                    $huboTransferencia = true;
                }
            }

            // 2) 14_16 <- 18_24
            if ($raw['14_16'] < $target && $raw['18_24'] > 0) {
                $prestamo = min($target - $raw['14_16'], $raw['18_24']);
                if ($prestamo > 0) {
                    $raw['14_16'] += $prestamo;
                    $raw['18_24'] -= $prestamo;
                    $huboTransferencia = true;
                }
            }

            // 3) 18_24 <- 14_16 (lo que le quede)
            if ($raw['18_24'] < $target && $raw['14_16'] > 0) {
                $prestamo = min($target - $raw['18_24'], $raw['14_16']);
                if ($prestamo > 0) {
                    $raw['18_24'] += $prestamo;
                    $raw['14_16'] -= $prestamo;
                    $huboTransferencia = true;
                }
            }

            if (!$huboTransferencia) {
                // Si las tres (10_12 ya con su sello incluido) alcanzaron el target,
                // subimos al siguiente bloque de 450 para ver si aún hay oferta que acomodar.
                $cargaReal10_12 = $raw['10_12'] + $ocupadoSello10_12;
                if ($cargaReal10_12 >= $target && $raw['14_16'] >= $target && $raw['18_24'] >= $target) {
                    $target += $bloque;
                    continue;
                }
                // No hubo transferencia y no todas llegaron al target:
                // ya se repartió todo lo posible con las conexiones permitidas.
                break;
            }
        }
    }

    aplicarCascada($rawTimes['NEGRA'], $maquinas['MCUT-10SN']);
    aplicarCascada($rawTimes['BLANCA'], $maquinas['MCUT-10SB']);

    // --- MAPEO FINAL A MÁQUINAS ---
    $maquinas['MCUT-10'] += $rawTimes['NEGRA']['10_12'];
    $maquinas['MCUT-5']  += $rawTimes['NEGRA']['14_16'];
    $maquinas['MCUT-4']  += $rawTimes['NEGRA']['18_24'];

    $maquinas['MCUT-1']  += $rawTimes['BLANCA']['10_12'];
    // BLANCA 14_16 y 18_24 comparten máquina (MCUT-6), por eso se suman aquí.
    $maquinas['MCUT-6']  += $rawTimes['BLANCA']['14_16'] + $rawTimes['BLANCA']['18_24'];

    $maquinas["MCUT-10TT"] = $maquinas["MCUT-10"] + $maquinas["MCUT-10SN"] + $maquinas["MCUT-10SB"];

    // Devolver JSON limpio
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