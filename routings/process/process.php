<?php
require "../app/conection.php";


$routings = mysqli_query($con,"SELECT DISTINCT * FROM routing_process ORDER BY routingNumber DESC");

if(mysqli_num_rows($routings)>0){
    $datos = mysqli_fetch_all($routings, MYSQLI_ASSOC);
    echo json_encode($datos);
}else{
    echo json_encode(["error" => "No data found"]);
}