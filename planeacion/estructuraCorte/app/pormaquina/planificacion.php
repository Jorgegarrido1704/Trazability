<?php
// Aseguramos que la respuesta siempre sea tratada como JSON, incluso si hay errores
header('Content-Type: application/json');

require '../conection.php';

try {
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $valor = isset($_GET['valor']) ? $_GET['valor'] : "";
    
    $update = "UPDATE `registro` SET `programado`='$valor' WHERE `id`='$id'";
    $datos = mysqli_query($con, $update);
    
    if($datos){
        $respuesta = array("success" => true);
    } else {
        // Si la consulta falla, incluimos el error de MySQL para saber qué pasó
        $respuesta = array("success" => false, "error" => mysqli_error($con));
    }
    
    // CORRECCIÓN: Quitamos mysqli_fetch_all y pasamos directamente el array
    echo json_encode($respuesta);

} catch (Exception $e) {
    // CORRECCIÓN: Si entra al catch, también devolvemos JSON válido
    echo json_encode(array("success" => false, "error" => $e->getMessage()));
}