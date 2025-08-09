<?php
session_start();
require "../app/conection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['user'];
    $password = $_POST['pass'];
$i=0;
$users=$claves=[];
    $sql = mysqli_query($con,"SELECT `user`, `clave` FROM `login` WHERE `user` = '$username' AND `clave` = '$password'");
    if(mysqli_num_rows($sql)==1){
        $log = "login";
        $_SESSION['usuario'] = $username; 
        date_default_timezone_set('America/Mexico_City');
        $fecha = date('d-m-Y H:i');
        $registro = mysqli_query($con, "INSERT INTO `registros`(`id`, `fecha`, `userName`, `action`) VALUES ('', '$fecha', '$username', '$log')");
       header("location: ../main/principal.php");
    } else {
        header("location: ../main/index.html");
    }
} 

mysqli_close($con);
?>