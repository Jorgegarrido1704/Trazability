<?php
session_start();
require "../app/conection.php";
date_default_timezone_set('America/Mexico_City');
$fecha = date('d-m-Y H:i');
$cliente = isset($_POST['cliente']) ? $_POST['cliente'] : "";
$num = isset($_POST['np']) ? $_POST['np'] : "";
$wo =isset($_POST['wo'])? $_POST['wo']:"";
$id=isset($_POST['id'])? $_POST['id']:"";
$qty = isset($_POST['qty'])?$_POST['qty']:"";
$date=isset($_POST['fecha'])?$_POST['fecha']:"";
$donde= isset($_POST['donde'])?$_POST['donde']:"";
$status=isset($_POST['status'])?$_POST['status']:"";
echo $id;

for($i=0;$i<=$id;$i++){
    $insertar="INSERT INTO `auditoria`(`id`, `fecha`, `np`, `client`, `wo`, `qty`, `donde`, `status`, `daterequest`) VALUES  ('','$fecha','$num[$i]','$cliente[$i]','$wo[$i]','$qty[$i]','$donde[$i]','$status[$i]','$date[$i]')";
    $qry=mysqli_query($con,$insertar);
}
header("location:auditoria.php");

?>