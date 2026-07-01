<?php
require "../../app/conection.php";
try {
    

//$delete=mysqli_query($con,"DELETE FROM familias");
$buscarFamilias=mysqli_query($con,"SELECT pn FROM familias WHERE familia_circuitos IS NULL  ORDER BY pn  ASC LIMIT 300");
while($rowFamilias=mysqli_fetch_array($buscarFamilias)){

    $familia="";
    $subFamilia=0;
    $pn_routing=$rowFamilias['pn'];
    $revisarCircuitos=mysqli_query($con,"SELECT cons,terminal2,terminal1 FROM listascorte WHERE pn='$pn_routing' ");
    $cantidad=mysqli_num_rows($revisarCircuitos);
   
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
    while($rowCircuitos=mysqli_fetch_array($revisarCircuitos)){
        $terminal2=$rowCircuitos['terminal2'];
        $terminal1=$rowCircuitos['terminal1'];
        $cons=$rowCircuitos['cons'];
       if(strpos($cons,"CORTE")){
            $subFamilia=5;
       }else if(strpos($cons,"T") || strpos($terminal2,"SOLDAR") || strpos($terminal1,"SOLDAR")){
            if($subFamilia<4){
                $subFamilia=4;
            }
       }else if(strpos($terminal2,"Empalme") || strpos($terminal1,"Empalme")){
            if($subFamilia<3){
                $subFamilia=3;
            }
       }else if(strpos($terminal2,"SELLO") || strpos($terminal1,"SELLO")){   
            if($subFamilia<2){
                $subFamilia=2;
            }
       }else {
            if($subFamilia<1){
                $subFamilia=1;
            }
       }
            

    }


    $updateFamilia=mysqli_query($con,"UPDATE familias SET familia_circuitos='$familia', cantidad_circuitos='$cantidad', subfamilia='$subFamilia' WHERE pn='$pn_routing'");
    
}
$buscarfaltanres= mysqli_query($con,"SELECT pn FROM familias WHERE familia_circuitos IS NULL  ORDER BY pn  ASC");
if(mysqli_num_rows($buscarfaltanres)>0){
    echo "Faltan por procesar: ".mysqli_num_rows($buscarfaltanres);
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
        <button onclick="window.location.href='prosesos.php'">Ver Cantidades de procesos</button>
</body>
</html>