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

    $consulta = conectar()->prepare(
        'SELECT customer, np, wo, qty_wo, corte, ensa, loom, cal, emb, fecha, comentarios
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
} catch (ConfiguracionPlaneacionException $error) {
    http_response_code(500);
    echo json_encode(['error' => $error->getMessage()], JSON_INVALID_UTF8_SUBSTITUTE);
} catch (PDOException $error) {
    error_log('Planeación CVTS, consulta: ' . $error->getMessage());
    $mensaje = match ((string) $error->getCode()) {
        '42S22' => 'Falta una columna en pantallas. Si falta customer, ejecuta agregar_customer.sql.',
        '42S02' => 'No se encontró pantallas. Revisa la base seleccionada en config.local.php.',
        default => 'Revisa la conexión a MySQL/MariaDB en config.local.php y el registro de errores de PHP.',
    };
    http_response_code(500);
    echo json_encode(['error' => $mensaje], JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Throwable $error) {
    error_log('Planeación CVTS: ' . $error->getMessage());
    http_response_code(500);
    echo '{"error":"No se pudo consultar la planeación."}';
}
