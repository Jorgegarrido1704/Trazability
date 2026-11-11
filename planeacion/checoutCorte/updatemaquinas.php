<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Convierte cualquier error fatal en JSON legible
register_shutdown_function(function () {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(500);
        echo json_encode(["error" => "Error fatal PHP", "detalle" => $e['message'], "archivo" => $e['file'], "linea" => $e['line']]);
    }
});

$rutas = [__DIR__ . '/app/conection.php', __DIR__ . '/../app/conection.php'];
foreach ($rutas as $r) {
    if (file_exists($r)) { require $r; break; }
}
if (!isset($con)) {
    http_response_code(500);
    echo json_encode(["error" => "No se encontró conection.php o no define \$con", "probado" => $rutas]);
    exit;
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$base     = "cutStatus != 'Cortado'";
$sinSello = "term1 NOT LIKE '%Sello%' AND term2 NOT LIKE '%Sello%'";

// Calibres que van a Cables Especiales (MCUT-7)
$especiales = "'1','2','4','6','8','1/0','2/0','3/0','4/0'";

$reglas = [
    'MCUT-1' => "aws IN ('10','12') AND $base AND cons NOT LIKE 'C%' AND tamano > 0 AND $sinSello",
    'MCUT-2' => "aws IN ('14','16') AND $base AND cons NOT LIKE 'C%' AND $sinSello AND tintaColor='BLANCA' AND tamano > 0",
    'MCUT-3' => "aws NOT IN ($especiales) AND $base AND cons NOT LIKE 'C%' AND tamano > 0 AND (term1 LIKE '%Sello%' OR term2 LIKE '%Sello%')",
    'MCUT-4' => "aws IN ('18','20','22','24') AND $base AND cons NOT LIKE 'C%' AND $sinSello AND tintaColor='BLANCA' AND tamano > 0",
    'MCUT-5' => "aws IN ('14','16') AND $base AND cons NOT LIKE 'C%' AND $sinSello AND tintaColor='NEGRA' AND tamano > 0",
    'MCUT-6' => "aws IN ('18','20','22','24') AND $base AND cons NOT LIKE 'C%' AND $sinSello AND tintaColor='NEGRA' AND tamano > 0",
    'MCUT-7' => "$base AND (cons LIKE 'C%' OR aws IN ($especiales))",
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