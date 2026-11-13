<?php
declare(strict_types=1);

require dirname(__DIR__) . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {
    $zona = zonaPlanta();
    $ahora = new DateTimeImmutable('now', $zona);
    $hoy = $ahora->format('Y-m-d');
    $manana = $ahora->modify('+1 day')->format('Y-m-d');

    $consulta = conectar()->prepare(
        'SELECT np, wo, qty_wo, corte, ensa, loom, cal, emb, fecha, comentarios
         FROM pantallas
         WHERE pantalla = :pantalla AND fecha_up >= :hoy AND fecha_up < :manana
         ORDER BY id'
    );
    $consulta->execute(['pantalla' => 'plan', 'hoy' => $hoy, 'manana' => $manana]);

    echo json_encode([
        'fecha' => $hoy,
        'zona_horaria' => $zona->getName(),
        'consultado_en' => $ahora->format(DATE_ATOM),
        'registros' => $consulta->fetchAll(),
    ], JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Throwable $error) {
    error_log('Planeación CVTS: ' . $error->getMessage());
    http_response_code(500);
    echo '{"error":"No se pudo consultar la planeación."}';
}
