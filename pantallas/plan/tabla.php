<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function escapar($valor): string
{
    return htmlspecialchars(
        (string) ($valor ?? ''),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

try {
    require __DIR__ . '/../../app/conection.php';

    mysqli_set_charset($con, 'utf8mb4');

    // Usa la zona horaria configurada en PHP.
    $inicio = date('Y-m-d');
    $fin = date('Y-m-d', strtotime('+1 day'));

    // Funciona si fecha_up es DATE o DATETIME.
    $stmt = mysqli_prepare(
        $con,
        'SELECT customer, np, wo, qty_wo, corte, ensa,
                loom, cal, emb, fecha, comentarios
         FROM pantallas
         WHERE fecha_up >= ? AND fecha_up < ?'
    );

    mysqli_stmt_bind_param($stmt, 'ss', $inicio, $fin);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $customer,
        $np,
        $wo,
        $qty,
        $corte,
        $ensa,
        $loom,
        $cal,
        $emb,
        $fecha,
        $comentarios
    );

    // Genera el HTML antes de enviarlo para evitar respuestas parciales.
    $html = '<table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Número de parte</th>
                <th>WO</th>
                <th>Cantidad</th>
                <th>Corte</th>
                <th>Ensamble</th>
                <th>Loom</th>
                <th>Calidad</th>
                <th>Embarque</th>
                <th>Fecha requerida</th>
                <th>Comentarios</th>
            </tr>
        </thead>
        <tbody>';

    $filas = 0;

    while (mysqli_stmt_fetch($stmt)) {
        $html .= '<tr>';

        foreach ([
            $customer, $np, $wo, $qty, $corte, $ensa,
            $loom, $cal, $emb, $fecha, $comentarios
        ] as $valor) {
            $html .= '<td>' . escapar($valor) . '</td>';
        }

        $html .= '</tr>';
        $filas++;
    }

    if ($filas === 0) {
        $html .= '<tr><td colspan="11">
            No hay registros cargados hoy.
        </td></tr>';
    }

    $html .= '</tbody></table>';

    mysqli_stmt_close($stmt);

    echo $html;
} catch (Throwable $e) {
    error_log((string) $e);
    http_response_code(500);
    echo 'No se pudo consultar la tabla.';
}