<?php
require "../app/conection.php";
try {
    

$delCore=mysqli_query($con,"DELETE FROM corte ");
} catch (\Throwable $th) {
    echo $th;
}
try {
    

   // echo "<h1>Se Eliminaron Correctamente</h1>";
$buscar=mysqli_query($con,"SELECT * FROM registro WHERE count<4");
while($row=mysqli_fetch_array($buscar)){
    $pn=$row['NumPart'];
    $client=$row['cliente'];
    $wo=$row['wo'];
    $cuantos=$row['Qty'];
    $rev1=$row['rev'];
    if(substr($rev1,0,4)=="PPAP" or substr($rev1,0,4)=="PRIM"){
        $rev=substr($rev1,5);
    }else { $rev=$rev1;}
$selectlist=mysqli_query($con,"SELECT * FROM listascorte WHERE pn='$pn' AND rev='$rev' AND cons != '' ");
    while($rowList=mysqli_fetch_array($selectlist)){
        $cons=$rowList['cons'];
        $tipo=$rowList['tipo'];
        $aws=$rowList['aws'];
        $tamano=doubleval($rowList['tamano']);//$rowList['tamano'];
        $color=$rowList['color'];
        $term1=$rowList['terminal1'];
        $term2=$rowList['terminal2'];
        $dataForm=$rowList['dataFrom'];
        $dataTo=$rowList['dataTo'];
       $likeConsu = "Cutting cons {$cons} //%";
        $conector=$rowList['conector'];
    $stmtTiempo = mysqli_query($con, "SELECT timePerProcess FROM routing_models WHERE pn_routing = '$pn' AND work_routing = '10001' AND work_description LIKE '$likeConsu' LIMIT 1");
    $stmtColor  = mysqli_query($con, "SELECT tintaOrg FROM coloresencables WHERE eng_short_color = '$color' OR eng_color = '$color' OR spn_color = '$color' LIMIT 1");
    $rowTiempo = mysqli_fetch_array($stmtTiempo);
    $rowColor  = mysqli_fetch_array($stmtColor);
    $tinta= $rowColor['tintaOrg'];
    $tiempo = round(($rowTiempo['timePerProcess'] * $cuantos),2);
    $codigo=substr($wo,2).$cons;

      
       if(substr($cons,0,5)=="C"){
        $cons=str_replace([".","-"," "],"",$cons);
        $codigo=substr($wo,2).substr($cons,5);

        }else{ $codigo=substr($wo,2).$cons;}
   
    $insertar=mysqli_query($con,"INSERT INTO corte (`np`, `cliente`, `rev`, `wo`, `cons`, `color`, `tipo`, `aws`, `codigo`, `term1`, `term2`, `dataFrom`, `dataTo`, `qty`, `tamano`, `conector`,`tintaColor`,`time_ruteo` ) VALUES ('$pn','$client','$rev','$wo','$cons','$color','$tipo','$aws','$codigo','$term1','$term2','$dataForm','$dataTo','$cuantos','$tamano','$conector','$tinta','$tiempo')");
}}

    //echo "<br><br><h1>Se Agregaron correctamente Correctamente</h1>";
    header("location:busqueda.php");


} catch (\Throwable $th) {
    echo $th;
}