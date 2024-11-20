<?php
require "../app/conection.php";
$delCore=mysqli_query($con,"DELETE FROM corte ");
if($delCore){
    echo "<h1>Se Eliminaron Correctamente</h1>";
$buscar=mysqli_query($con,"SELECT * FROM registro WHERE count<4");
while($row=mysqli_fetch_array($buscar)){
    $pn=$row['NumPart'];
    $client=$row['cliente'];
    $wo=$row['wo'];
    $cuantos=$row['Qty'];
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
        $rev=$rowList['rev'];
        $conector=$rowList['conector'];
      
       if(substr($cons,0,5)=="C"){
        $cons=str_replace([".","-"," "],"",$cons);
        $codigo=substr($wo,2).substr($cons,5);

        }else{ $codigo=substr($wo,2).$cons;}
   
    $insertar=mysqli_query($con,"INSERT INTO corte (`np`, `cliente`, `rev`, `wo`, `cons`, `color`, `tipo`, `aws`, `codigo`, `term1`, `term2`, `dataFrom`, `dataTo`, `qty`, `tamano`, `conector` ) VALUES ('$pn','$client','$rev','$wo','$cons','$color','$tipo','$aws','$codigo','$term1','$term2','$dataForm','$dataTo','$cuantos','$tamano','$conector')");
}}
if($insertar){
    echo "<br><br><h1>Se Agregaron correctamente Correctamente</h1>";
    header("location:busqueda.php");
}

}