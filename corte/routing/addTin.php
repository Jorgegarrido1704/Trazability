<?php
require "../../app/conection.php";
require "timesReg.php";

if (isset($_GET['np'])) {
    $paramNp = $_GET['np'];
    if (strpos($paramNp, ',') !== false) {
        $datos = explode(',', $paramNp); 
    } else {
        $datos = [$paramNp]; 
    }

} else {
    echo "No se han recibido números de parte.";
    header("location:../registro.php");
}
       foreach ($datos as $np) {
        $delete=(mysqli_query($con,"DELETE FROM routing_models WHERE pn_routing='$np' and work_routing='10431'"));
    $buscar = mysqli_query($con, "SELECT terminal1,terminal2 FROM listascorte WHERE pn='$np' AND (terminal1 LIKE '%SOLDAR%' or terminal2 LIKE '%SOLDAR%' OR terminal1 LIKE '%Soldar%' or terminal2 LIKE '%Soldar%' )");
    if (mysqli_num_rows($buscar) > 0) {
            $totalVeces=mysqli_num_rows($buscar);
            $random = rand(0, count($tinSet) - 1);
            $terminal = "set tin point";
            $tiempos = $tinSet[$random];
        
            $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
           VALUES ('$np','10431','Pend','$terminal','$totalVeces','$tiempos','600')");    
        echo $np. " Ocupa un total de: " . $totalVeces . " veces: " . $terminal . " con tiempos de: " . $tiempos . "<br>";
    }
   }
header("location:addmangaterminal1.php?np=" . implode(',', $datos));