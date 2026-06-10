<?php

require 'conection.php';

try {
    // $color=isset($_GET['color']) ? $_GET['color'] : '';
   // $awg=isset($_GET['awg']) ? $_GET['awg'] : '';
    //$tipo =isset($_GET['type']) ? $_GET['type'] : '';
    
   $gauge=[];
     
        $listasdecorte= mysqli_query($con,"SELECT DISTINCT aws FROM corte WHERE cutStatus != 'Cortado'  ");
        while($rowlistas = mysqli_fetch_array($listasdecorte)){
         
            $awg = $rowlistas['aws'];
            if (!in_array($awg, $gauge)){
               $gauge[]= $awg;
            }
           
          
        }
        
    echo json_encode($gauge);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    
}



?>