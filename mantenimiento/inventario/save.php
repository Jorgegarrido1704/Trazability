<?php 
session_start();
if(!$_SESSION['user']){
    header("location:index.html");
}
require "conection.php";

$herramienta=isset($_POST['herramienta'])?$_POST['herramienta']:"";
$marca=isset($_POST['marca'])? $_POST['marca']:"";
$modelo=isset($_POST['modelo'])? $_POST['modelo']:"";
$qyherra=isset($_POST['qty'])? $_POST['qty']:"";
 date_default_timezone_set('America/Mexico_City');
 $fecha=date(" Y/m/d H:i");
if($herramienta and $qyherra){
    $quien=$_SESSION['user'];
    $categoria="Herramientas";
    $inserherra="INSERT INTO `inventario`(`id`, `categoria`, `articulo`, `marca`, `modelo`, `qty`, `quien`, `date`) VALUES ('','$categoria','$herramienta','$marca','$modelo','$qyherra','$quien','$fecha')";
    $qry=mysqli_query($con,$inserherra);

header("location:herramientas.php");    
}

$solvente=isset($_POST['solvente'])? $_POST['solvente']:"";
$idsolvente=isset($_POST['id'])? $_POST['id']:"";
$cantidadsol=isset($_POST['cantidadsol'])? $_POST['cantidadsol']:"";
if($solvente and $idsolvente and $cantidadsol){
    $quien=$_SESSION['user'];
    $categoria="solvente";
    $solv="INSERT INTO `inventario`(`id`, `categoria`, `articulo`, `marca`, `modelo`, `qty`, `quien`, `date`) VALUES ('','$categoria','$solvente','','$idsolvente','$cantidadsol','$quien','$fecha')";
    $qrysol=mysqli_query($con,$solv);

    header("location:solventes.php");   
}

$refacciones=isset($_POST['refacciones'])?$_POST['refacciones']:"";
$modeloref=isset($_POST['modeloref'])? $_POST['modeloref']:"";
$maquina=isset($_POST['maquina'])?$_POST['maquina']:"";
$cantidadref=isset($_POST['cantidadref'])?$_POST['cantidadref']:"";
if($refacciones){
    $quien=$_SESSION['user'];
    $categoria="refacciones";
    $ref="INSERT INTO `inventario`(`id`, `categoria`, `articulo`, `marca`, `modelo`, `qty`, `quien`, `date`) VALUES ('','$categoria','$refacciones','$maquina','$modeloref','$cantidadref','$quien','$fecha')";
    $qryref=mysqli_query($con,$ref);
header("location:refacciones.php");
}


?>