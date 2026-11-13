<?php
declare(strict_types=1);

require dirname(__DIR__) . '/carga_planeacion.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function responderCarga(int $codigo, array $datos): void
{
    http_response_code($codigo);
    echo json_encode($datos, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
}

try {
    $metodo = $_SERVER['REQUEST_METHOD'];
    if ($metodo !== 'GET' && $metodo !== 'POST') {
        header('Allow: GET, POST');
        responderCarga(405, ['error' => 'Método no permitido.']);
        return;
    }

    session_start();
    $config = require dirname(__DIR__) . '/config.local.php';
    $autorizar = $config['autorizar_carga'] ?? null;
    if (!is_callable($autorizar) || $autorizar() !== true) {
        session_write_close();
        responderCarga(403, ['error' => 'La carga de CSV requiere acceso de Planeación en CVTS.']);
        return;
    }

    if ($metodo === 'GET') {
        $_SESSION['cvts_planeacion_csrf'] ??= bin2hex(random_bytes(32));
        $token = $_SESSION['cvts_planeacion_csrf'];
        session_write_close();
        responderCarga(200, ['token' => $token]);
        return;
    }

    $token = $_SESSION['cvts_planeacion_csrf'] ?? '';
    session_write_close();
    if ($token === '' || !hash_equals($token, $_SERVER['HTTP_X_CVTS_CSRF'] ?? '')) {
        responderCarga(403, ['error' => 'Actualiza la página antes de cargar el CSV.']);
        return;
    }

    $archivo = $_FILES['csv'] ?? [];
    if (!is_string($archivo['tmp_name'] ?? null) || ($archivo['error'] ?? null) !== UPLOAD_ERR_OK
        || !is_uploaded_file($archivo['tmp_name'])) {
        responderCarga(400, ['error' => 'No se recibió el CSV. Revisa el archivo y el límite de carga de PHP.']);
        return;
    }
    if (strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION)) !== 'csv') {
        responderCarga(400, ['error' => 'Selecciona un archivo con extensión .csv.']);
        return;
    }

    responderCarga(200, cargarPlaneacion($archivo['tmp_name']));
} catch (InvalidArgumentException $error) {
    responderCarga(422, ['error' => $error->getMessage()]);
} catch (CargaPlaneacionException $error) {
    error_log('Planeación CVTS, carga: ' . $error);
    responderCarga(409, ['error' => $error->getMessage()]);
} catch (Throwable $error) {
    error_log('Planeación CVTS, carga: ' . $error);
    responderCarga(500, ['error' => 'No se pudo importar la planeación. Revisa el registro de errores del servidor.']);
}
