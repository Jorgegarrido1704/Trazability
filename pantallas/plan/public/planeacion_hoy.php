<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    require __DIR__ . '/../../app/conection.php';

    $zona = zonaPlanta();
    $ahora = new DateTimeImmutable('now', $zona);
    $hoy = $ahora->setTime(0, 0);
    $manana = $hoy->modify('+1 day');

    $inicio = $hoy->format('Y-m-d H:i:s');
    $fin = $manana->format('Y-m-d H:i:s');
    $pantalla = 'plan';

    $consulta = $con->prepare(
        'SELECT customer, np, wo, qty_wo, corte, ensa, loom, cal, emb,
                DATE(fecha) AS fecha, comentarios
         FROM pantallas
         WHERE pantalla = ? AND fecha_up >= ? AND fecha_up < ?
         ORDER BY id'
    );

    $consulta->bind_param('sss', $pantalla, $inicio, $fin);
    $consulta->execute();

    $registros = $consulta->get_result()->fetch_all(MYSQLI_ASSOC);
    $consulta->close();

    echo json_encode([
        'fecha' => $hoy->format('Y-m-d'),
        'zona_horaria' => $zona->getName(),
        'consultado_en' => $ahora->format(DATE_ATOM),
        'registros' => $registros,
    ], JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
} catch (mysqli_sql_exception $error) {
    error_log('Planeación CVTS, consulta: ' . $error->getMessage());

    $mensaje = match ($error->getCode()) {
        1054 => 'Falta una columna en pantallas. Si falta customer, ejecuta agregar_customer.sql.',
        1146 => 'No se encontró la tabla pantallas en la base de datos seleccionada.',
        default => 'No se pudo consultar la base de datos de planeación.',
    };

    http_response_code(500);
    echo json_encode(['error' => $mensaje], JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Throwable $error) {
    error_log('Planeación CVTS: ' . $error->getMessage());

    http_response_code(500);
    echo '{"error":"No se pudo consultar la planeación."}';
}