<?php
session_start();
require "app.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $username = $_SESSION['usuario'];

    $log = "logout_pull"; // Assuming this is a logout action
    date_default_timezone_set('America/Mexico_City');
    $fecha = date('d-m-Y H:i');
    $registro = "INSERT INTO `registros`(`id`, `fecha`, `userName`, `action`) VALUES ('', '$fecha', '$username', '$log')";

    mysqli_query($con, $registro); // Insert the logout record into the 'registros' table

    session_destroy(); // Destroy the session

    header("location: index.HTML");
} else {
    header("location: index.HTML"); // Redirect if not a POST request and no session user
}

mysqli_close($con);
?>

