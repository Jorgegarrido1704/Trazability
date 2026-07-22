<?php
require "app/conection.php";

$numero = $_GET['pn'];

    
$registroDatos = mysqli_query($con,"SELECT * FROM routing_models WHERE pn_routing = '$numero' ORDER BY work_routing  DESC");

if(mysqli_num_rows($registroDatos)>0){
    $datos = mysqli_fetch_all($registroDatos, MYSQLI_ASSOC);
    echo json_encode($datos);
}else{
    echo json_encode(["error" => "No data found"]);
}



?>