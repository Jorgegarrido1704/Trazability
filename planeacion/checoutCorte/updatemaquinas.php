<?php
$rutas = [__DIR__ . '/app/conection.php', __DIR__ . '/../app/conection.php'];
foreach ($rutas as $r) {
    if (file_exists($r)) { require $r; break; }
}
if (!isset($con)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(["error" => "No se encontró conection.php", "probado" => $rutas]);
    exit;
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$base = "cutStatus != 'Cortado'";
$sinSello = "term1 NOT LIKE '%Sello%' AND term2 NOT LIKE '%Sello%'";

$reglas = [
    'MCUT-1' => "aws IN (10,12) AND $base AND cons NOT LIKE 'C%' AND tamano > 0 AND $sinSello",
    'MCUT-2' => "aws IN (14,16) AND $base AND cons NOT LIKE 'C%' AND $sinSello AND tintaColor='BLANCA' AND tamano > 0",
    'MCUT-3' => "aws NOT IN (1,2,4,6,8) AND $base AND cons NOT LIKE 'C%' AND tamano > 0 AND (term1 LIKE '%Sello%' OR term2 LIKE '%Sello%')",
    'MCUT-4' => "aws IN (18,20,22,24) AND $base AND cons NOT LIKE 'C%' AND $sinSello AND tintaColor='BLANCA' AND tamano > 0",
    'MCUT-5' => "aws IN (14,16) AND $base AND cons NOT LIKE 'C%' AND $sinSello AND tintaColor='NEGRA' AND tamano > 0",
    'MCUT-6' => "aws IN (18,20,22,24) AND $base AND cons NOT LIKE 'C%' AND $sinSello AND tintaColor='NEGRA' AND tamano > 0",
    'MCUT-7' => "$base AND (cons LIKE 'C%' OR aws IN (1,2,4,6,8))",
];

try {
    // Solo máquinas válidas (lista blanca)
    $seleccionadas = isset($_GET['maquinas']) && is_array($_GET['maquinas'])
        ? array_values(array_intersect($_GET['maquinas'], array_keys($reglas)))
        : [];

    if (empty($seleccionadas)) {
        http_response_code(400);
        echo json_encode(["error" => "No se recibieron máquinas válidas."]);
        exit;
    }

    mysqli_query($con, "UPDATE corte SET maq_asignada = NULL WHERE $base AND tamano > 0");

    foreach ($seleccionadas as $maq) {
        mysqli_query($con, "UPDATE corte SET maq_asignada = '$maq' WHERE " . $reglas[$maq]);
    }

    echo json_encode(["ok" => true, "maquinas" => $seleccionadas]);

} catch (Throwable $e) {
    error_log("Error repartiendo carga: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Error interno", "detalle" => $e->getMessage()]);
}