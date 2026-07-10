<?php 
 require "../app/conection.php";
 date_default_timezone_set("America/Mexico_City");

$datosregistro=mysqli_query($con,"SELECT NumPart,Qty,wo,info,count FROM registro WHERE count NOT IN ('20','12') ");
while($row=mysqli_fetch_array($datosregistro)){
    $NumPart=$row['NumPart'];
    $Qty=$row['Qty'];
    $wo=$row['wo'];
    $info=$row['info'];
    $count=$row['count'];
    $buscarcSiExiste=mysqli_query($con,"SELECT * FROM registroparcial WHERE pn='$NumPart' AND wo='$wo' AND codeBar='$info'");
    if(mysqli_num_rows($buscarcSiExiste)>0){
        continue;
    }
    echo $NumPart." ".$Qty." ".$wo." ".$info." ".$count."<br>";
    switch($count){
        case '1':
            echo $NumPart." ".$Qty." ".$wo." ".$info." Planeacion  <br>";
    //$update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`cortPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$info')");
   case '2':
    echo $NumPart." ".$Qty." ".$wo." ".$info." Cutting  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`cortPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '3':
    echo $NumPart." ".$Qty." ".$wo." ".$info." Planeacion  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`cortPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '4':
    echo $NumPart." ".$Qty." ".$wo." ".$info." Planeacion  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`libePar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '5':
    echo $NumPart." ".$Qty." ".$wo." ".$info." Planeacion  <br>";
$update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`libePar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '6':
    echo $NumPart." ".$Qty." ".$wo." ".$info." Planeacion  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`ensaPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '7':
    echo $NumPart." ".$Qty." ".$wo." ".$info." Planeacion  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`ensaPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '8':
    echo $NumPart." ".$Qty." ".$wo." ".$info." Planeacion  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`loomPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '9':
    echo $NumPart." ".$Qty." ".$wo." ".$info." Planeacion  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`loomPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '10':
    echo $NumPart." ".$Qty." ".$wo." ".$info." Planeacion  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`testPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '11':
    echo $NumPart." ".$Qty." ".$wo." ".$info." Planeacion  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`testPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '12':
    echo $NumPart." ".$Qty." ".$wo." ".$info." Planeacion  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`embPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '17':
    echo $NumPart." ".$Qty." ".$wo." ".$info." Cutting  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`cortPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '16':
    echo $NumPart." ".$Qty." ".$wo." ".$info." terminals  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`libePar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '13':
    echo $NumPart." ".$Qty." ".$wo." ".$info." assembly  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`ensaPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '14':
    echo $NumPart." ".$Qty." ".$wo." ".$info." loom  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`loomPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
case '18':
    echo $NumPart." ".$Qty." ".$wo." ".$info." test  <br>";
 $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`testPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
    break;
    case '15':
        echo $NumPart." ".$Qty." ".$wo." ".$info." assembly  <br>";
     $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`,`ensaPar`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$Qty','$info')");    
        break;

    }
}

