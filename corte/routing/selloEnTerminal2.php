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
}else {
    echo "No se han recibido números de parte.";
    header("location:../registro.php");
}
foreach ($datos as $np) {

    $buscar = mysqli_query($con, "SELECT terminal2 FROM listascorte WHERE pn='$np' AND (terminal2 LIKE('%Sello%')
    or terminal2 LIKE('%SELLO%') or terminal2 LIKE('%sello%'))
     order by terminal2 desc");
    if (mysqli_num_rows($buscar) > 0) {
        while ($row = mysqli_fetch_array($buscar)) {
            $terminal = $row['terminal2'];
            
            if (strpos($terminal, '(')) {
               $terminal = explode('(', $terminal)[1];
            }
            if(strpos($terminal, ')')) {
                $terminal = explode(')', $terminal)[0];
            }
          
            if (isset($terminales[$terminal])) {
                $terminales[$terminal] = $terminales[$terminal] + 1;
            } else {
                $terminales[$terminal] = 1;
            }
         
        }
    
    foreach ($terminales as $terminal => $qtyTerminal) {
        $rand=rand(0, count($setSealTime)-1);
        $tiempoSetSeal=$setSealTime[$rand];
        $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','10381','Pend','$terminal','$qtyTerminal','$tiempoSetSeal','300')");
    }
}
}
header("location:addTin.php?np=" . implode(',', $datos));
//header("location:terminales2.php?np=" . implode(',', $datos));
