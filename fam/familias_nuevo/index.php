<?php
require "../../app/conection.php";
try {
    

$delete=mysqli_query($con,"DELETE FROM familias");
$buscarFamilias=mysqli_query($con,"SELECT DISTINCT pn_routing FROM routing_models  ORDER BY pn_routing  ASC ");
while($rowFamilias=mysqli_fetch_array($buscarFamilias)){
   
    $pn_routing=$rowFamilias['pn_routing'];
  
    
    $insertarFamilia=mysqli_query($con,"INSERT INTO familias ( `pn`) VALUES ('$pn_routing')");
}
}catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registro Familias</title>
</head>
<body>
        <button onclick="window.location.href='cantidasCircuitos.php'">Ver Cantidades de Circuitos</button>
</body>
</html>