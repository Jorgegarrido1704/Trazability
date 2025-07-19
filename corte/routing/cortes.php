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
     $buscarExistencia = mysqli_query($con, "SELECT `pn_routing` FROM routing_models WHERE pn_routing='$np' ");
            if(mysqli_num_rows($buscarExistencia)>0){
                $delete=(mysqli_query($con,"DELETE FROM routing_models WHERE pn_routing='$np'"));
            }
            
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
            VALUES ('$np','10001','FB036','$dataLabel','1','$tiempo','600')");

        
        }
    } else {
        echo "No se encontraron registros para el número de parte: " . htmlspecialchars($np);
    }
}


header("location:twist.php?np=" . implode(',', $datos));