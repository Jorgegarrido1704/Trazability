<?php

require 'conection.php';

try {
    $estructuras = mysqli_query($con, "SELECT np,qty FROM estructuracortetiempos");
    $calibres =[];
    $totalCables = 0;
    $tintaNegra = 0;
    $tintaBlanca = 0;
    $tintaNegraOpt = 0;
    $tintaBlancaOpt = 0;
    $sellos=0;
    while($rowEstructura = mysqli_fetch_array($estructuras)){
        $pn = $rowEstructura['np'];
        $qty = $rowEstructura['qty'];
        $listasdecorte= mysqli_query($con, "SELECT color,aws,cons,tipo FROM listascorte WHERE pn = '$pn'");
        while($rowlistas = mysqli_fetch_array($listasdecorte)){
            $calibre = $rowlistas['aws'];
            $consumo = $rowlistas['cons'];
            $tipo = $rowlistas['tipo'];
            $color = $rowlistas['color'];
            if (!isset($calibres[$calibre])){ 
                $calibres[$calibre] = $qty;
            }
            else {
                $calibres[$calibre] += $qty;
            }
            $totalCables +=  $qty;
            $buscarColor= mysqli_query($con, "SELECT tintaOrg,tintaOpt FROM coloresencables WHERE `eng_short_color` = '$color' or `eng_color` = '$color' or `spn_color` = '$color' limit 1");
            if (mysqli_num_rows($buscarColor) > 0) {
                $rowColor = mysqli_fetch_array($buscarColor);
               if($rowColor['tintaOrg'] == 'NEGRA'){
                   $tintaNegra += $qty;
               }else if ($rowColor['tintaOrg'] == 'BLANCA'){
                   $tintaBlanca += $qty;
               }
            }
            

        }
        $totalsellosIzquierda = mysqli_query($con, "SELECT * FROM listascorte WHERE pn = '$pn' AND terminal1 LIKE  '%Sello%'");
        $totalsellosDerecha = mysqli_query($con, "SELECT * FROM listascorte WHERE pn = '$pn' AND terminal2 LIKE  '%Sello%'");
        if(mysqli_num_rows($totalsellosIzquierda) > 0){
            
            $sellos += mysqli_num_rows($totalsellosIzquierda)*$qty;
        }
        if(mysqli_num_rows($totalsellosDerecha) > 0){
            
            $sellos += mysqli_num_rows($totalsellosDerecha) *$qty;
    
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