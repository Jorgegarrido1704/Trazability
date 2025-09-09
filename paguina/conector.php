<?php
$host="127.0.0.1";
$user="pcadmin";
$pass="SupAdmin1212";
$db="trazabilidad";

$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}