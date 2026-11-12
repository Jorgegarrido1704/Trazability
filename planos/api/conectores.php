<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    header('Allow: GET');

    echo json_encode([
        'error' => 'Método no permitido'
    ]);

    exit;
}

try {
    mysqli_report(
        MYSQLI_REPORT_ERROR |
        MYSQLI_REPORT_STRICT
    );

    /*
     * CONFIGURACIÓN
     *
     * Puedes editar estos valores o definir las variables
     * de entorno correspondientes en tu servidor.
     */
    $dbHost = getenv('DB_HOST') ?: '127.0.0.1';
    $dbPort = (int) (getenv('DB_PORT') ?: 3306);
    $dbName = getenv('DB_NAME') ?: 'trazabilidad';
    $dbUser = getenv('DB_USER') ?: 'root';

    $dbPassword = getenv('DB_PASSWORD');

    if ($dbPassword === false) {
        $dbPassword = '';
    }

    $db = new mysqli(
        $dbHost,
        $dbUser,
        $dbPassword,
        $dbName,
        $dbPort
    );

    $db->set_charset('utf8mb4');

    /*
     * Consulta consistente y de solo lectura.
     */
    $db->begin_transaction(
        MYSQLI_TRANS_START_READ_ONLY |
        MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT
    );

    $catalogo = [];

    /*
     * HOUSINGS
     */
    $resultado = $db->query(
        'SELECT
            pn,
            descripcion,
            cavidades,
            profundidad_mm
         FROM conectores
         ORDER BY pn'
    );

    foreach ($resultado as $fila) {
        $catalogo[$fila['pn']] = [
            'descripcion' => $fila['descripcion'],
            'cavidades' => (int) $fila['cavidades'],

            'profundidad_mm' => (
                float
            ) $fila['profundidad_mm'],

            'terminales' => new stdClass(),
            'vias' => new stdClass(),
            'componentes' => []
        ];
    }

    /*
     * TERMINALES COMPATIBLES Y MEDIDAS
     */
    $resultado = $db->query(
        'SELECT
            conector_pn,
            terminal_pn,
            strip_mm,
            consumo_mm
         FROM terminales_compatibles
         ORDER BY conector_pn, terminal_pn'
    );

    foreach ($resultado as $fila) {
        $conectorPN = $fila['conector_pn'];
        $terminalPN = $fila['terminal_pn'];

        $catalogo[$conectorPN]['terminales']->{$terminalPN} = [
            'strip_mm' => (float) $fila['strip_mm'],
            'consumo_mm' => (float) $fila['consumo_mm']
        ];
    }

    /*
     * ASIGNACIÓN PREDETERMINADA POR VÍA
     */
    $resultado = $db->query(
        'SELECT
            conector_pn,
            via,
            terminal_pn,
            sello_pn
         FROM vias_conector
         ORDER BY conector_pn, via'
    );

    foreach ($resultado as $fila) {
        $conectorPN = $fila['conector_pn'];
        $via = 'via-' . $fila['via'];

        $catalogo[$conectorPN]['vias']->{$via} = [
            'pn' => $fila['terminal_pn'] ?? '',
            'sello' => $fila['sello_pn'] ?? ''
        ];
    }

    /*
     * ACCESORIOS ADICIONALES
     *
     * No se incluyen aquí los terminales ni los sellos,
     * porque el editor ya los cuenta por vía.
     */
    $resultado = $db->query(
        'SELECT
            cc.conector_pn,
            cc.componente_pn,
            cc.cantidad,
            c.descripcion
         FROM componentes_conector AS cc
         INNER JOIN componentes AS c
             ON c.pn = cc.componente_pn
         ORDER BY cc.conector_pn, cc.componente_pn'
    );

    foreach ($resultado as $fila) {
        $conectorPN = $fila['conector_pn'];

        $catalogo[$conectorPN]['componentes'][] = [
            'pn' => $fila['componente_pn'],
            'descripcion' => $fila['descripcion'],
            'cantidad' => (float) $fila['cantidad']
        ];
    }

    $db->commit();

    echo json_encode(
        (object) $catalogo,
        JSON_UNESCAPED_UNICODE |
        JSON_THROW_ON_ERROR
    );

    $db->close();

} catch (Throwable $error) {
    error_log(
        'Catálogo de conectores: ' .
        $error->getMessage()
    );

    http_response_code(503);

    echo json_encode([
        'error' => (
            'Catálogo no disponible. ' .
            'Revisa la configuración del servidor.'
        )
    ]);
}