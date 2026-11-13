<?php
declare(strict_types=1);

function zonaPlanta(): DateTimeZone
{
    return new DateTimeZone('America/Mexico_City');
}

final class ConfiguracionPlaneacionException extends RuntimeException {}

function configuracionPlaneacion(): array
{
    static $config;
    if ($config === null) {
        $ruta = __DIR__ . '/config.local.php';
        if (!is_readable($ruta)) {
            throw new ConfiguracionPlaneacionException('Falta config.local.php. Copia config.ejemplo.php y configura el permiso de carga.');
        }
        try {
            $config = require $ruta;
        } catch (ParseError $error) {
            error_log('Planeación CVTS, configuración: ' . $error->getMessage());
            throw new ConfiguracionPlaneacionException('config.local.php contiene un error de sintaxis. Revisa el registro de errores de PHP.', 0, $error);
        }
        if (!is_array($config)) {
            throw new ConfiguracionPlaneacionException('config.local.php debe devolver un arreglo de configuración.');
        }
    }

    return $config;
}

function conectarPlaneacion(): mysqli
{
    static $con;

    if ($con instanceof mysqli) {
        return $con;
    }
    if (!extension_loaded('mysqli')) {
        throw new ConfiguracionPlaneacionException('Habilita mysqli en el PHP del servidor.');
    }

    $ruta = dirname(__DIR__) . '/app/conection.php';
    if (!is_readable($ruta)) {
        throw new ConfiguracionPlaneacionException('No se encontró app/conection.php en la ruta configurada en conexion.php.');
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    require $ruta;
    if (!$con instanceof mysqli) {
        throw new ConfiguracionPlaneacionException('app/conection.php debe proporcionar una conexión mysqli en $con.');
    }
    $con->set_charset('utf8mb4');

    return $con;
}
