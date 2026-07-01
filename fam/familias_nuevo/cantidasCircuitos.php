<?php
require "../../app/conection.php";
try {
    

//$delete=mysqli_query($con,"DELETE FROM familias");
$buscarFamilias=mysqli_query($con,"SELECT pn FROM familias WHERE familia_circuitos IS NULL  ORDER BY pn  ASC ");
while($rowFamilias=mysqli_fetch_array($buscarFamilias)){

    $familia="";
    $pn_routing=$rowFamilias['pn'];
    $revisarCircuitos=mysqli_query($con,"SELECT COUNT(*) AS cantidad FROM listascorte WHERE pn='$pn_routing' ");
    $rowCircuitos=mysqli_fetch_array($revisarCircuitos);
    $cantidad=$rowCircuitos['cantidad'];
   
    switch ($cantidad) {
        case $cantidad >= 150:
            $familia = 'A';
            break;
        case $cantidad >= 75:
            $familia = 'B';
            break;
        case $cantidad >= 50:
            $familia = 'C';
            break;
        case $cantidad >= 20:
            $familia = 'D';
            break;
        
        default:
            $familia = 'E';
    }
    $updateFamilia=mysqli_query($con,"UPDATE familias SET familia_circuitos='$familia', cantidad_circuitos='$cantidad' WHERE pn='$pn_routing'");
    
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
        <button onclick="window.location.href='procesos.php'">Ver Cantidades de procesos</button>
</body>
</html>