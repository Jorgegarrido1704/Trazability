<?php

include('./app.php');
$hora = $_GET['hourTime'];
$dateRegistered = $_GET['dateRegistered'];
$toolingCrimperName = $_GET['toolingCrimperName'];
$downtime = $_GET['downtime'];
$reason = $_GET['reason'];
$reason = strtoupper($reason);
$reasion = str_replace(",", ";", $reason);




$qry= "INSERT INTO `calidad_corte_oee`(`maquina`, `motivo`, `qty_errores`, `fecha`, `hora`) VALUES 
('$toolingCrimperName', '$reason', '$downtime', '$dateRegistered', '$hora')";

$send = $conn->query($qry);
$conn->close();

    echo json_encode(array('status' => 'success', 'message' => 'Data received successfully'));



?>