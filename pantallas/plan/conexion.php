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
        $ruta = __DIR__ . '/config.ejemplo.php';
        if (!is_readable($ruta)) {
            throw new ConfiguracionPlaneacionException('Falta config.local.php en la carpeta que contiene conexion.php. Copia config.ejemplo.php y configura CVTS.');
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

function conectar(): PDO
{
    $config = configuracionPlaneacion();
    if (!class_exists(PDO::class) || !in_array('mysql', PDO::getAvailableDrivers(), true)) {
        throw new ConfiguracionPlaneacionException('Habilita pdo_mysql en el PHP que usa el servidor web.');
    }
    if (!isset($config['dsn'], $config['usuario'], $config['clave'])) {
        throw new ConfiguracionPlaneacionException('Completa dsn, usuario y clave en config.local.php.');
    }
    return new PDO(
        $config['dsn'],
        $config['usuario'],
        $config['clave'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
}
