<?php
require 'conector.php';

header('Content-Type: application/json');

$wo = $_POST['wo'] ?? '';

if (empty($wo)) {
    echo json_encode(['success' => false, 'message' => 'WO is missing']);
    exit;
}

$stmt = $con->prepare("SELECT info, Qty FROM registro WHERE wo = ?");
$stmt->bind_param("s", $wo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $info = $row['info'];
    $qty = $row['Qty'];

    $stmt2 = $con->prepare("SELECT totalWo FROM consterm WHERE codigo = ? ORDER BY id DESC LIMIT 1");
    $stmt2->bind_param("s", $info);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows > 0) {
        $row2 = $result2->fetch_assoc();
        $total = $row2['totalWo'];
    } else {
        $total = $qty;
    }

    echo json_encode(['success' => true, 'max' => $total]);
} else {
    echo json_encode(['success' => false, 'message' => 'No WO found']);
}
