<?php

require '../conection.php'; 

try {
    $color = isset($_GET['color']) ? $_GET['color'] : '';
    $awg = isset($_GET['awg']) ? $_GET['awg'] : '';
    $tipo = isset($_GET['type']) ? $_GET['type'] : '';
    $maquina = isset($_GET['maquina']) ? $_GET['maquina'] : 'todas';
    $calibres = [];
    $totalCables = 0;
    $tintaNegra = 0;
    $tintaBlanca = 0;
    $tintaNegraOpt = 0;
    $tintaBlancaOpt = 0;
    $tinta = '';
    $tiempo = '';
    $maxtime = 135000;
    
    $tiempoTotal = 0; 
    $i = 0;
    // TEMPORAL - para depurar el 500
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
    set_exception_handler(function($e) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        exit;
    });
    set_error_handler(function($errno, $errstr) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "$errstr (código $errno)"]);
        exit;
    });
    if ($maquina == "ESPECIALES") {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' 
               AND r.programado = 1 AND `maq_asignada` = '>10'  AND c.tamano >0
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, tipo ASC";
        $maxtime = 27000;
    } elseif ($maquina == "MCUT-1") {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' 
               AND r.programado = 1 AND `maq_asignada` = 'MCUT-1' AND c.tamano >0
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, tipo ASC";
        $maxtime = 27000;
    } elseif ($maquina == "MCUT-6") {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' 
               AND r.programado = 1 AND `maq_asignada` = 'MCUT-6' AND c.tamano >0 AND c.tintaColor = 'NEGRO'
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, c.tipo ASC";
        $maxtime = 27000;
    } elseif ($maquina == "MCUT-10") {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' 
               AND r.programado = 1  AND `maq_asignada` = 'MCUT-10' AND c.tamano >0
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, c.tipo ASC, c.tintaColor ASC";
        $maxtime = 27000;
    } elseif ($maquina == "MCUT-5") {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' 
               AND r.programado = 1 AND `maq_asignada` = 'MCUT-5' AND c.tamano >0 AND c.tintaColor = 'NEGRA'
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, c.tipo ASC";
        $maxtime = 27000;
    } elseif ($maquina == "MCUT-4") {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' 
               AND r.programado = 1 AND `maq_asignada` = 'MCUT-4' AND c.tamano >0 AND c.tintaColor = 'NEGRA'
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, c.tipo ASC";
        $maxtime = 27000;
    } else if ($maquina == 'todas') {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' AND r.programado = 1 AND TRIM(c.tipo) IN ('GXL','TXL','SGX','UL1569') AND c.tamano >0
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, c.tipo ASC";
    }

    // SI EN TU ARCHIVO CONECTION.PHP LA VARIABLE SE LLAMA $conexion, CAMBIA ESTO A $conexion
    if (!isset($con) || !$con) {
        throw new Exception("La variable de conexión no está definida correctamente.");
    }

    $listasdecorte = mysqli_query($con, $qry); 
    
    if (!$listasdecorte) {
        throw new Exception("Error en la consulta SQL: " . mysqli_error($con));
    }

    while ($rowlistas = mysqli_fetch_array($listasdecorte)) {
        $minutos = 0;
        $pn = $rowlistas['np'];
        $calibre = $rowlistas['aws'];
        $consumo = $rowlistas['cons'];
        $tipo = $rowlistas['tipo'];
        $color = $rowlistas['color'];
        $tamano = round((float)$rowlistas['tamano'], 2);
        $terminal1 = $rowlistas['term1'];
        
        $strip1 = $rowlistas['strip1'];
        if ($strip1 == null) {
            $strip1 = 0;
        } else if ($strip1 < 1.5) {
            $strip1 = $strip1 * 25.4;
        }
        
        $strip2 = $rowlistas['strip2'];
        if ($strip2 == null) {
            $strip2 = 0;
        } else if ($strip2 < 1.5) {
            $strip2 = $strip2 * 25.4;
        }
        
        $strip1 = round((float)$strip1, 2);
        $strip2 = round((float)$strip2, 2);
        $terminal2 = $rowlistas['term2'];
        $tinta = $rowlistas['tintaColor'];
        $qty = $rowlistas['qty'];
        $wo = $rowlistas['wo'];
        $codigo = $rowlistas['codigo'];
        $conector = $rowlistas['conector'];
        $estamp = isset($rowlistas['dist_stamp']) ? $rowlistas['dist_stamp'] : '';
        
        $time_ruteo = round((2.92 * $qty) + 180, 2);
        $minutos = round(((float)$time_ruteo / 60), 2);
        
        $tiempoTotal += $time_ruteo;
        
        if ($tiempoTotal <= $maxtime) {
            $calibres[] = [ 
                'pn' => $pn,
                'calibre' => $calibre,
                'consumo' => $consumo,
                'tipo' => $tipo,
                'color' => $color,
                'tamano' => $tamano,
                'Qty' => $qty,
                'min' => $minutos,
                'tinta' => $tinta,
                'terminal1' => $terminal1,
                'terminal2' => $terminal2,
                'wo' => $wo, 
                'codigo' => $codigo,
                'strip1' => $strip1,
                'strip2' => $strip2,
                'conector' => $conector,
                'estampado' => $estamp
            ];                  
        } else {
            break;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($calibres);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>