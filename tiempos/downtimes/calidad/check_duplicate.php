<?php
include('./app.php');

$json = file_get_contents('php://input');

$requestData = json_decode($json, true);

$datos = isset($requestData['data']) ? $requestData['data'] : null;

$dateRegistered = $datos['dateRegistered'];
$hourTime = $datos['hourTime'];
$toolingCrimperName = $datos['toolingCrimperName'];


$busqueda = "SELECT `fecha`,`hora`,`maquina` FROM `calidad_corte_oee` WHERE fecha = '$dateRegistered' AND hora = '$hourTime' AND maquina = '$toolingCrimperName' ";
$result = $conn->query($busqueda);

if ($result->num_rows > 0) {

    echo json_encode(array('status' => 'duplicate', 'message' => 'Duplicate entry found'));
} else {
    echo json_encode(array('status' => 'unique', 'message' => 'No duplicate entry found'));
}