<?php

require 'conection.php';

try {
    $color=isset($_GET['color']) ? $_GET['color'] : '';
    $awg=isset($_GET['awg']) ? $_GET['awg'] : '';
    //$tipo =isset($_GET['type']) ? $_GET['type'] : '';
    
   $tipe=[]; 
        $listasdecorte= mysqli_query($con,"SELECT DISTINCT tipo FROM corte WHERE cutStatus != 'Cortado'  AND aws = '$awg'  AND color = '$color'");
        while($rowlistas = mysqli_fetch_array($listasdecorte)){
         
            $awg = $rowlistas['tipo'];
            if (!in_array($awg, $tipe)){
               $tipe[]= $awg;
            }
        }
    echo json_encode($tipe);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    
}



?>