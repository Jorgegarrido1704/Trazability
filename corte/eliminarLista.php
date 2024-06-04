<?php 
require "../app/conection.php";
$lista=isset($_GET['elim'])?$_GET['elim']:"";
$lista=strtoupper($lista);
if($lista!=""){
$delte=mysqli_query($con,"DELETE FROM listascorte WHERE pn='$lista'");
header("location:busqueda.php");
}
