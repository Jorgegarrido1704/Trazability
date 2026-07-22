<?php 

require "../app/conection.php";

$routingDescription = $_GET['routingDescription'];
$routings = mysqli_query($con,"SELECT posibleAssets,routingNumber FROM routing_process WHERE routingDescription = '$routingDescription' ");

if(mysqli_num_rows($routings)>0){
    $datos = mysqli_fetch_all($routings, MYSQLI_ASSOC);
    echo json_encode($datos);
}else{
    echo json_encode(["error" => "No data found"]);
}