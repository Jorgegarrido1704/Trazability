<?php
require "../app/conection.php";
$wo='009723';
$pn ="1003826612";



$selectlist=mysqli_query($con,"SELECT * FROM listascorte WHERE pn='$pn'");
    while($rowList=mysqli_fetch_array($selectlist)){
        $cons=$rowList['cons'];
        $tipo=$rowList['tipo'];
        $aws=$rowList['aws'];
        $tamano=$rowList['tamano'];
        $color=$rowList['color'];
        $term1=$rowList['terminal1'];
        $term2=$rowList['terminal2'];
        $dataForm=$rowList['dataFrom'];
        $dataTo=$rowList['dataTo'];
      
       if(substr($cons,0,5)=="CORTE"){
        $cons=str_replace([".","-"," "],"",$cons);
        $codigo=substr($wo,2).substr($cons,5);

        }else{ $codigo=substr($wo,2).$cons;}
   
    $insertar=mysqli_query($con,"INSERT INTO corte (`np`, `cliente`, `wo`, `cons`, `color`, `tipo`, `aws`, `codigo`, `term1`, `term2`, `dataFrom`, `dataTo`, `qty`, `tamano`) VALUES ('$pn','$client','$wo','$cons','$color','$tipo','$aws','$codigo','$term1','$term2','$dataForm','$dataTo','$cuantos','$tamano')");
}
if($insertar){
    echo "<br><br><h1>Se Agregaron correctamente Correctamente</h1>";
    header("location:busqueda.php");
}

