<?php
$host = "127.0.0.1";
$user = "root";
$clave = "";
$bd = "routing_times";
$con = mysqli_connect($host, $user, $clave, $bd);
date_default_timezone_set('America/Mexico_City');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}