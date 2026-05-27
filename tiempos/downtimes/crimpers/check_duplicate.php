<?php
include('./app.php');

$json = file_get_contents('php://input');

$requestData = json_decode($json, true);

$datos = isset($requestData['data']) ? $requestData['data'] : null;

$dateRegistered = $datos['dateRegistered'];
$hourTime = $datos['hourTime'];
$startHour = explode('-', $hourTime)[0];
$endHour = explode('-', $hourTime)[1];
$toolingCrimperName = $datos['toolingCrimperName'];

$busqueda = "SELECT * FROM crimpers_tools WHERE dateRegistered = '$dateRegistered' AND startHour = '$startHour' AND endHour = '$endHour' AND toolingCrimperName = '$toolingCrimperName'";

$result = $conn->query($busqueda);

if ($result->num_rows > 0) {

    echo json_encode(array('status' => 'duplicate', 'message' => 'Duplicate entry found'));
} else {
    echo json_encode(array('status' => 'unique', 'message' => 'No duplicate entry found'));
}