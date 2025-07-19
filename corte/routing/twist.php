<?php 
require "../../app/conection.php";
require "timesReg.php";

if (isset($_GET['np'])) {
    if(strpos($_GET['np'], ',') !== false){
        $datos [0]= $_GET['np'];   
    }else{
    $datos =  explode(',', $_GET['np']);
    }
} else {
    echo "No se han recibido números de parte.";
    header("location:../registro.php");
    
}

foreach ($datos as $np) {            
    $buscar = mysqli_query($con, "SELECT cons, tipo, aws, color,tamano FROM listascorte WHERE pn='$np' and tamano>0  and cons LIKE 'T%'  ORDER BY cons DESC");
    if (mysqli_num_rows($buscar) > 0) {
        while ($row = mysqli_fetch_array($buscar)) {
            $cons = $row['cons'];
            $tipo = $row['tipo'];
            $aws = $row['aws'];
            $color = $row['color'];
            $tamano = $row['tamano'];
            $random = rand(0, count($twistMm) - 1);
            $tiempo = floatval($tamano) * $twistMm[$random] ;   
            $verifiacion="Twist ".explode("-",$cons)[0];
           
            $buscarExistencia = mysqli_query($con, "SELECT work_description FROM routing_models WHERE pn_routing='$np' and work_routing='10061' and work_description LIKE '$verifiacion%' ");
            if(mysqli_num_rows($buscarExistencia)>0){
                $row=mysqli_fetch_array($buscarExistencia);
                $dataLabel=$row['work_description'];
                $newDataLabel=$dataLabel." , ".$cons;
                $update=mysqli_query($con,"UPDATE routing_models SET work_description='$newDataLabel' WHERE pn_routing='$np' and work_routing='10061' and work_description LIKE '$verifiacion%' ");
            }else{
                $dataLabel = "Twist ". $cons ;
            $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','10061','Pending','$dataLabel','1','$tiempo','600')");
            }
        }}}

header("location:terminales.php?np=" . implode(',', $datos));