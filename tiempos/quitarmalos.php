<?php 
require "../app/conection.php";


$times = mysqli_query($con, "SELECT * FROM tiempos JOIN  registro ON registro.info = tiempos.info");
foreach ($times as $time) {
    $id = $time['info'];
    $liberacion = $time['liberacion'];
    $corte = $time['corte'];
    $ensamble = $time['ensamble'];
    $loom = $time['loom'];
    $calidad = $time['calidad'];
    $embarqui = $time['embarque'];

   // echo $id." - " . $corte ." - ". $liberacion." - ". $ensamble." - ". $loom." - ". $calidad. "<br>";

   $datosparcial=mysqli_query($con,"SELECT  `pn`, `wo`, `orgQty`, `cortPar`, `libePar`, `ensaPar`, `preCalidad`, `loomPar`,
    `testPar`, `embPar`, `eng`, `codeBar`, `fallasCalidad` FROM `registroparcial` Where `codeBar` = '$id'");
    foreach ($datosparcial as $dato) {
        $pn = $dato['pn'];
        $wo = $dato['wo'];
        $orgQty = $dato['orgQty'];
        $cortPar = $dato['cortPar'];
        $libePar = $dato['libePar'];
        $ensaPar = $dato['ensaPar'];
        $preCalidad = $dato['preCalidad'];
        $loomPar = $dato['loomPar'];
        $testPar = $dato['testPar'];
        $embPar = $dato['embPar'];
        $eng = $dato['eng'];
        $codeBar = $dato['codeBar'];
        $fallasCalidad = $dato['fallasCalidad'];
       
    if($embPar>0){
        $i=5;
        $update=mysqli_query($con,"UPDATE `tiempos` SET `embarque`='' WHERE `info`='$id'");
    }   
    else if($testPar>0){
        $i=4;
        $update=mysqli_query($con,"UPDATE `tiempos` SET `calidad`='',`embarque`='' WHERE `info`='$id'");
    }   
    else if($loomPar>0){
        $i=3;
        $update=mysqli_query($con,"UPDATE `tiempos` SET `calidad`='',`embarque`='', `loom`='' WHERE `info`='$id'");
    }   
    else if($ensaPar>0){
        $i=2;
        $update=mysqli_query($con,"UPDATE `tiempos` SET `calidad`='',`embarque`='', `loom`='', `ensamble`='' WHERE `info`='$id'");
    }   
    else if($libePar>0 or $cortPar>0){
        $update=mysqli_query($con,"UPDATE `tiempos` SET `calidad`='',`embarque`='', `loom`='', `ensamble`='', `liberacion`='' WHERE `info`='$id'");
        $i=1;
    }
     echo $id." - " . $liberacion." - ". $ensamble." - ". $loom." - ". $calidad." - ". $embarqui. "<br>";
 echo "cuenta: " . $i . "<br><br>";

    
    




}




}