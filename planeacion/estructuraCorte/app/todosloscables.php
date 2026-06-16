<?php
header('Content-Type: application/json');
require 'conection.php';

try {
    $color = isset($_GET['color']) ? $_GET['color'] : '';
    $awg   = isset($_GET['awg']) ? $_GET['awg'] : '';
    $tipo  = isset($_GET['type']) ? $_GET['type'] : '';
    
    $calibres = [];
    $i = 0;
    
    if ($color != '' && $awg != '' && $tipo != '') {
        // CORRECCIÓN: Se agregó c.qty (ó qty) para mantener consistencia con el else
        $listasdecorte = mysqli_query($con, "SELECT c.np, c.wo,c.color, c.aws, c.cons, c.tipo, c.tamano, c.term1, c.term2, c.tintaColor, c.qty 
                                             FROM corte c 
                                             JOIN registro r ON c.wo = r.wo 
                                             WHERE c.cutStatus != 'Cortado' 
                                               AND r.programado = 1 
                                               AND c.aws = '$awg' 
                                               AND c.color = '$color' 
                                               AND c.tipo = '$tipo' 
                                               AND c.tamano >0
                                             ORDER BY c.term1, c.term2 DESC");
    } else {
        // CORRECCIÓN: Se agregó c.tamano a la consulta para que no rompa el ciclo while
        $listasdecorte = mysqli_query($con, "SELECT c.np, c.wo,c.color, c.aws, c.cons, c.tipo, c.tamano, c.term1, c.term2, c.tintaColor, c.qty 
                                             FROM corte c 
                                             JOIN registro r ON c.wo = r.wo 
                                             WHERE c.cutStatus != 'Cortado' 
                                               AND r.programado = 1  AND c.tamano >0
                                             ORDER BY c.term1, c.term2 DESC");
    }

    if (!$listasdecorte) {
        throw new Exception("Error en la consulta SQL: " . mysqli_error($con));
    }

    while ($rowlistas = mysqli_fetch_array($listasdecorte)) {
        $pn        = $rowlistas['np'];
        $calibre   = $rowlistas['aws'];
        $consumo   = $rowlistas['cons'];
        $tipo      = $rowlistas['tipo'];
        $color     = $rowlistas['color'];
        
        // Evitamos errores si 'tamano' viene nulo en la base de datos
        $tamano    = isset($rowlistas['tamano']) ? round($rowlistas['tamano'], 2) : 0;
        
        $terminal1 = $rowlistas['term1'];
        $terminal2 = $rowlistas['term2'];
        $tinta     = $rowlistas['tintaColor'];
        $qty       = $rowlistas['qty']; 
        $wo        = $rowlistas['wo'];

        $calibres[$i] = [
            'pn'        => $pn,
            'calibre'   => $calibre,
            'consumo'   => $consumo,
            'tipo'      => $tipo,
            'wo'        => $wo,
            'color'     => $color,
            'tamano'    => $tamano,
            'tinta'     => $tinta,
            'terminal1' => $terminal1,
            'terminal2' => $terminal2,
            'qty'       => $qty
        ];                  
        $i++;
    }
    
    echo json_encode($calibres);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    // CORRECCIÓN: Devolvemos el error en formato JSON estructurado
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}
?>