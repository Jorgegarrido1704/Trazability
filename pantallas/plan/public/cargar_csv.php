<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function responderCarga(int $codigo, array $datos): void
{
    http_response_code($codigo);
    echo json_encode($datos, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
}

try {
    $dependencia = dirname(__DIR__) . '/carga_planeacion.php';
    if (!is_readable($dependencia) || !is_readable(dirname(__DIR__) . '/conexion.php')) {
        responderCarga(500, ['error' => 'Instala la carpeta completa. Faltan archivos PHP fuera de public/.']);
        return;
    }
    require $dependencia;

    $metodo = $_SERVER['REQUEST_METHOD'];
    if ($metodo !== 'GET' && $metodo !== 'POST') {
        header('Allow: GET, POST');
        responderCarga(405, ['error' => 'Método no permitido.']);
        return;
    }

    $config = configuracionPlaneacion();
    if (session_status() !== PHP_SESSION_ACTIVE && !session_start()) {
        responderCarga(500, ['error' => 'PHP no pudo iniciar la sesión. Revisa session.save_path en el servidor.']);
        return;
    }

    $autorizar = $config['autorizar_carga'] ?? null;
    $autorizadoCvts = is_callable($autorizar) && $autorizar() === true;
    $hash = $config['clave_carga_hash'] ?? '';
    if (!$autorizadoCvts && (!is_string($hash) || $hash === '' || password_get_info($hash)['algo'] === null)) {
        session_write_close();
        responderCarga(403, ['error' => 'Activa la carga en config.local.php con el permiso de CVTS o una contraseña de carga.']);
        return;
    }

    $local = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true)
        && in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', '::1', '[::1]'], true);
    $https = in_array(strtolower($_SERVER['HTTPS'] ?? ''), ['on', '1'], true);
    if (!$autorizadoCvts && !$https && !$local) {
        session_write_close();
        responderCarga(403, ['error' => 'Abre la pantalla por HTTPS para usar la contraseña de carga, o habilita el permiso de la sesión de CVTS.']);
        return;
    }

    if ($metodo === 'GET') {
        $_SESSION['cvts_planeacion_csrf'] ??= bin2hex(random_bytes(32));
        $token = $_SESSION['cvts_planeacion_csrf'];
        session_write_close();
        responderCarga(200, ['token' => $token, 'requiere_clave' => !$autorizadoCvts]);
        return;
    }

    $token = $_SESSION['cvts_planeacion_csrf'] ?? '';
    session_write_close();
    if ($token === '' || !hash_equals($token, $_SERVER['HTTP_X_CVTS_CSRF'] ?? '')) {
        responderCarga(403, ['error' => 'Actualiza la página antes de cargar el CSV.']);
        return;
    }

    if (!$autorizadoCvts) {
        $clave = $_POST['clave_carga'] ?? '';
        if (!is_string($clave) || strlen($clave) > 72 || !password_verify($clave, $hash)) {
            responderCarga(403, ['error' => 'Contraseña de carga incorrecta.']);
            return;
        }
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
} catch (ConfiguracionPlaneacionException $error) {
    responderCarga(500, ['error' => $error->getMessage()]);
} catch (mysqli_sql_exception $error) {
    error_log('Planeación CVTS, conexión: ' . $error->getMessage());
    responderCarga(500, ['error' => 'Revisa la conexión de app/conection.php y el registro de errores de PHP.']);
} catch (InvalidArgumentException $error) {
    responderCarga(422, ['error' => $error->getMessage()]);
} catch (CargaPlaneacionException $error) {
    error_log('Planeación CVTS, carga: ' . $error);
    responderCarga(409, ['error' => $error->getMessage()]);
} catch (Throwable $error) {
    error_log('Planeación CVTS, carga: ' . $error);
    responderCarga(500, ['error' => 'No se pudo importar la planeación. Revisa el registro de errores del servidor.']);
}
