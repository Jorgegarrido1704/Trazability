<?php 

require "../app/conection.php";

//conteo de cable

$conteo_cable=[];
$pn=isset($_GET['pn'])?$_GET['pn']:"621705";
$buscawo=mysqli_query($con,"SELECT * FROM listascorte  WHERE pn='$pn'");
while($rowo=mysqli_fetch_array($buscawo)){
    $tipo=$rowo['tipo'];
    
}