<?php

require "../app/conection.php";
require '../app/vendor/autoload.php'; 

date_default_timezone_set("America/Mexico_City");

try{
    $validarFaltante = mysqli_query($con, "SELECT * FROM po  Join precios ON po.pn != precios.pn ");


}catch(Exception $e){
    echo "Error: " . $e->getMessage();
}