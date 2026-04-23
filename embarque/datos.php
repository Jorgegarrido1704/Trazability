<?php
require "../app/conection.php";

$np=$_GET['np'];

$buscar = mysqli_query($con, "SELECT `NumPart`,`cliente`,`rev` FROM `registro` WHERE `NumPart`='$np' order by `id` DESC limit 1");

echo json_encode(mysqli_fetch_all($buscar));
?>