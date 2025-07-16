<?php
require "../../app/conection.php";

if (isset($_GET['np'])) {
    if (strpos($_GET['np'], ',') !== false) {
        $datos[0] = $_GET['np'];
    } else {
        $datos =  explode(',', $_GET['np']);
    }
} else {
    echo "No se han recibido números de parte.";
    header("location:../registro.php");
}

foreach ($datos as $np) {
  /*  $buscarExistencia = mysqli_query($con, "SELECT `pn_routing` FROM routing_models WHERE pn_routing='$np' AND `work_routing`='10081' ");
    if (mysqli_num_rows($buscarExistencia) > 0) {
        $delete = (mysqli_query($con, "DELETE FROM routing_models WHERE pn_routing='$np' AND  work_routing='10081'"));
    }*/

    $buscar = mysqli_query($con, "SELECT terminal2 FROM listascorte WHERE pn='$np' AND terminal2 IS NOT NULL and terminal2 !='' and 
    terminal2 not like 'Empalme%' AND terminal2 not like 'EMPALME%' and terminal2 not like 'SPL%' AND terminal2 not like 'SPLICE%'   ");
    if (mysqli_num_rows($buscar) > 0) {
        while ($row = mysqli_fetch_array($buscar)) {
            $terminal = $row['terminal2'];
            if (strpos($terminal, '(')) {
                $terminal = substr($terminal, 0, strpos($terminal, '('));
            }
            if (isset($terminales[$terminal])) {
                $terminales[$terminal]++;
            } else {
                $terminales[$terminal] = 1;
            }
        }
        foreach ($terminales as $terminal => $qtyTerminal) {
            
            $buscarDatos=mysqli_query($con,"SELECT QtyTimes FROM routing_models WHERE pn_routing='$np' AND `work_routing`='10081' AND `work_description`='$terminal'");
            if(mysqli_num_rows($buscarDatos)>0){
                $row=mysqli_fetch_array($buscarDatos);
                $qtyTerminal=$qtyTerminal+$row['QtyTimes'];
                $update="UPDATE routing_models SET QtyTimes='$qtyTerminal' WHERE pn_routing='$np' AND `work_routing`='10081' AND `work_description`='$terminal'";
            }else{
            $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','10081','FB-081','$terminal','$qtyTerminal','3.084','600')");}
        echo $terminal . " = " . $qtyTerminal . "<br>";    
    }
    } else {
        echo "No se encontraron registros para el número de parte: " . htmlspecialchars($np);
    }
}


//header("location:addmangaterminal1.php?np=" . implode(',', $datos));