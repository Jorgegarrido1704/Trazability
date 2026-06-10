<?php

require 'conection.php';

try {
     $color=isset($_GET['color']) ? $_GET['color'] : '';
    $awg=isset($_GET['awg']) ? $_GET['awg'] : '';
    $tipo =isset($_GET['type']) ? $_GET['type'] : '';
    
   
    
    $calibres =[];
    $totalCables = 0;
    $tintaNegra = 0;
    $tintaBlanca = 0;
    $tintaNegraOpt = 0;
    $tintaBlancaOpt = 0;
    $tinta = '';
    $i=0;
   
        
        if($color != '' && $awg != '' && $tipo != ''){
            $listasdecorte= mysqli_query($con,"SELECT np,color,aws,cons,tipo,tamano,term1,term2,tintaColor FROM corte WHERE cutStatus != 'Cortado' AND aws = '$awg' AND color = '$color' AND tipo = '$tipo' ORDER BY term1, term2 DESC");
        }else {
        $listasdecorte= mysqli_query($con,"SELECT np,color,aws,cons,tipo,tamano,term1,term2,tintaColor FROM corte WHERE cutStatus != 'Cortado' ORDER BY term1, term2 DESC");
        }
        while($rowlistas = mysqli_fetch_array($listasdecorte)){
          //  echo $rowlistas['pn']."<br>";
            $pn = $rowlistas['np'];
            $calibre = $rowlistas['aws'];
            $consumo = $rowlistas['cons'];
            $tipo = $rowlistas['tipo'];
            $color = $rowlistas['color'];
            $tamano = round($rowlistas['tamano'],2);
            $terminal1 = $rowlistas['term1'];
            $terminal2 = $rowlistas['term2'];
            $tinta = $rowlistas['tintaColor'];
           
        $calibres[$i]= [
            'pn' => $pn,
            'calibre' => $calibre,
            'consumo' => $consumo,
            'tipo' => $tipo,
            'color' => $color,
            'tamano' => $tamano,
            'tinta' => $tinta,
            'terminal1' => $terminal1,
            'terminal2' => $terminal2
        ];                  
       $i++;
        }
    echo json_encode($calibres);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    $gauges = [];
}



?>