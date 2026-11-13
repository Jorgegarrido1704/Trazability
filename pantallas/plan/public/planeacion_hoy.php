<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {
    $dependencia = dirname(__DIR__) . '/conexion.php';
    if (!is_readable($dependencia)) {
        http_response_code(500);
        echo '{"error":"Falta conexion.php en la carpeta superior a public/."}';
        return;
    }
    require $dependencia;
    $zona = zonaPlanta();
    $ahora = new DateTimeImmutable('now', $zona);
    $hoy = $ahora->format('Y-m-d');
    $manana = $ahora->modify('+1 day')->format('Y-m-d');

    $consulta = conectarPlaneacion()->prepare(
        'SELECT customer, np, wo, qty_wo, corte, ensa, loom, cal, emb, DATE(fecha) AS fecha, comentarios
         FROM pantallas
         WHERE pantalla = ? AND fecha_up >= ? AND fecha_up < ?
         ORDER BY id'
    );
    $consulta->execute(['plan', $hoy, $manana]);
    $registros = $consulta->get_result()->fetch_all(MYSQLI_ASSOC);
    $consulta->close();

    echo json_encode([
        'fecha' => $hoy,
        'zona_horaria' => $zona->getName(),
        'consultado_en' => $ahora->format(DATE_ATOM),
        'registros' => $registros,
    ], JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
} catch (ConfiguracionPlaneacionException $error) {
    http_response_code(500);
    echo json_encode(['error' => $error->getMessage()], JSON_INVALID_UTF8_SUBSTITUTE);
} catch (mysqli_sql_exception $error) {
    error_log('Planeación CVTS, consulta: ' . $error->getMessage());
    $mensaje = match ($error->getCode()) {
        1054 => 'Falta una columna en pantallas. Si falta customer, ejecuta agregar_customer.sql.',
        1146 => 'No se encontró pantallas. Revisa la base seleccionada por app/conection.php.',
        default => 'Revisa la conexión de app/conection.php y el registro de errores de PHP.',
    };
    http_response_code(500);
    echo json_encode(['error' => $mensaje], JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Throwable $error) {
    error_log('Planeación CVTS: ' . $error->getMessage());
    http_response_code(500);
    echo '{"error":"No se pudo consultar la planeación."}';
}
