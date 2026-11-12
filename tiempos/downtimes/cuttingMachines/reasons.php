<?php

$localhost = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'trazabilidad';

// Create connection
$conn = new mysqli($localhost, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set("America/Mexico_City");


$busqueda = "SELECT `defecto`,`clave` FROM `clavecali`  ";
$result = $conn->query($busqueda);

echo json_encode($result->fetch_all(MYSQLI_ASSOC));