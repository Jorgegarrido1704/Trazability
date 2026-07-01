<?php
require "../../app/conection.php";
try {
    

//$delete=mysqli_query($con,"DELETE FROM familias");
$buscarFamilias=mysqli_query($con,"SELECT pn FROM familias WHERE procesos IS NULL  ORDER BY pn  ASC LIMIT 300");
while($rowFamilias=mysqli_fetch_array($buscarFamilias)){
    $works="";
    
    $pn_routing=$rowFamilias['pn'];
  
    $revisarRouting=mysqli_query($con,"SELECT DISTINCT work_routing   FROM routing_models WHERE pn_routing='$pn_routing' ");
    if(mysqli_num_rows($revisarRouting)>0){
        $cantidads_porceos=mysqli_num_rows($revisarRouting);
        while($rowRouting=mysqli_fetch_array($revisarRouting)){
            $work_routing=$rowRouting['work_routing'];
            $works.=$work_routing." ; ";
        }
    }
   
    $updateFamilia=mysqli_query($con,"UPDATE familias SET cantidad_porcesos='$cantidads_porceos', procesos='$works' WHERE pn='$pn_routing'");
    }
}catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
$buscarfaltanres= mysqli_query($con,"SELECT pn FROM familias WHERE procesos IS NULL  ORDER BY pn  ASC");
if(mysqli_num_rows($buscarfaltanres)>0){
    echo "Faltan por procesar: ".mysqli_num_rows($buscarfaltanres);
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
       <h1>Todo al dia. </h1>
</body>
</html>