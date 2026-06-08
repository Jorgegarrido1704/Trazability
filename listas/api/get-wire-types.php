<?php
// api/get-colors.php
header('Content-Type: application/json');
require 'conection.php';

$awg = isset($_GET['awg']) ? $_GET['awg'] : null;
$color = isset($_GET['color']) ? $_GET['color'] : null;

try {
    // Security: Escape the input to prevent SQL Injection
    $awg_escaped = mysqli_real_escape_string($con, $awg);
    $color_escaped = mysqli_real_escape_string($con, $color);

    $stmt = mysqli_query($con, "SELECT DISTINCT tipo FROM listascorte WHERE aws = '$awg_escaped' AND color = '$color_escaped' ORDER BY tipo ASC");
    
    // Check if query actually succeeded
    if (!$stmt) {
        throw new Exception(mysqli_error($con));
    }

    $types = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
    $types = array_column($types, 'tipo');

    // If no types found, array_column returns an empty array [], which is safe for .forEach()
    echo json_encode($types);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}