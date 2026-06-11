<?php

require '../conection.php';

try {
    $color = isset($_GET['color']) ? $_GET['color'] : '';
    $awg = isset($_GET['awg']) ? $_GET['awg'] : '';
    $tipo = isset($_GET['type']) ? $_GET['type'] : '';
    $maquina = isset($_GET['maquina']) ? $_GET['maquina'] : '';
    $calibres = [];
    $totalCables = 0;
    $tintaNegra = 0;
    $tintaBlanca = 0;
    $tintaNegraOpt = 0;
    $tintaBlancaOpt = 0;
    $tinta = '';
    $tiempo = '';
    $maxtime =135000;
    
    // CORRECCIÓN: Inicializar la variable del acumulador de tiempo
    $tiempoTotal = 0; 
    $i = 0;
    
    // CORRECCIÓN: Se agregó 'time_ruteo' a la primera consulta SQL también
    if ($maquina == "MCUT-1") {
       $qry ="SELECT np, color, aws, cons, tipo, tamano, term1, term2, tintaColor, qty, time_ruteo 
                                             FROM corte 
                                             WHERE cutStatus != 'Cortado' AND aws IN ('10','12','14') AND tintaColor='BLANCA'
                                            AND  (term1 NOT LIKE '%Sello%' OR term2 NOT LIKE '%Sello%')
                                              AND tipo IN ('GXL','TXL','SGX','UL1569') AND qty>20
                                             ORDER BY  aws ASC, 
                                             term1 ASC,
                                             CASE 
                                                WHEN term2 LIKE CONCAT('%',term1,'%') THEN 0 
                                                ELSE 1
                                            END,
                                            tipo ASC";
                                             $maxtime=27000;
    }elseif ($maquina == "MCUT-6") {
       $qry ="SELECT np, color, aws, cons, tipo, tamano, term1, term2, tintaColor, qty, time_ruteo 
                                             FROM corte 
                                             WHERE cutStatus != 'Cortado' AND aws IN ('16','18','20','22','24') AND tintaColor='BLANCA'
                                             AND  (term1 NOT LIKE '%Sello%' OR term2 NOT LIKE '%Sello%')
                                              AND tipo IN ('GXL','TXL','SGX','UL1569') AND qty>20
                                             ORDER BY  aws ASC, 
                                             term1 ASC,
                                             CASE 
                                                WHEN term2 LIKE CONCAT('%',term1,'%') THEN 0 
                                                ELSE 1
                                            END,
                                            tipo ASC";
                                             $maxtime=27000;
    }elseif ($maquina == "MCUT-10") {
       $qry ="SELECT np, color, aws, cons, tipo, tamano, term1, term2, tintaColor, qty, time_ruteo 
                                             FROM corte 
                                             WHERE cutStatus != 'Cortado' AND qty>20 AND ((aws IN ('10','12') AND tintaColor='NEGRA'
                                             ) or (aws IN ('18','16','14')   AND (term1 LIKE '%Sello%' OR term2 LIKE '%Sello%')
                                             ))  AND tipo IN ('GXL','TXL','SGX','UL1569')
                                             ORDER BY  aws ASC, 
                                             term1 ASC,
                                             CASE 
                                                WHEN term2 LIKE CONCAT('%',term1,'%') THEN 0 
                                                ELSE 1
                                            END,
                                            tipo ASC";
                                             $maxtime=27000;
    }elseif ($maquina == "MCUT-5") {
       $qry ="SELECT np, color, aws, cons, tipo, tamano, term1, term2, tintaColor, qty, time_ruteo 
                                             FROM corte 
                                             WHERE cutStatus != 'Cortado' AND aws IN ('14','16') AND tintaColor='NEGRA'
                                             AND ( term1 NOT LIKE '%Sello%' or term2 NOT LIKE '%Sello%')
                                              AND tipo IN ('GXL','TXL','SGX','UL1569') AND qty>20
                                             ORDER BY  aws ASC, 
                                             term1 ASC,
                                             CASE 
                                                WHEN term2 LIKE CONCAT('%',term1,'%') THEN 0 
                                                ELSE 1
                                            END,
                                            tipo ASC";
                                             $maxtime=27000;
    }elseif ($maquina == "MCUT-4") {
       $qry ="SELECT np, color, aws, cons, tipo, tamano, term1, term2, tintaColor, qty, time_ruteo 
                                             FROM corte 
                                             WHERE cutStatus != 'Cortado' AND aws IN ('18','20','22','24') AND tintaColor='NEGRA'
                                             AND  (term1 NOT LIKE '%Sello%' OR term2 NOT LIKE '%Sello%')
                                              AND tipo IN ('GXL','TXL','SGX','UL1569') AND qty>20
                                             ORDER BY  aws ASC, 
                                             term1 ASC,
                                             CASE 
                                                WHEN term2 LIKE CONCAT('%',term1,'%') THEN 0 
                                                ELSE 1
                                            END,
                                            tipo ASC";
                                             $maxtime=27000;
    } else if ($maquina == 'todas') {
       $qry ="SELECT np, color, aws, cons, tipo, tamano, term1, term2, tintaColor, qty, time_ruteo 
                                             FROM corte 
                                             WHERE cutStatus != 'Cortado' 
                                              AND tipo IN ('GXL','TXL','SGX','UL1569') AND qty>20
                                             ORDER BY 
                                             aws ASC, 
                                             term1 ASC,
                                             CASE 
                                                WHEN term2 LIKE CONCAT('%',term1,'%') THEN 0 
                                                ELSE 1
                                            END,
                                            tipo ASC
                                            ";
    }
    $listasdecorte= mysqli_query($con,$qry);
    while ($rowlistas = mysqli_fetch_array($listasdecorte)) {
        $minutos=0;
        $pn = $rowlistas['np'];
        $calibre = $rowlistas['aws'];
        $consumo = $rowlistas['cons'];
        $tipo = $rowlistas['tipo'];
        $color = $rowlistas['color'];
        $tamano = round($rowlistas['tamano'], 2);
        $terminal1 = $rowlistas['term1'];
        $terminal2 = $rowlistas['term2'];
        $tinta = $rowlistas['tintaColor'];
        $qty = $rowlistas['qty'];
        //$time_ruteo = $rowlistas['time_ruteo']+300;
        $time_ruteo = round((2.92*$qty)+180,2);
        $minutos=round(($time_ruteo/60),2);
        
        // Sumamos el tiempo de la fila actual al acumulador
        $tiempoTotal += $time_ruteo;
        
        // Si el tiempo acumulado NO supera los 27000, lo agregamos al array
        if ($tiempoTotal <= $maxtime) {
            $calibres[] = [ // Nota: puedes usar [] vacío, PHP auto-incrementa el índice solo
                'pn' => $pn,
                'calibre' => $calibre,
                'consumo' => $consumo,
                'tipo' => $tipo,
                'color' => $color,
                'tamano' => $tamano,
                'Qty' => $qty,
                'min' => $minutos,
                'tinta' => $tinta,
                'terminal1' => $terminal1,
                'terminal2' => $terminal2
            ];                  
        } else {
            // Si la suma de esta fila ya hace que supere los 27000, salimos del while por completo
            break;
        }
    }

    // Devolvemos el JSON con los registros que alcanzaron a entrar en las 27000 unidades de tiempo
    echo json_encode($calibres);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    echo json_encode([]); // Es buena idea retornar un JSON vacío en lugar de nada si hay error
}

?>