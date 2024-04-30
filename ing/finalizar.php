<?php 
session_start();
if (!$_SESSION['usuario']){
    header('location:../main');
}
require '../app/conection.php';
date_default_timezone_set('America/Mexico_City');
$date=date('d-m-Y H:i');

$id=isset($_POST['id'])?$_POST['id']:"";
echo $id;

$updateFin="UPDATE reqing SET timeFin='$date', count='2' WHERE id='$id'";
$qry=mysqli_query($con, $updateFin);
 
if($qry){
    header("location:tableengi.php");
}else{
    echo "Error en coneccion";
}


?>