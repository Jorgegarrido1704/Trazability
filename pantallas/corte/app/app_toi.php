<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

date_default_timezone_set('America/Mexico_City');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $con = new mysqli("127.0.0.1", "root", "", "toi");
    $con->set_charset('utf8mb4');

    $maquinas = ['M1'=>0,'M2'=>0,'M3'=>0,'M4'=>0,'M5'=>0,'M6'=>0,'totales'=>0];

    // ---- Cálculo del turno ----
    $ahora = new DateTime();
    $hoy   = $ahora->format('Y-m-d');

    $diaInicio   = new DateTime("$hoy 07:30:00");
    $nocheInicio = new DateTime("$hoy 19:00:00");

    if ($ahora >= $nocheInicio) {
        // Noche (antes de medianoche): hoy 19:00 -> mañana 07:30
        $inicio = $nocheInicio;
        $fin    = (clone $diaInicio)->modify('+1 day');
    } elseif ($ahora >= $diaInicio) {
        // Día: hoy 07:30 -> hoy 19:00
        $inicio = $diaInicio;
        $fin    = $nocheInicio;
    } else {
        // Noche (después de medianoche): ayer 19:00 -> hoy 07:30
        $inicio = (clone $nocheInicio)->modify('-1 day');
        $fin    = $diaInicio;
    }
    $inicioStr = $inicio->format('Y-m-d H:i:s');
    $finStr    = $fin->format('Y-m-d H:i:s');

    // ---- Una sola consulta para ambos estados ----
    $sql = "SELECT maquina, estado, COUNT(*) AS cantidad
            FROM lecturas
            WHERE fecha >= ? AND fecha < ?
              AND estado IN ('RUN','Corte')
            GROUP BY maquina, estado";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ss', $inicioStr, $finStr);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $m = $row['maquina'];
        if (!array_key_exists($m, $maquinas) || $m === 'totales') {
            continue; // ignora máquinas desconocidas
        }

        if ($m === 'M6' && $row['estado'] === 'Corte') {
            $maquinas['M6'] = (int) $row['cantidad'];
        } 
        if($m === 'M2' && $row['estado'] === 'Corte') {
            $maquinas['M2'] = (int) $row['cantidad'];
        }/* elseif ($m !== 'M6' && $m !== 'M2'  && $row['estado'] === 'RUN') {
            $maquinas[$m] = (int) round($row['cantidad'] / 2);
        }*/
        
    }

    $maquinas['totales'] = $maquinas['M1'] + $maquinas['M2'] + $maquinas['M3']
                         + $maquinas['M4'] + $maquinas['M5'] + $maquinas['M6'];

    echo json_encode([
        'maquinas' => $maquinas,
        'turno'    => ['inicio' => $inicioStr, 'fin' => $finStr] // útil para depurar
    ]);

} catch (Throwable $e) {
    error_log("Error en app_toi.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['maquinas' => [], 'error' => 'Error interno']);
}