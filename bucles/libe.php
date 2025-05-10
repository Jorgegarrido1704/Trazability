<?php 

require '../app/conection.php';

//buscarTIme

$times = mysqli_query($con, "SELECT id,corte,liberacion,ensamble FROM tiempos where liberacion = '' and corte != '' and ensamble !='' ");

foreach ($times as $time) {
    $id = $time['id'];
    $liberacion = $time['liberacion'];
    $corte = $time['corte'];
    $ensamble = $time['ensamble'];
    

    mysqli_query($con,"UPDATE tiempos SET liberacion = '$corte' WHERE id = $id");
    echo $id." - " . $corte ." - ". $liberacion. "<br>";
}