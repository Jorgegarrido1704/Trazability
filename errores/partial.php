<?php 
 require "../app/conection.php";
 date_default_timezone_set("America/Mexico_City");

$datosregistro=mysqli_query($con,"SELECT NumPart,Qty,wo,info FROM registro ");
while($row=mysqli_fetch_array($datosregistro)){
    $NumPart=$row['NumPart'];
    $Qty=$row['Qty'];
    $wo=$row['wo'];
    $info=$row['info'];
    echo $NumPart." ".$Qty." ".$wo." ".$info."<br>";
    $update=mysqli_query($con,"INSERT INTO registroparcial(`pn`, `wo`, `orgQty`, `codeBar`) VALUES('$NumPart','$wo','$Qty','$info')");
}

