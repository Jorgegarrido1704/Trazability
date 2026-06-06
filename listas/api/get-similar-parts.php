<?php
header('Content-Type: application/json');
require '../../app/conection';

$query = isset($_GET['query']) ? mysqli_real_escape_string($con, trim($_GET['query'])) : '';

$response = [
    "suggestions" => [],
    "similares" => []
];

if (!empty($query)) {
    try {
        // 1. Sugerencias del dropdown (Coincidencias iniciales)
        $stmt1 = mysqli_query($con, "SELECT DISTINCT pn FROM listascorte WHERE pn LIKE '$query%' LIMIT 5");
        $rawSug = mysqli_fetch_all($stmt1, MYSQLI_ASSOC);
        $response['suggestions'] = array_column($rawSug, 'pn');

        // 2. Similares (Coincidencia amplia pero que no sea idéntica)
        $stmt2 = mysqli_query($con, "SELECT DISTINCT pn FROM listascorte WHERE pn LIKE '%$query%' AND pn != '$query' LIMIT 4");
        $rawSim = mysqli_fetch_all($stmt2, MYSQLI_ASSOC);
        
        foreach ($rawSim as $row) {
            $response['similares'][] = [
                "num_parte" => $row['pn'],
                "coincidencia" => rand(80, 98) 
            ];
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
        exit;
    }
}

echo json_encode($response);