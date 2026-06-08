<?php

require 'conection.php';

try {
     $color=isset($_GET['color']) ? $_GET['color'] : '';
    $awg=isset($_GET['awg']) ? $_GET['awg'] : '';
    $tipo =isset($_GET['type']) ? $_GET['type'] : '';
    
   
    $estructuras = mysqli_query($con, "SELECT np,qty FROM estructuracortetiempos");
    $calibres =[];
    $totalCables = 0;
    $tintaNegra = 0;
    $tintaBlanca = 0;
    $tintaNegraOpt = 0;
    $tintaBlancaOpt = 0;
    $tinta = '';
    $i=0;
    while($rowEstructura = mysqli_fetch_array($estructuras)){
        $pn = $rowEstructura['np'];  
        $qty = $rowEstructura['qty'];
        
        if($color != '' && $awg != '' && $tipo != ''){
            $listasdecorte= mysqli_query($con,"SELECT pn,color,aws,cons,tipo,tamano,terminal1,terminal2 FROM listascorte WHERE pn ='$pn' AND aws = '$awg' AND color = '$color' AND tipo = '$tipo' ORDER BY terminal1, terminal2 DESC");
        }else {
        $listasdecorte= mysqli_query($con,"SELECT pn,color,aws,cons,tipo,tamano,terminal1,terminal2 FROM listascorte WHERE pn ='$pn' ORDER BY terminal1, terminal2 DESC");
        }
        while($rowlistas = mysqli_fetch_array($listasdecorte)){
          //  echo $rowlistas['pn']."<br>";
            $pn = $rowlistas['pn'];
            $calibre = $rowlistas['aws'];
            $consumo = $rowlistas['cons'];
            $tipo = $rowlistas['tipo'];
            $color = $rowlistas['color'];
            $tamano = round($rowlistas['tamano'],2);
            $terminal1 = $rowlistas['terminal1'];
            $terminal2 = $rowlistas['terminal2'];
           
            $buscarColor= mysqli_query($con, "SELECT tintaOrg,tintaOpt FROM coloresencables WHERE `eng_short_color` = '$color' or `eng_color` = '$color' or `spn_color` = '$color' limit 1");
            if (mysqli_num_rows($buscarColor) > 0) {
                $rowColor = mysqli_fetch_array($buscarColor);
               if($rowColor['tintaOrg'] == 'NEGRA'){
                  $tinta= $rowColor['tintaOrg'];
               }else if ($rowColor['tintaOrg'] == 'BLANCA'){
                   $tinta= $rowColor['tintaOrg'];
               }
            }else{
               
            }
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

    
        }

    echo json_encode($calibres);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    $gauges = [];
}



?>