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
    
    if ($maquina == "ESPECIALES") {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' 
               AND r.programado = 1 AND (c.aws < '10' or c.cons LIKE 'C%') AND c.tamano >0
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, tipo ASC";
        $maxtime = 27000;
    } elseif ($maquina == "MCUT-1") {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' 
               AND r.programado = 1 AND c.aws IN ('10','12','14') AND c.tintaColor='BLANCA'
               AND (c.term1 NOT LIKE '%Sello%' AND c.term2 NOT LIKE '%Sello%')
               AND c.tipo IN ('GXL','TXL','SGX','UL1569') AND c.tamano >0
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, tipo ASC";
        $maxtime = 27000;
    } elseif ($maquina == "MCUT-6") {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' 
               AND r.programado = 1 AND c.aws IN ('16','18','20','22','24') AND c.tintaColor='NEGRA'
               AND (c.term1 NOT LIKE '%Sello%' AND c.term2 NOT LIKE '%Sello%')
               AND c.tipo IN ('GXL','TXL','SGX','UL1569') AND c.tamano >0
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, c.tipo ASC";
        $maxtime = 27000;
    } elseif ($maquina == "MCUT-10") {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' 
               AND r.programado = 1 AND ((c.aws IN ('10','12') AND c.tintaColor='BLANCA') or (c.aws IN ('18','16','14') AND 
               (c.term1 LIKE '%DA2-38%' OR c.term1 LIKE '%DA2-40%' OR c.term1 LIKE '%DA2-80%' OR c.term2 LIKE '%DA2-38%' OR c.term2 LIKE '%DA2-40%' OR c.term2 LIKE '%DA2-80%') ))
               AND c.tipo IN ('GXL','TXL','SGX','UL1569') AND c.tamano >0
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, c.tipo ASC, c.tintaColor ASC";
        $maxtime = 27000;
    } elseif ($maquina == "MCUT-5") {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' 
               AND r.programado = 1 AND c.aws IN ('14','16') AND c.tintaColor='NEGRA'
               AND (c.term1 NOT LIKE '%Sello%' AND c.term2 NOT LIKE '%Sello%')
               AND c.tipo IN ('GXL','TXL','SGX','UL1569') AND c.tamano >0
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, c.tipo ASC";
        $maxtime = 27000;
    } elseif ($maquina == "MCUT-4") {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' 
               AND r.programado = 1 AND c.aws IN ('18','20','22','24') AND c.tintaColor='NEGRA'
               AND (c.term1 NOT LIKE '%Sello%' AND c.term2 NOT LIKE '%Sello%')
               AND c.tipo IN ('GXL','TXL','SGX','UL1569') AND c.tamano >0
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, c.tipo ASC";
        $maxtime = 27000;
    } else if ($maquina == 'todas') {
        $qry ="SELECT c.np, c.color, c.wo,c.codigo, c.aws, c.cons, c.tipo,c.dist_stamp, c.tamano, c.term1, c.term2,c.strip1,c.strip2, c.tintaColor, c.qty, c.time_ruteo,c.conector 
               FROM corte c 
               JOIN registro r ON c.wo = r.wo 
               WHERE c.cutStatus != 'Cortado' AND r.programado = 1 AND c.tipo IN ('GXL','TXL','SGX','UL1569') AND c.tamano >0
               ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
               CASE WHEN c.term2 LIKE CONCAT('%',c.term1,'%') THEN 0 ELSE 1 END, c.tipo ASC";
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
        $tamano = round($rowlistas['tamano'], 2);
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
        
        $strip1 = round($strip1, 2);
        $strip2 = round($strip2, 2);
        $terminal2 = $rowlistas['term2'];
        $tinta = $rowlistas['tintaColor'];
        $qty = $rowlistas['qty'];
        $wo = $rowlistas['wo'];
        $codigo = $rowlistas['codigo'];
        $conector = $rowlistas['conector'];
        $estamp = $rowlistas['estamp'];
        
        $time_ruteo = round((2.92 * $qty) + 180, 2);
        $minutos = round(($time_ruteo / 60), 2);
        
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
                'estamapo' => $estamp
            ];                  
        } else {
            break;
        }
    }

    // Cabecera indispensable para asegurar que el navegador entienda que es JSON
    header('Content-Type: application/json');
    echo json_encode($calibres);

} catch (Exception $e) {
    // Si algo falla, registramos el error y enviamos un JSON válido con el error para debuggear
    error_log("Error cargando calibres: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>