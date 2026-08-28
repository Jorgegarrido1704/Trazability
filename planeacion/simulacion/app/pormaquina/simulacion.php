<?php
require '../conection.php';

header('Content-Type: application/json');

ini_set('display_errors', 0);
error_reporting(E_ALL);

set_exception_handler(function($e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    exit;
});

function clasificarMaquina($calibre, $tinta, $cons, $term1, $term2) {
    $awg = intval(preg_replace('/[^0-9]/', '', (string)$calibre));
    $tintaUpper = strtoupper(trim((string)$tinta));
    $consUpper  = strtoupper(trim((string)$cons));
    $term1Upper = strtoupper((string)$term1);
    $term2Upper = strtoupper((string)$term2);

    $esBlanca   = (strpos($tintaUpper, 'BLAN') !== false || strpos($tintaUpper, 'WHT') !== false || strpos($tintaUpper, 'WHITE') !== false);
    $esNegra    = (strpos($tintaUpper, 'NEG') !== false || strpos($tintaUpper, 'BLK') !== false || strpos($tintaUpper, 'BLACK') !== false);
    $tieneSello = (strpos($term1Upper, 'SELLO') !== false || strpos($term2Upper, 'SELLO') !== false);
    $esConsumoC = (strpos($consUpper, 'C') === 0);

    if ($esConsumoC || in_array($awg, [1, 2, 4, 6, 8])) {
        return 'MCUT-7';
    }
    if ($tieneSello) {
        return 'MCUT-3';
    }
    if (in_array($awg, [10, 12])) {
        return 'MCUT-1';
    }
    if (in_array($awg, [14, 16])) {
        return $esBlanca ? 'MCUT-2' : 'MCUT-5';
    }
    if (in_array($awg, [18, 20, 22, 24])) {
        return $esBlanca ? 'MCUT-4' : 'MCUT-6';
    }
    return 'MCUT-7';
}

try {
    if (!isset($con) || !$con) {
        throw new \Exception("Conexión a BD no disponible.");
    }

    $inputJSON = file_get_contents('php://input');
    $dataInput = json_decode($inputJSON, true);

    $itemsSimulados = $dataInput['items'] ?? [];
    $maquinaFiltro  = $dataInput['maquina'] ?? 'todas';

    if (empty($itemsSimulados)) {
        echo json_encode(["status" => "empty", "debug" => "No se recibieron items en el payload", "data" => []]);
        exit;
    }

    $pnList = [];
    $pnDataMap = [];

    foreach ($itemsSimulados as $item) {
        $pn         = strtoupper(trim($item[1] ?? ''));
        $wo         = trim($item[2] ?? '');
        $qty        = intval($item[3] ?? 0);
        $programado = intval($item[4] ?? 1); // Forzar a int

        // Aceptamos cualquier item con PN y cantidad > 0 que esté activo (1)
        if ($programado === 1 && !empty($pn) && $qty > 0) {
            $pnEscaped = mysqli_real_escape_string($con, $pn);
            $pnList[] = "'$pnEscaped'";

            if (!isset($pnDataMap[$pn])) {
                $pnDataMap[$pn] = [];
            }
            $pnDataMap[$pn][] = [
                'wo' => $wo,
                'qty' => $qty
            ];
        }
    }

    if (empty($pnList)) {
        echo json_encode(["status" => "empty", "debug" => "Ningún PN cumple condición programado=1 y qty>0", "data" => []]);
        exit;
    }

    $pnInQuery = implode(',', array_unique($pnList));

    // Búsqueda insensible a espacios y mayúsculas en listascorte
    $qry = "SELECT 
                id, TRIM(UPPER(pn)) as pn_clean, pn, cons, tipo, aws, color, tamano,
                strip1, terminal1, strip2, terminal2,
                conector, colorTinta, dist_stamp
            FROM listascorte
            WHERE TRIM(UPPER(pn)) IN ($pnInQuery)
              AND (tamano > 0 OR tamano IS NOT NULL)
            ORDER BY aws ASC, color ASC, tipo ASC";

    $res = mysqli_query($con, $qry);
    if (!$res) {
        throw new \Exception("Error en SQL: " . mysqli_error($con));
    }

    $calibres = [];
    $fechaHoy = date('Y-m-d');
    $diaBloqueDefecto = 1;

    while ($row = mysqli_fetch_assoc($res)) {
        $pnKey = $row['pn_clean'];
        if (!isset($pnDataMap[$pnKey])) continue;

        $strip1 = $row['strip1'];
        $strip1 = ($strip1 === null) ? 0 : (($strip1 < 1.5) ? floatval($strip1) * 25.4 : floatval($strip1));
        $strip1 = round((float)$strip1, 2);

        $strip2 = $row['strip2'];
        $strip2 = ($strip2 === null) ? 0 : (($strip2 < 1.5) ? floatval($strip2) * 25.4 : floatval($strip2));
        $strip2 = round((float)$strip2, 2);

        $maquinaAsignada = clasificarMaquina(
            $row['aws'], 
            $row['colorTinta'], 
            $row['cons'], 
            $row['terminal1'], 
            $row['terminal2']
        );

        if ($maquinaFiltro !== 'todas' && $maquinaAsignada !== $maquinaFiltro) {
            continue;
        }

        foreach ($pnDataMap[$pnKey] as $orden) {
            $qtyMultiplicada = $orden['qty'];
            $time_ruteo_seg  = round((2.92 * $qtyMultiplicada) + 180, 2);
            $minutos         = round(($time_ruteo_seg / 60), 2);

            $calibres[] = [
                'id'             => $row['id'],
                'pn'             => $row['pn'],
                'calibre'        => $row['aws'],
                'consumo'        => $row['cons'],
                'tipo'           => $row['tipo'],
                'color'          => $row['color'],
                'tamano'         => round((float)$row['tamano'], 2),
                'Qty'            => $qtyMultiplicada,
                'min'            => $minutos,
                'tinta'          => $row['colorTinta'],
                'terminal1'      => $row['terminal1'],
                'terminal2'      => $row['terminal2'],
                'wo'             => $orden['wo'],
                'codigo'         => $orden['wo'] . '-' . $row['cons'],
                'strip1'         => $strip1,
                'strip2'         => $strip2,
                'conector'       => $row['conector'],
                'estampado'      => $row['dist_stamp'] ?? '',
                'maquina'        => $maquinaAsignada,
                'fecha_asignada' => $fechaHoy,
                'dia_bloque'     => $diaBloqueDefecto
            ];
        }
    }

    echo json_encode($calibres);

} catch (\Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>