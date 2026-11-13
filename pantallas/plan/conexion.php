<?php
declare(strict_types=1);

function zonaPlanta(): DateTimeZone
{
    return new DateTimeZone('America/Mexico_City');
}

function conectar(): PDO
{
    $config = require __DIR__ . '/config.local.php';
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
