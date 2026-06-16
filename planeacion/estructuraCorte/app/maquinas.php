<?php

require 'conection.php';

try {
    $maquinas = [
        "MCUT-10" => 0, "MCUT-1" => 0, "MCUT-5" => 0, 
        "MCUT-6"  => 0, "MCUT-4" => 0, 
        ">10"     => 0, "MCUT-10SN" => 0, "MCUT-10SB" => 0,"TOTAL_MAQUINAS" => 0
    ];

    // Estructura: [tinta][rango_de_calibres] => modelo_maquina
    $maquinaMapping = [
        'NEGRA' => [
            '10_12' => 'MCUT-10',
            '14_16' => 'MCUT-5',
            '18_24' => 'MCUT-4',
            'sello' => 'MCUT-10SN',
        ],
        'BLANCA' => [
            '10_12' => 'MCUT-1',
            '14_16' => 'MCUT-6',
            '18_24' => 'MCUT-6',
            'sello' => 'MCUT-10SB',
        ]
    ];

    $stmtListas = mysqli_prepare($con, "SELECT c.np, c.color, c.aws, c.cons, c.tipo, c.tamano, c.term1, c.term2, c.tintaColor, c.qty, c.time_ruteo 
                                             FROM corte c 
          JOIN registro r ON c.wo = r.wo 
                                              WHERE c.cutStatus != 'Cortado'  AND c.tamano > 0
            AND r.programado = 1  ORDER BY c.aws, c.color, c.term1, c.term2 DESC");
    
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
        $cons     = $rowlistas['cons'];
        $tinta    = trim(strtoupper($rowlistas['tintaColor'])); 
       
        $tiempo   = round($rowlistas['time_ruteo'] / 60, 2);
        $setUp_routing = 5;

        // --- NUEVA LÓGICA DE ASIGNACIÓN DE RANGO ---
        
        // 1. Prioridad Máxima: ¿Tiene Sello en alguna de las terminales?
        if (stripos($term1, "Sello") !== false || stripos($term2, "Sello") !== false) {
            $rango = 'sello';
        } 
       elseif(stripos($cons, "Corte") !== false || stripos($term2, "CORTE") !== false){
            $rango = '>10';
            }else {
            if ($calibre == 10 || $calibre == 12) {
                $rango = '10_12';
            } elseif ($calibre == 14 || $calibre == 16) {
                $rango = '14_16';
            } elseif ($calibre >= 18 && $calibre <= 24 && $calibre % 2 == 0) {
                $rango = '18_24';
            } else {
                $rango = '>10';
            }
        }

        // Si la tinta existe en nuestro mapeo, buscamos su máquina. Si no, va por defecto a '>10'
        if (isset($maquinaMapping[$tinta])) {
            $maquinaAsignada = $maquinaMapping[$tinta][$rango] ?? '>10';
        } else {
            $maquinaAsignada = '>10';
        }

        // Evitar errores si la máquina asignada no está declarada en el array principal
        if (isset($maquinas[$maquinaAsignada])) {
            $maquinas[$maquinaAsignada] += ($tiempo + $setUp_routing);
            $maquinas['TOTAL_MAQUINAS'] += ($tiempo + $setUp_routing);
        } else {
            $maquinas['>10'] += ($tiempo + $setUp_routing);
            
        }
    }
    $maquinas["MCUT-10TT"] = $maquinas["MCUT-10"] + $maquinas["MCUT-10SN"] + $maquinas["MCUT-10SB"];
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