<?php
    include "../app/conection.php";
    session_start();

$buscar= mysqli_query($con,"SELECT * FROM mant_golpes_diarios ");
while($row=mysqli_fetch_array($buscar)){
    $id=$row['id'];
    $fecha=$row['fecha_reg'];
    $fecha=str_replace("/","-",$fecha);
    
    mysqli_query($con,"UPDATE mant_golpes_diarios SET fecha_reg='$fecha' where id='$id'");
    
}    
