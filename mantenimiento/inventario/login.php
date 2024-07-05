<?php
session_start();
require "conection.php";
$usuario=isset($_POST['user']) ? $_POST['user']:"";
$key=isset($_POST['pass'])? $_POST['pass']:"";
 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['user'];
    $key = $_POST['pass'];
$_SESSION['user']=$usuario;
    // To prevent SQL injection
    $usuario = mysqli_real_escape_string($con, $usuario);
    $key = mysqli_real_escape_string($con, $key);

    $sql = "SELECT * FROM users WHERE user = '$usuario' AND password = '$key'";
    $result = mysqli_query($con, $sql);
    $count = mysqli_num_rows($result);
    
    if ($count == 1) {
        $log = "login";
        $_SESSION['user'] = $usuario; // Store the username in the session

        date_default_timezone_set('America/Mexico_City');
        $fecha = date('d-m-Y H:i');
        $registro = "INSERT INTO `registros`(`id`, `fecha`, `userName`, `action`) VALUES ('', '$fecha', '$usuario', '$log')";

        mysqli_query($con, $registro); // Insert the login record into the 'registros' table

        header("location: principal.php");
    } else {
        header("location: index.html");
    } }
mysqli_close($con);
?>