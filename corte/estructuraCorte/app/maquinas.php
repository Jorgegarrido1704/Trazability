<?php

require 'conection.php';

try {
    $maquinas = [
        "MCUT-10" => 0, "MCUT-1" => 0, "MCUT-5" => 0, 
        "MCUT-6"  => 0, "MCUT-4" => 0, "MCUT-7" => 0, 
        ">10"     => 0
    ];

    // 1. Mapeo de lógica optimizado
    $maquinaMapping = [
        'NEGRA' => [
            '10_12' => 'MCUT-10',
            '14_16' => 'MCUT-5',
            '18_24' => 'MCUT-4'
        ],
        'BLANCA' => [
            '10_12' => 'MCUT-1',
            '14_16' => 'MCUT-6',
            '18_24' => 'MCUT-7'
        ]
    ];

    // 2. ¡Una sola consulta con INNER JOIN! 
    // Esto reduce drásticamente el tiempo de ejecución eliminando el bucle anidado.
    $query = "
        SELECT c.aws, c.tintaColor, c.time_ruteo 
        FROM estructuracortetiempos e
        INNER JOIN corte c ON e.np = c.pn
        WHERE c.cutStatus != 'Cortado'
        ORDER BY c.aws DESC
    ";

    $resultado = mysqli_query($con, $query);

    if (!$resultado) {
        throw new Exception("Error en la consulta de base de datos: " . mysqli_error($con));
    }

    $setUp_routing = 10;

    // 3. Un solo bucle para procesar la información
    while ($row = mysqli_fetch_assoc($resultado)) {
        $tinta   = $row['tintaColor'];
        $calibre = (int)$row['aws'];
        $tiempo  = round($row['time_ruteo'] / 60, 2);

        // Validar si la tinta existe en nuestro mapa, si no, salta a la siguiente iteración
        if (!isset($maquinaMapping[$tinta])) {
            continue; 
        }

        // Determinar el rango del calibre
        $rango = null;
        if ($calibre == 10 || $calibre == 12) {
            $rango = '10_12';
        } elseif ($calibre == 14 || $calibre == 16) {
            $rango = '14_16';
        } elseif ($calibre >= 18 && $calibre <= 24 && $calibre % 2 == 0) {
            $rango = '18_24';
        }

        // Si el rango existe en el mapa de esa tinta se asigna su máquina, de lo contrario va a '>10'
        $maquinaAsignada = $maquinaMapping[$tinta][$rango] ?? '>10';
        
        // Sumar los tiempos acumulados
        $maquinas[$maquinaAsignada] += ($tiempo + $setUp_routing);
    }

    // Devolver JSON limpio
    header('Content-Type: application/json');
    echo json_encode($maquinas);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Ocurrió un error interno en el servidor."]);
}
?>