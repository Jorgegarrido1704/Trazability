<?php 
require "../app/conection.php";
$quien=isset($_POST['quien'])? $_POST['quien'] :'';
$quien=strtoupper($quien);
$po=isset($_POST["numPartOrg"]) ? $_POST["numPartOrg"] :"";
$text=isset($_POST["texthidden"]) ? $_POST["texthidden"] :"";
$ps=isset($_POST["numPartSus"]) ? $_POST["numPartSus"] :"";
$modelo=isset($_POST["modelo"]) ? $_POST["modelo"] :"";
$po=strtoupper($po);
$ps=strtoupper($ps);   
$modelo=strtoupper($modelo);
$text=strtoupper($text);
date_default_timezone_set("America/Mexico_City");
$fecha=date("d-m-Y H:i");
$cant=isset($_POST["cant"]) ? $_POST["cant"] :"";
$time=isset($_POST["time"]) ? $_POST["time"] :"";
$evi=isset($_POST["evihidden"]) ? $_POST["evihidden"] :"";
$evi=strtoupper($evi);
$acc=isset($_POST["acchidden"]) ? $_POST["acchidden"] :"";
$acc=strtoupper($acc);
$cl=isset($_POST["cliente"]) ? $_POST["cliente"] :"";
$cl=strtoupper($cl);
$insert="INSERT INTO `desvation`(`id`, `fecha`,`client`,`quien`, `Mafec`, `porg`, `psus`, `clsus`, `peridoDesv`, `Causa`,`accion`, `fcom`, `fing`, `fcal`, `fpro`, `fimm`, `evidencia`, `count`,`rechazo`) VALUES ('','$fecha','$cl','$quien','$modelo','$po','$ps','$cant','$time','$text','$acc','','','','','','$evi','','')";
$qry= mysqli_query($con,$insert);  
if($qry){
    header("location:index.html");
}