<?php

require 'conection.php';

try {
    $maquinas = [
        "MCUT-10" => 0, "MCUT-1" => 0, "MCUT-5" => 0, 
        "MCUT-6"  => 0, "MCUT-4" => 0, "MCUT-7" => 0, 
        ">10"     => 0
    ];

    // Estructura: [tinta][rango_de_calibres] => modelo_maquina
    $maquinaMapping = [
        'NEGRA' => [
            '10_12' => 'MCUT-10',
            '14_16' => 'MCUT-5',
            '18_24' => 'MCUT-4'
        ],
        'BLANCA' => [
            '10_12' => 'MCUT-1',
            '14_16' => 'MCUT-6',
            '18_24' => 'MCUT-6'
        ]
    ];

    // SOLUCIÓN: Agregada la columna 'qty' al SELECT para que no rompa el while
    $stmtListas = mysqli_prepare($con, "SELECT  aws, color, term1, term2, tintaColor, time_ruteo, cutStatus FROM corte WHERE cutStatus != 'Cortado' ORDER BY aws,color,term1,term2 DESC");
    
    if (!$stmtListas) {
        throw new Exception("Error al preparar la consulta: " . mysqli_error($con));
    }

    mysqli_stmt_execute($stmtListas);
    $resListas = mysqli_stmt_get_result($stmtListas);

    while ($rowlistas = mysqli_fetch_assoc($resListas)) {
      
        $calibre  = (int)$rowlistas['aws'];
        $color    = $rowlistas['color'];
        $term1    = $rowlistas['term1'];
        $term2    = $rowlistas['term2'];
        // Pasamos a mayúsculas y limpiamos espacios por si acaso
        $tinta    = trim(strtoupper($rowlistas['tintaColor'])); 
       
        $tiempo   = round($rowlistas['time_ruteo'] / 60, 2);

        $setUp_routing = 10;

        // Determinar el rango del calibre de manera fija
        $rango = '>10';
        if ($calibre == 10 || $calibre == 12) {
            $rango = '10_12';
        } elseif ($calibre == 14 || $calibre == 16) {
            if(strpos($term1,"Sello")){
                $rango = '10_12';
                $tinta='NEGRA';
            }else if(strpos($term2,"Sello")){
                $rango = '10_12';
                $tinta='NEGRA';
            }
            $rango = '14_16';
        } elseif ($calibre >= 18 && $calibre <= 24 && $calibre % 2 == 0) {
             if($calibre == 18 && strpos($term1,"Sello")){
                $rango = '10_12';
                $tinta='NEGRA';
            }else if($calibre == 18 && strpos($term2,"Sello")){
                $rango = '10_12';
                $tinta='NEGRA';
            }
            $rango = '18_24';
        }

        // Si la tinta existe en nuestro mapeo, buscamos su máquina. Si no, va por defecto a '>10'
        if (isset($maquinaMapping[$tinta])) {
            $maquinaAsignada = $maquinaMapping[$tinta][$rango] ?? '>10';
        } else {
            $maquinaAsignada = '>10';
        }

        $maquinas[$maquinaAsignada] += ($tiempo + $setUp_routing);
    }

    // Cerrar statement
    mysqli_stmt_close($stmtListas);

    // Devolver JSON limpio
    header('Content-Type: application/json');
    echo json_encode($maquinas);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        "error" => "Ocurrió un error interno en el servidor.",
        "detalle" => $e->getMessage()
    ]);
}
?>