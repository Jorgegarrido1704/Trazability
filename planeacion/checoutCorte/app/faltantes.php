<?php
require 'conection.php'; 

try {
    $maquina = isset($_GET['maquina']) ? $_GET['maquina'] : 'todas';
    $calibres = [];
    $totalCables = 0;
   
    $tiempoTotal = 0; 

     
       $qry = "SELECT c.*
        FROM carga_congelada c
                    ";
       

    if($maquina == 'todas') {
        $qry = $qry . "
       WHERE c.urgencia = 1
        ORDER BY 
         
        c.fecha_asignada ASC,
        c.dia_bloque ASC,
        c.wo ASC,
        c.consumo ASC
               ";
    }else {
     
      $qry = $qry."
        WHERE c.maquina = '$maquina' AND c.urgencia =1
        ORDER BY 
       
        c.fecha_asignada ASC,
        c.dia_bloque ASC,
        c.wo ASC,
                 c.consumo ASC
                ";

    }

    if (!isset($con) || !$con) {
        throw new Exception("La variable de conexión no está definida correctamente.");
    }

    $listasdecorte = mysqli_query($con, $qry); 
    
    if (!$listasdecorte) {
        throw new Exception("Error en la consulta SQL: " . mysqli_error($con));
    }

    while ($rowlistas = mysqli_fetch_array($listasdecorte)) {
        $pn = $rowlistas['pn'];
        $consumo = $rowlistas['consumo']; 
        $wo = $rowlistas['wo'];
        $esta_congelado = $rowlistas['fecha_asignada'].'-'.$rowlistas['dia_bloque'];
        $codigo = $rowlistas['id_corte'];

      
        
            $calibres[] = [ 
                'pn' => $pn,
                'consumo' => $consumo,
                'wo' => $wo, 
                'codigo' => $codigo,
                'congelada' => $esta_congelado
            ];                  
        
    }

    header('Content-Type: application/json');
    echo json_encode($calibres);

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>