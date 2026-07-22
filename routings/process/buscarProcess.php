<?php
require "../app/conection.php";

$numero = $_GET['pn'];
$datos=[];
    
$registroDatos = mysqli_query($con,"SELECT * FROM routing_models
JOIN routing_process ON routing_models.id_process = routing_process.id 
WHERE pn_routing = '$numero' ");

if(mysqli_num_rows($registroDatos)>0){
    $datos = mysqli_fetch_all($registroDatos, MYSQLI_ASSOC);
    echo json_encode(["status" => "success", "message" => "Data found", "data"=> $datos]);
}else{
    echo json_encode(["error" => "No data found"]);
}



?>