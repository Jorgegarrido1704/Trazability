<?php
require 'conection.php'; 

try {
    $maquina = isset($_GET['maquina']) ? $_GET['maquina'] : 'todas';
    $calibres = [];
    $totalCables = 0;
   
    $tiempoTotal = 0; 

     
       $qry = "SELECT c.id, c.np, c.color, c.wo, c.codigo, c.aws, c.cons, c.tipo, c.dist_stamp,
               c.tamano, c.term1, c.term2, c.strip1, c.strip2, c.tintaColor, c.qty,
               c.time_ruteo, c.conector,
               cc.fecha_asignada, cc.dia_bloque
        FROM corte c
        JOIN carga_congelada cc ON cc.wo = c.wo AND cc.consumo = c.cons";

    if($maquina == 'todas') {
        $qry = $qry . "
        WHERE c.cutStatus != 'Cortado'
          AND c.tamano > 0
        ORDER BY cc.fecha_asignada ASC,
                 cc.dia_bloque ASC
                LIMIT 300";
    }else {
     
      $qry = $qry."
        WHERE c.cutStatus != 'Cortado'
          AND c.maq_asignada = '$maquina'
          AND c.tamano > 0
         
        ORDER BY cc.fecha_asignada ASC,
                 cc.dia_bloque ASC
                LIMIT 150";

    }

    if (!isset($con) || !$con) {
        throw new Exception("La variable de conexión no está definida correctamente.");
    }

    $listasdecorte = mysqli_query($con, $qry); 
    
    if (!$listasdecorte) {
        throw new Exception("Error en la consulta SQL: " . mysqli_error($con));
    }

    while ($rowlistas = mysqli_fetch_array($listasdecorte)) {
        $pn = $rowlistas['np'];
        $calibre = $rowlistas['aws'];
        $consumo = $rowlistas['cons'];
        $tipo = $rowlistas['tipo'];
        $color = $rowlistas['color'];
        $tamano = round((float)$rowlistas['tamano'], 2);
        $terminal1 = $rowlistas['term1'];
        
        $strip1 = $rowlistas['strip1'] ?? 0;
        if ($strip1 < 1.5 && $strip1 > 0) $strip1 = $strip1 * 25.4;
        
        $strip2 = $rowlistas['strip2'] ?? 0;
        if ($strip2 < 1.5 && $strip2 > 0) $strip2 = $strip2 * 25.4;
        
        $strip1 = round((float)$strip1, 2);
        $strip2 = round((float)$strip2, 2);
        $terminal2 = $rowlistas['term2'];
        $tinta = $rowlistas['tintaColor'];
        $qty = $rowlistas['qty'];
        $wo = $rowlistas['wo'];
        $codigo = $rowlistas['codigo'];
        $conector = $rowlistas['conector'];
        $estamp = $rowlistas['dist_stamp'] ?? '';
        
        $time_ruteo = round((2.92 * $qty) + 180, 2);
        $minutos = round(((float)$time_ruteo / 60), 2);
        
        $tiempoTotal += $time_ruteo;
        
        
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
        
    }

    header('Content-Type: application/json');
    echo json_encode($calibres);

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>