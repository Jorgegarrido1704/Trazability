<?php

require "../app/conection.php";
require '../app/vendor/autoload.php'; 

date_default_timezone_set("America/Mexico_City");

try{
$validarFaltante = mysqli_query($con, "SELECT DISTINCT po.pn FROM po  ORDER BY po.id  DESC" );

while($row = mysqli_fetch_array($validarFaltante)){
    $pn = $row['pn'];
   $revisionPO= mysqli_query($con, "SELECT * FROM po WHERE pn = '$pn' ORDER BY id DESC LIMIT 1");
   $rowRev = mysqli_fetch_array($revisionPO);
   $rev = str_replace("PPAP ", "", $rowRev['rev']);
   $rev = str_replace("PRIM ", "", $rev);
   $cliente = $rowRev['client'];
   $desc = $rowRev['description'];
   $price = $rowRev['price'];
   $send = $rowRev['send'];
   $desc = str_replace("'", "", $desc);
   $desc = str_replace('"', "", $desc);
   $desc = str_replace(",", ";", $desc);
   $send = str_replace("'", "", $send);
   $send = str_replace('"', "", $send);
   $send = str_replace(",", ";", $send);
   $buscarPrecio = mysqli_query($con, "SELECT rev FROM precios WHERE pn = '$pn'  ORDER BY id DESC LIMIT 1");
    $rowPrecio = mysqli_fetch_array($buscarPrecio);
    if (mysqli_num_rows($buscarPrecio) == 0) {
      mysqli_query($con, "INSERT INTO `precios`( `client`, `pn`, `desc`, `rev`, `price`, `send`) VALUES ( '$cliente', '$pn', '$desc', '$rev', '$price', '$send')");
    echo $pn . " " . $rev . "Insertado<br>";

    }else{
    $revPrecio = "";
    if($rowPrecio['rev'] != null){
        $revPrecio = str_replace("PPAP ", "", $rowPrecio['rev']);
        $revPrecio = str_replace("PRIM ", "", $revPrecio);
    }
    if($rev != $revPrecio){
        mysqli_query($con, "UPDATE `precios` SET `rev`='$rev' WHERE pn = '$pn'");
        echo $pn . " " . $rev . " " . $revPrecio . " Actualizado<br>";
    }
    } 
   
    }


}catch(Exception $e){
    echo "Error: " . $e->getMessage();
}