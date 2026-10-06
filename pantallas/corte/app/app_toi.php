<?php
$host = "127.0.0.1";
$user = "root";
$clave = "";
$bd = "toi";
$con = mysqli_connect($host, $user, $clave, $bd);
date_default_timezone_set('America/Mexico_City');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$fecha=date('Y-m-d');
try{
    $maquinas = [
        'M1' => 0,
        'M2' => 0,
        'M3' => 0,
        'M4' => 0,
        'M5' => 0,
        'M6' => 0,
        'totales'=> 0
    ];
    $ahora = new DateTime();
$hoy   = $ahora->format('Y-m-d');
$hora  = $ahora->format('H:i');

if ($hora >= '19:00') {
    // Turno nocturno (antes de medianoche): hoy 19:00 -> mañana 07:00
    $inicio = "$hoy 19:00:00";
    $fin    = (new DateTime("$hoy 07:00:00"))->modify('+1 day')->format('Y-m-d H:i:s');
} elseif ($hora >= '07:30') {
    // Turno de día: hoy 07:30 -> hoy 19:00
    $inicio = "$hoy 07:30:00";
    $fin    = "$hoy 19:00:00";
} else {
    // Turno nocturno (después de medianoche): ayer 19:00 -> hoy 07:00
    $inicio = (new DateTime("$hoy 19:00:00"))->modify('-1 day')->format('Y-m-d H:i:s');
    $fin    = "$hoy 07:00:00";
}
        //busqueda de cantidad de cortes
        $lectura = mysqli_query($con, "SELECT maquina, COUNT(*) AS cantidad
        FROM lecturas
        WHERE fecha >= '$inicio' AND fecha < '$fin'
          AND estado = 'RUN'
        GROUP BY maquina
        ORDER BY maquina ASC
        ");
        while ($row = mysqli_fetch_assoc($lectura)) {
            if($row['maquina'] == 'M1'){
                $maquinas['M1'] = round($row['cantidad']/2, 0);
            }else if($row['maquina'] == 'M2'){
                $maquinas['M2'] = round($row['cantidad']/2, 0);
            }else if($row['maquina'] == 'M3'){
                $maquinas['M3'] = round($row['cantidad']/2, 0);
            }else if($row['maquina'] == 'M4'){  
                $maquinas['M4'] = round($row['cantidad']/2, 0);
            }else if($row['maquina'] == 'M5'){
                $maquinas['M5'] = round($row['cantidad']/2, 0);
            }else if($row['maquina'] == 'M6'){
                $maquinas['M6'] = round($row['cantidad']/2, 0);
            }
            $maquinas['totales'] += $maquinas[$row['maquina']];
        }

        echo json_encode([
                'maquinas' => $maquinas
            ]);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    echo json_encode([]);
}