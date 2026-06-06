<?php
// api/get-gauges.php
header('Content-Type: application/json');
require 'conection.php';

try {
    $stmt = mysqli_query($con, "SELECT DISTINCT aws FROM listascorte WHERE aws IS NOT NULL AND aws != '' ORDER BY CAST(aws AS UNSIGNED) ASC, aws ASC");
    $gauges = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
    $gauges = array_column($gauges, 'aws');

    echo json_encode($gauges);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}