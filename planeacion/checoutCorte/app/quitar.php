<?php
require 'conection.php'; 

try {
    $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
    if(!$codigo) {
        throw new Exception("Código ausente.");
    }
    $fechaCorte = date('Y-m-d H:i:s');
  
    $changeEstatus = "UPDATE corte SET cutStatus='Cortado', fechaCorte='$fechaCorte' WHERE codigo='$codigo'";
    mysqli_query($con, $changeEstatus);
    
    $buscarWo = mysqli_query($con, "SELECT wo FROM corte WHERE codigo='$codigo'");
    $filaWo = mysqli_fetch_row($buscarWo);
    
    if ($filaWo) {
        $wo = $filaWo[0];
        $buscar = mysqli_query($con, "SELECT * FROM corte WHERE wo='$wo' AND cutStatus='Activo'");
        
        if (mysqli_num_rows($buscar) == 0) {
            mysqli_query($con, "UPDATE registro SET count='4', donde='En espera de liberacion' WHERE wo='$wo'");
            mysqli_query($con, "UPDATE registroparcial SET libePar=libePar+cortPar, cortPar='0' WHERE wo='$wo'");
            mysqli_query($con, "UPDATE timesharn SET cutF='$fechaCorte' WHERE wo='$wo'");
        }
    }
    
    $dat['status'] = "success";
    echo json_encode($dat);
 
} catch(Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>