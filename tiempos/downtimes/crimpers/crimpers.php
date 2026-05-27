<?php

include('./app.php');
$hora = $_POST['hourTime'];
$startHour = explode('-', $hora)[0];
$endHour = explode('-', $hora)[1];


$datos = array(
    'dateRegistered' => $_POST['dateRegistered'],
    'startHour' => $startHour,
    'endHour' => $endHour,
    'toolingCrimperName' => $_POST['toolingCrimperName'],
    'TerminalsUsed' => $_POST['Qty'],
    'minutesStop' => $_POST['downtime'],
    'reasonStop' => $_POST['reason'],
    'observations' => $_POST['observation']
);

$qry= "INSERT INTO crimpers_tools (`dateRegistered`, `startHour`, `endHour`, `toolingCrimperName`, `TerminalsUsed`, `minutesStop`, `reasonStop`, `observations`) VALUES ('" . $datos['dateRegistered'] . "', '" . $datos['startHour'] . "', '" . $datos['endHour'] . "', '" . $datos['toolingCrimperName'] . "', '" . $datos['TerminalsUsed'] . "', '" . $datos['minutesStop'] . "', '" . $datos['reasonStop'] . "', '" . $datos['observations'] . "')";

$send = $conn->query($qry);
$conn->close();
if ($datos){
    echo json_encode(array('status' => 'success', 'message' => 'Data received successfully', 'data' => $datos));
}else {
    echo json_encode(array('status' => 'error', 'message' => 'No data received'));  
}


?>