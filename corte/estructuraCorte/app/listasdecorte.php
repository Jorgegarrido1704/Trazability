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
   
        $listasdecorte= mysqli_query($con, "SELECT color,aws,cons,tipo,term1,term2,tintaColor,qty FROM corte WHERE cutStatus != 'Cortado'");
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