<?php
require 'conection.php'; 

try {
    $maquina = isset($_GET['maquina']) ? $_GET['maquina'] : 'todas';
    $calibres = [];
    $totalCables = 0;
   
    $tiempoTotal = 0; 

     
       $qry = "SELECT c.*
        FROM corte c
                    ";
       

    if($maquina == 'todas') {
        $qry = $qry . "
       WHERE c.cutStatus='Cortado'
        ORDER BY 
        c.wo ASC,
        c.cons ASC
               ";
    }else {
     
      $qry = $qry."
        WHERE c.maq_asignada = '$maquina' AND c.cutStatus='Cortado'
        ORDER BY 
        c.wo ASC,
        c.cons ASC
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
        $consumo = $rowlistas['cons']; 
        $wo = $rowlistas['wo'];
        $esta_congelado = '';
        $codigo = $rowlistas['id'];

      
        
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