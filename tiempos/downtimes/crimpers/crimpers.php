<?php

include('./app.php');
$hora = $_GET['hourTime'];
$startHour = explode('-', $hora)[0];
$endHour = explode('-', $hora)[1];
$dateRegistered = $_GET['dateRegistered'];
$toolingCrimperName = $_GET['toolingCrimperName'];
$terminalsUsed = $_GET['Qty'];
$downtime = $_GET['downtime'];
$reason = $_GET['reason'];
$observation = $_GET['observation'];

$datos = array(
    'dateRegistered' => $dateRegistered,
    'startHour' => $startHour,
    'endHour' => $endHour,
    'toolingCrimperName' => $toolingCrimperName,
    'TerminalsUsed' => $terminalsUsed,
    'minutesStop' => $downtime,
    'reasonStop' => $reason,
    'observations' => $observation
);

$qry= "INSERT INTO crimpers_tools (`dateRegistered`, `startHour`, `endHour`, `toolingCrimperName`, `TerminalsUsed`, `minutesStop`, `reasonStop`, `observations`) VALUES ('$dateRegistered', '$startHour', '$endHour', '$toolingCrimperName', '$terminalsUsed', '$downtime', '$reason', '$observation')";

$send = $conn->query($qry);
$conn->close();
if ($datos){
    echo json_encode(array('status' => 'success', 'message' => 'Data received successfully', 'data' => $datos));
}else {
    echo json_encode(array('status' => 'error', 'message' => 'No data received'));  
}


?>