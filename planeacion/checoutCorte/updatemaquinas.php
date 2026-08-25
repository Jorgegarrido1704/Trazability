<?php

require 'app/conection.php';

const TAMANO_BATCH = 540.0; // Tamaño de cada lote/ronda en minutos

//$consulta_mcut2="SELECT cons,color,tintaColor,aws,term1,term2,time_ruteo FROM corte WHERE aws IN (14,16) AND cutStatus != 'Cortado' AND cons not LIKE 'C%' AND 
//term1 not LIKE '%Sello%' AND term2 not LIKE '%Sello%'
// AND tamano > 0 ";
try {
        $maquinas ='todas';
            mysqli_query($con,"UPDATE corte SET maq_asignada = Null  WHERE cutStatus != 'Cortado'  AND tamano > 0 ");

            mysqli_query($con,"UPDATE corte SET maq_asignada ='MCUT-2' WHERE aws IN (14,16) AND cutStatus != 'Cortado' AND cons not LIKE 'C%' AND 
            term1 not LIKE '%Sello%' AND term2 not LIKE '%Sello%' AND tintaColor='BLANCA'
            AND tamano > 0 ");
            mysqli_query($con,"UPDATE corte SET maq_asignada ='MCUT-4' WHERE aws IN (18,20,22,24) AND cutStatus != 'Cortado' AND cons not LIKE 'C%' AND 
            term1 not LIKE '%Sello%' AND term2 not LIKE '%Sello%' AND tintaColor='BLANCA'
            AND tamano > 0 ");
            
            mysqli_query($con,"UPDATE corte SET maq_asignada ='MCUT-5' WHERE aws IN (14,16) AND cutStatus != 'Cortado' AND cons not LIKE 'C%' AND 
            term1 not LIKE '%Sello%' AND term2 not LIKE '%Sello%' AND tintaColor='NEGRA'
            AND tamano > 0 ");
            mysqli_query($con,"UPDATE corte SET maq_asignada ='MCUT-6' WHERE aws IN (18,20,22,24) AND cutStatus != 'Cortado' AND cons not LIKE 'C%' AND 
            term1 not LIKE '%Sello%' AND term2 not LIKE '%Sello%' AND tintaColor='NEGRA'
            AND tamano > 0 ");
            mysqli_query($con,"UPDATE corte SET maq_asignada ='MCUT-7' WHERE  cutStatus != 'Cortado' AND (cons  LIKE 'C%' OR aws IN (1,2,4,6,8)) ");

            mysqli_query($con,"UPDATE corte SET maq_asignada='MCUT-1' WHERE aws  IN (10,12) AND cutStatus != 'Cortado'
            AND cons NOT LIKE 'C%' AND tamano > 0
            AND term1 NOT LIKE '%Sello%' AND term2 NOT LIKE '%Sello%'");

            mysqli_query($con,"UPDATE corte SET maq_asignada='MCUT-3' WHERE aws NOT IN (1,2,4,6,8) AND cutStatus != 'Cortado'
            AND cons NOT LIKE 'C%' AND tamano > 0
            AND (term1 LIKE '%Sello%' OR term2 LIKE '%Sello%') ");

            header('Content-Type: application/json');
                echo json_encode($maquinas);

                } catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        "error" => "Ocurrió un error interno en el servidor.",
        "detalle" => $e->getMessage()
    ]);
}