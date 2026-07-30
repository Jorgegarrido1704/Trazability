<?php
require 'conection.php'; 

try {
    $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
    if(!$codigo) {
        throw new Exception("Código ausente.");
    }
    $fechaCorte = date('Y-m-d H:i:s');
    $nuevafechaCutF= date('d-m-Y H:i');
  
    $changeEstatus = "UPDATE carga_congelada SET urgencia='1' WHERE id_corte='$codigo'";
    mysqli_query($con, $changeEstatus);
    
    
    
    $dat['status'] = "success";
    echo json_encode($dat);
 
} catch(Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>