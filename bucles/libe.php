<?php 

require '../app/conection.php';

//buscarTIme

$times = mysqli_query($con, "SELECT id,corte,liberacion,ensamble,loom,calidad FROM tiempos where 
(liberacion = '' and corte != '' and ensamble !='') OR
 (ensamble != '' and loom = '' and calidad != '') ");

foreach ($times as $time) {
    $id = $time['id'];
    $liberacion = $time['liberacion'];
    $corte = $time['corte'];
    $ensamble = $time['ensamble'];
    $loom = $time['loom'];
    $calidad = $time['calidad'];

    
if($liberacion == '' and $ensamble != ''){
    mysqli_query($con,"UPDATE tiempos SET liberacion = '$corte' WHERE id = $id");
}
if($loom == '' and $calidad != ''){
    mysqli_query($con,"UPDATE tiempos SET loom = '$ensamble' WHERE id = $id");
}
    echo $id." - " . $corte ." - ". $liberacion. "<br>";
}