<?php 

require "../app/conection.php";
date_default_timezone_set("America/Mexico_city");
$date=date("d-m-Y");

$buscarventa=mysqli_query($con,"SELECT * FROM regsitrocalidad WHERE fecha LIKE '$date%'");
foreach($buscarventa as $venta){
    $fecha=$venta['fecha'];
    $pn=$venta['pn'];
    $buscarPrecio=mysqli_query($con,"SELECT price FROM precios WHERE pn='$pn' ");
    foreach($buscarPrecio as $pre){
        $pric=$pre['price'];
    }

    print($pn." ".$pric."<br>");
}
