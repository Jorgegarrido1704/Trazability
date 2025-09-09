<?php
$host="127.0.0.1";
$user="root";
$pass="";
$db="trazabilidad";

$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
date_default_timezone_set("america/Mexico_City");