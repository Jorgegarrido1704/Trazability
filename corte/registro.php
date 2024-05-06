<?php
require "../app/conection.php";

$buscar=mysqli_query($con,"SELECT * FROM registro WHERE wo='007963'");
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
        $color=$rowList['color'];
        $term1=$rowList['terminal1'];
        $term2=$rowList['terminal2'];
        $dataForm=$rowList['dataFrom'];
        $dataTo=$rowList['dataTo'];
      
       if(substr($cons,0,5)=="CORTE"){
        $cons=str_replace([".","-"," "],"",$cons);
        $codigo=substr($wo,2).substr($cons,5);

        }else{ $codigo=substr($wo,2).$cons;}
    echo $pn." ".$client." ".$wo." ".$cons." ".$tipo." ".$aws." ".$color." ".$codigo." ".$term1." ".$term2." ".$dataForm." ".$dataTo." ".$cuantos."<br> ";
    $insertar=mysqli_query($con,"INSERT INTO corte (`np`, `cliente`, `wo`, `cons`, `color`, `tipo`, `aws`, `codigo`, `term1`, `term2`, `dataFrom`, `dataTo`, `qty`) VALUES ('$pn','$client','$wo','$cons','$color','$tipo','$aws','$codigo','$term1','$term2','$dataForm','$dataTo','$cuantos')");
}}