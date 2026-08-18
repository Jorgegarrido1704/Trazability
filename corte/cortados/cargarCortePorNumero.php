<?php
require "../../app/conection.php";
try {

//$pn= isset($_POST['np']) ? $_POST['np'] : '';




$buscar=mysqli_query($con,"SELECT * FROM registro WHERE count IN ('2','3','17','1')  ORDER BY count ASC");
//$buscar=mysqli_query($con,"SELECT * FROM registro WHERE count IN ('2','3','17','1') AND wo = '$pn'  ORDER BY count ASC");
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
        $strip1=$rowList['strip1'];
        $term2=$rowList['terminal2'];
        $strip2=$rowList['strip2'];
        $dataForm=$rowList['dataFrom'];
        $dataTo=$rowList['dataTo'];
       $likeConsu = "Cutting cons {$cons} //%";
        $conector=$rowList['conector'];
        $tinta=$rowList['colorTinta'];
        $distEstamp= $rowList['dist_stamp'];
    $tiempo = round(($rowList['defaultTime'] * $cuantos),2);
    $codigo=$wo."-".$cons;

      
       if(substr($cons,0,5)=="C"){
        $cons=str_replace([".","-"," "],"",$cons);
        $codigo=$wo."-".substr($cons,5);

        }else{ $codigo=$wo."-".$cons;}
    $buscarDuplicado=mysqli_query($con,"SELECT * FROM corte WHERE wo='$wo' AND cons='$cons' AND cutStatus='Activo'");
    if(mysqli_num_rows($buscarDuplicado)==0){
        $insertar=mysqli_query($con,"INSERT INTO corte (`np`, `cliente`, `rev`, `wo`, `cons`, `color`, `tipo`, `aws`, `codigo`, `term1`,`strip1`, `term2`,`strip2`, `dataFrom`, `dataTo`, `qty`, `tamano`, `conector`,`tintaColor`,`time_ruteo`,`dist_stamp` ) VALUES ('$pn','$client','$rev','$wo','$cons','$color','$tipo','$aws','$codigo','$term1','$strip1','$term2','$strip2','$dataForm','$dataTo','$cuantos','$tamano','$conector','$tinta','$tiempo','$distEstamp')");
    }else{
        $update= mysqli_query($con,"UPDATE corte SET
       `color`='$color',`tipo`='$tipo',`aws`='$aws',`term1`='$term1',`strip1`='$strip1',`term2`='$term2',`strip2`='$strip2',`dataFrom`='$dataForm',`dataTo`='$dataTo',`tamano`='$tamano',`conector`='$conector'
         WHERE cons='$cons' AND wo='$wo' AND cutStatus='Activo'");
        
    }
    //$insertar=mysqli_query($con,"INSERT INTO corte (`np`, `cliente`, `rev`, `wo`, `cons`, `color`, `tipo`, `aws`, `codigo`, `term1`,`strip1`, `term2`,`strip2`, `dataFrom`, `dataTo`, `qty`, `tamano`, `conector`,`tintaColor`,`time_ruteo`,`dist_stamp` )('$pn','$client','$rev','$wo','$cons','$color','$tipo','$aws','$codigo','$term1','$strip1','$term2','$strip2','$dataForm','$dataTo','$cuantos','$tamano','$conector','$tinta','$tiempo','$distEstamp')");
}}

    //echo "<br><br><h1>Se Agregaron correctamente Correctamente</h1>";
    header("location:../busqueda.php");


} catch (\Throwable $th) {
    echo $th;
}