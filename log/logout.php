<?php
session_start();
require "../app/conection.php";
if (isset($_SESSION['usuario'])) {
    $username = $_SESSION['usuario'];
    $log = "logout"; 
    date_default_timezone_set('America/Mexico_City');
    $fecha = date('d-m-Y H:i');
    $registro = mysqli_query($con,"INSERT INTO `registros`( `fecha`, `userName`, `action`) VALUES ( '$fecha', '$username', '$log')");
       session_destroy(); 
    header("location: ../main/index.html");
} else {
    header("location: ../main/index.html"); 
}

mysqli_close($con);
?>

