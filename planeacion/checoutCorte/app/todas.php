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
        WHERE fecha_asignada = (SELECT fecha_asignada FROM carga_congelada  ORDER BY id ASC LIMIT 1)  
        ORDER BY c.wo ASC,
                 c.consumo ASC
               ";
    }else {
     
      $qry = $qry."
        WHERE c.maquina = '$maquina'
        AND  fecha_asignada = (SELECT fecha_asignada FROM carga_congelada WHERE maquina = '$maquina' ORDER BY id ASC LIMIT 1) 
        ORDER BY c.wo ASC,
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
        $esta_congelado = 'Esta congelado';
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