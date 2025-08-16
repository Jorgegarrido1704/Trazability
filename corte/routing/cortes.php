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
                $delete=(mysqli_query($con,"DELETE FROM routing_models WHERE pn_routing='$np' and (work_routing>'10001' and work_routing<'10061')"));
                $delete=(mysqli_query($con,"DELETE FROM routing_models WHERE pn_routing='$np' and (work_routing='11501' or work_routing='11701')"));
           
    $buscar = mysqli_query($con, "SELECT cons, tipo, aws, color,tamano FROM listascorte WHERE pn='$np' and tamano>0  ");
    if (mysqli_num_rows($buscar) > 0) {
        while ($row = mysqli_fetch_array($buscar)) {
            $cons = $row['cons'];
            $tipo = $row['tipo'];
            $aws = $row['aws'];
            $color = $row['color'];
            $tamano = $row['tamano'];
            $random = rand(0, count($corte) - 1);

            $tiempo = floatval($tamano) * $corte[$random] ;
            $dataLabel = 'Cutting cons ' . $cons . ' // Tipo:' . $tipo . '// AWG: ' . $aws . '// Color: ' . $color;
            $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','10001','FB036','$dataLabel','1','$tiempo','300')");

        
        }
        if(mysqli_num_rows($buscar) <=10){
            $testing=45;
            $packing=45;
        }else if(mysqli_num_rows($buscar) >10 and mysqli_num_rows($buscar) <=20){
            $testing=120;
            $packing=60;
        }else if(mysqli_num_rows($buscar) >20 and mysqli_num_rows($buscar) <=50){
            $testing=180;
            $packing=90;
        }else if(mysqli_num_rows($buscar) >50 ){
            $testing=240;
            $packing=180;}
            $label='Testing: '.mysqli_num_rows($buscar).' Circuits';
        $testingTimes = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','11501','Pend','$label','1','$testing','300')");
        $packingTimes = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','11701','Pend','Packing','1','$packing','300')");

    } else {
       // echo "No se encontraron registros para el número de parte: " . ($np). "<br>";
    }
}


header("location:twist.php?np=" . implode(',', $datos));