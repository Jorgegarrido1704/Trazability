<?php
// api/get-colors.php
header('Content-Type: application/json');
require 'conection.php';

$awg = isset($_GET['awg']) ? $_GET['awg'] : null;

try {
    // Security: Escape the input to prevent SQL Injection
    $awg_escaped = mysqli_real_escape_string($con, $awg);

    $stmt = mysqli_query($con, "SELECT DISTINCT color FROM listascorte WHERE aws = '$awg_escaped' ORDER BY color ASC");
    
    // Check if query actually succeeded
    if (!$stmt) {
        throw new Exception(mysqli_error($con));
    }

    $colors = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
    $colors = array_column($colors, 'color');

    // If no colors found, array_column returns an empty array [], which is safe for .forEach()
    echo json_encode($colors);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}