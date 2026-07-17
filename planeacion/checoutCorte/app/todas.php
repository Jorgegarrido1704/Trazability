<?php

require 'conection.php'; 

try {
    $maquina = isset($_GET['maquina']) ? trim($_GET['maquina']) : 'todas';
    $calibres = [];
    $tiempoTotal = 0; 

    ini_set('display_errors', 0);
    error_reporting(E_ALL);
    
    set_exception_handler(function($e) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        exit;
    });

    if (!isset($con) || !$con) {
        throw new Exception("La variable de conexión no está definida correctamente.");
    }

    // ESTRUCTURA OPTIMIZADA: Usamos LEFT JOIN + IS NULL en lugar de NOT EXISTS
    $baseQry = "SELECT c.id, c.np, c.color, c.wo, c.codigo, c.aws, c.cons, c.tipo, c.dist_stamp, c.tamano, 
                       c.term1, c.term2, c.strip1, c.strip2, c.tintaColor, c.qty, c.conector 
                FROM corte c 
                INNER JOIN registro r ON c.wo = r.wo 
                LEFT JOIN carga_congelada cc ON cc.wo = c.wo AND cc.consumo = c.cons
                WHERE cc.wo IS NULL /* Filtro Anti-Join mucho más eficiente */
                  AND c.cutStatus != 'Cortado' 
                  AND r.programado = 1 
                  AND c.tamano > 0 
                  AND r.count IN ('2','3','17')";

    if ($maquina === 'todas') {
        $qry = $baseQry . " AND TRIM(c.tipo) IN ('GXL','TXL','SGX','UL1569')";
    } else {
        $qry = $baseQry . " AND c.maq_asignada = ?";
    }

    // Añadimos el ordenamiento
    $qry .= " ORDER BY c.urgencia DESC, c.aws ASC, c.term1 ASC,
                       CASE WHEN c.term2 LIKE CONCAT('%', c.term1, '%') THEN 0 ELSE 1 END,
                       c.tipo ASC";

    // Preparar la consulta de forma segura
    $stmt = mysqli_prepare($con, $qry);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . mysqli_error($con));
    }

    if ($maquina !== 'todas') {
        mysqli_stmt_bind_param($stmt, "s", $maquina);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($rowlistas = mysqli_fetch_assoc($result)) {
        $qty = (int)$rowlistas['qty'];
        
        // Conversión de medidas eficiente en memoria
        $strip1 = $rowlistas['strip1'] ?? 0;
        if ($strip1 > 0 && $strip1 < 1.5) {
            $strip1 *= 25.4;
        }
        
        $strip2 = $rowlistas['strip2'] ?? 0;
        if ($strip2 > 0 && $strip2 < 1.5) {
            $strip2 *= 25.4;
        }

        $time_ruteo = (2.92 * $qty) + 180;
        $minutos = round(($time_ruteo / 60), 2);
        $tiempoTotal += $time_ruteo;
        
        $calibres[] = [ 
            'id' => $rowlistas['id'],
            'pn' => $rowlistas['np'],
            'calibre' => $rowlistas['aws'],
            'consumo' => $rowlistas['cons'],
            'tipo' => $rowlistas['tipo'],
            'color' => $rowlistas['color'],
            'tamano' => round((float)$rowlistas['tamano'], 2),
            'Qty' => $qty,
            'min' => $minutos,
            'tinta' => $rowlistas['tintaColor'],
            'terminal1' => $rowlistas['term1'],
            'terminal2' => $rowlistas['term2'],
            'wo' => $rowlistas['wo'], 
            'codigo' => $rowlistas['codigo'],
            'strip1' => round((float)$strip1, 2),
            'strip2' => round((float)$strip2, 2),
            'conector' => $rowlistas['conector'],
            'estampado' => $rowlistas['dist_stamp'] ?? ''
        ];                  
    }

    mysqli_stmt_close($stmt);

    header('Content-Type: application/json');
    echo json_encode($calibres);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}