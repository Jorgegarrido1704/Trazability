<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require __DIR__ . '/carga_planeacion.php';

if ($argc !== 2) {
    fwrite(STDERR, "Uso: php importar_planeacion.php archivo.csv\n");
    exit(1);
}

try {
    $resultado = cargarPlaneacion($argv[1]);
    fwrite(STDOUT, sprintf(
        "Registros anteriores eliminados: %d. Registros nuevos: %d. Fecha de carga: %s.\n",
        $resultado['reemplazados'],
        $resultado['guardados'],
        $resultado['fecha_carga']
    ));
} catch (Throwable $error) {
    fwrite(STDERR, $error->getMessage() . PHP_EOL);
    if ($error instanceof CargaPlaneacionException && $error->getPrevious()) {
        fwrite(STDERR, $error->getPrevious()->getMessage() . PHP_EOL);
    }
    exit(1);
}
