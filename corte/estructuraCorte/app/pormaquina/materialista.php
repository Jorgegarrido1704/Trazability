<?php

require '../conection.php';

try {
    $color = isset($_GET['color']) ? $_GET['color'] : '';
    $awg = isset($_GET['awg']) ? $_GET['awg'] : '';
    $tipo = isset($_GET['type']) ? $_GET['type'] : '';
    $maquina = isset($_GET['maquina']) ? $_GET['maquina'] : 'todas';
    $calibres = [];
    $totalCables = 0;
    $tintaNegra = 0;
    $tintaBlanca = 0;
    $tintaNegraOpt = 0;
    $tintaBlancaOpt = 0;
    $tinta = '';
    $tiempo = '';
    $maxtime =135000;
    $cables = $terminales = $herramental = [];
    
    // CORRECCIÓN: Inicializar la variable del acumulador de tiempo
    $tiempoTotal = 0; 
    $i = 0;
       
    // CORRECCIÓN: Se agregó 'time_ruteo' a la primera consulta SQL también
    if ($maquina == "MCUT-1") {
       $qry ="SELECT c.np, c.color, c.aws, c.cons, c.tipo, c.tamano, c.term1, c.term2, c.tintaColor, c.qty, c.time_ruteo 
                                             FROM corte 
                                             WHERE cutStatus != 'Cortado' AND aws IN ('10','12','14') AND tintaColor='BLANCA'
                                            AND  (term1 NOT LIKE '%Sello%' AND term2 NOT LIKE '%Sello%')
                                              AND tipo IN ('GXL','TXL','SGX','UL1569') AND qty>20
                                             ORDER BY urgencia DESC,
                                              aws ASC, 
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
                                             AND  (term1 NOT LIKE '%Sello%' AND term2 NOT LIKE '%Sello%')
                                              AND tipo IN ('GXL','TXL','SGX','UL1569') AND qty>20
                                             ORDER BY  urgencia DESC, aws ASC, 
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
                                             ORDER BY urgencia DESC,
                                              aws ASC, 
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
                                             AND ( term1 NOT LIKE '%Sello%' AND term2 NOT LIKE '%Sello%')
                                              AND tipo IN ('GXL','TXL','SGX','UL1569') AND qty>20
                                             ORDER BY urgencia DESC,
                                              aws ASC, 
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
                                             AND  (term1 NOT LIKE '%Sello%' AND term2 NOT LIKE '%Sello%')
                                              AND tipo IN ('GXL','TXL','SGX','UL1569') AND qty>20
                                             ORDER BY  urgencia DESC,
                                             aws ASC, 
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
                                             urgencia DESC, 
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
            // if exist the key in the array no add key else add key
            if (array_key_exists($calibre."-".$tipo."-".$color, $cables)) {
                $cables[$calibre."-".$tipo."-".$color]+=$minutos;
            }else{
                $cables[$calibre."-".$tipo."-".$color]=$minutos;
            }
            if(!array_key_exists($terminal1, $terminales) && stripos($terminal1, 'Empalme') === false){
                $terminales[$terminal1]=1;
            }
            if(!array_key_exists($terminal2, $terminales) && stripos($terminal2, 'Empalme') === false ){
                $terminales[$terminal2]=1;
            }
           
                     
        } else {
           
            break;
        }
    }
    $datos=array();
    $datos['cables']=$cables;
    $datos['terminales']=$terminales; 
   
    // Devolvemos el JSON con los registros que alcanzaron a entrar en las 27000 unidades de tiempo
    echo json_encode($datos);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    echo json_encode([]); // Es buena idea retornar un JSON vacío en lugar de nada si hay error
}

?>