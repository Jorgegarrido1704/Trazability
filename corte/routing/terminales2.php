<?php
require "../../app/conection.php";
require "timesReg.php";

if (isset($_GET['np'])) {
    if (strpos($_GET['np'], ',') !== false) {
        $datos = $_GET['np'];
    } else {
        $datos =  explode(',', $_GET['np']);
    }
} else {
    echo "No se han recibido números de parte.";
    header("location:../registro.php");
}
    $np=$datos[0];

    $buscar = mysqli_query($con, "SELECT DataTo,terminal2 FROM listascorte WHERE pn='$np' AND terminal2 IS NOT NULL and terminal2 !='' and 
    terminal2 not like 'Empalme%' AND terminal2 not like 'EMPALME%' and terminal2 not like 'SPL%' AND terminal2 not like 'SPLICE%'  
    AND   terminal2 not like 'JUMPER%' AND terminal2 not like 'Jumper%' order by terminal2 desc");
    if (mysqli_num_rows($buscar) > 0) {
        while ($row = mysqli_fetch_array($buscar)) {
            $terminal = $row['terminal2'];
            $conector=$row['DataTo'];
           
            if (strpos($terminal, '(')) {
                $terminal = substr($terminal, 0, strpos($terminal, '('));
            }
            if (isset($terminales[$terminal])) {
                $terminales[$terminal]=$terminales[$terminal]+1;
            } else {
                $terminales[$terminal] = 1;
            }
            if(!strpos($terminal, 'T3-') AND !strpos($terminal, 'T4-')){
                $random = rand(0, count($plugIn) - 1);
                $tiempoPlugIn = $plugIn[$random];
                $leyenda="Plug $terminal Terminal in $conector";
                 echo $terminal . " = 1   In $conector en $tiempoPlugIn segundos". "<br>";
                $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
                VALUES ('$np','10951','pend','$leyenda','1','$tiempoPlugIn','300')");    
            }
            
        }
        
    }
    foreach ($terminales as $terminal => $qtyTerminal) {
            $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','10081','FB-081','$terminal','$qtyTerminal','3.084','600')");    
        }


header("location:addmangaterminal1.php?np=" . implode(',', $datos));