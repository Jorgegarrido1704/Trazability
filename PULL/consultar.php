<?php 

require "../app/conection.php";

$work_order = $_GET['wo'] ?? '';

if($work_order) {
    // Fetch information from the database using the work order
    $query = "SELECT NumPart,cliente FROM registro WHERE wo = '$work_order' limit 1";
    $result = mysqli_query($con, $query);

    if($result) {
        $row = mysqli_fetch_assoc($result);
        $data = [
             $row['NumPart'], $row['cliente']
        ];
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "No data found"]);
    }
} else {
    echo json_encode(["error" => "Invalid work order"]);
}