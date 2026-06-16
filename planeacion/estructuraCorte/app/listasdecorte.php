<?php

require 'conection.php';

try {
    
    $calibres =[];
    $totalCables = 0;
    $tintaNegra = 0;
    $tintaBlanca = 0;
    $tintaNegraOpt = 0;
    $tintaBlancaOpt = 0;
    $sellos=0;
   
        $listasdecorte= mysqli_query($con,  "SELECT 
             c.np,c.color, 
            c.aws, 
            c.cons, 
            c.tipo, 
            c.term1, 
            c.term2, 
            c.tintaColor, 
            c.qty,
            c.wo 
          FROM corte c 
          JOIN registro r ON c.wo = r.wo 
          WHERE c.cutStatus != 'Cortado'  AND c.tamano >0
            AND r.programado = 1");
        while($rowlistas = mysqli_fetch_array($listasdecorte)){
            $calibre = $rowlistas['aws'];
            $consumo = $rowlistas['cons'];
            $tipo = $rowlistas['tipo'];
            $color = $rowlistas['color'];
            $qty = $rowlistas['qty'];
            $term1   = $rowlistas['term1'];
            $term2   = $rowlistas['term2'];       
            $tinta   = trim(strtoupper($rowlistas['tintaColor']));
            if (!isset($calibres[$calibre])){ 
                $calibres[$calibre] = $qty;
            }
            else {
                $calibres[$calibre] += $qty;
            }
            $totalCables +=  $qty;
           
               if($tinta == 'NEGRA'){
                   $tintaNegra += $qty;
               }else if ($tinta== 'BLANCA'){
                   $tintaBlanca += $qty;
               }
            
            if (stripos($term1, "Sello") !== false || stripos($term2, "Sello") !== false) {
                 $sellos += $qty;

        }
        
            
          
       
    }

    echo json_encode([
        'calibres' => $calibres,
        'totalCables' => $totalCables,
        'tintaNegra' => $tintaNegra,
        'tintaBlanca' => $tintaBlanca,
        'sellos' => $sellos
    ]);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    $gauges = [];
}



?>