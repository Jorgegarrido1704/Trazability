<?php
require "../../app/conection.php";

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
    $buscarExistencia = mysqli_query($con, "SELECT `pn_routing` FROM routing_models WHERE pn_routing='$np' AND `work_routing`='10081' ");
    if (mysqli_num_rows($buscarExistencia) > 0) {
        $delete = (mysqli_query($con, "DELETE FROM routing_models WHERE pn_routing='$np' AND  work_routing='10081'"));
    }

    $buscar = mysqli_query($con, "SELECT terminal1 FROM listascorte WHERE pn='$np' AND terminal1 IS NOT NULL and terminal1 !='' and 
    terminal1 not like 'Empalme%' AND terminal1 not like 'EMPALME%' and terminal1 not like 'SPL%' AND terminal1 not like 'SPLICE%' order by terminal1 desc");
    if (mysqli_num_rows($buscar) > 0) {
        while ($row = mysqli_fetch_array($buscar)) {
            $terminal = $row['terminal1'];
           
            if (strpos($terminal, '(')) {
                $terminal = substr($terminal, 0, strpos($terminal, '('));
            }
            if (isset($terminales[$terminal])) {
                $terminales[$terminal]=$terminales[$terminal]+1;
            } else {
                $terminales[$terminal] = 1;
            }
            
        }
        
    }
    foreach ($terminales as $terminal => $qtyTerminal) {
            echo $terminal . " = " . $qtyTerminal . "<br>";


            $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','10081','FB-081','$terminal','$qtyTerminal','3.084','600')");
        }



header("location:terminales2.php?np=" . implode(',', $datos));