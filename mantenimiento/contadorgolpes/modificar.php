<?php
    include "../app/conection.php";
    session_start();

$buscar= mysqli_query($con,"SELECT fecha_reg FROM mant_golpes_diarios ORDER BY id DESC");
while($row=mysqli_fetch_array($buscar)){
    $fecha=$row['fecha_reg'];
    echo $fecha.'<br>';
    
    $fecha=str_replace('-','/',$fecha);
    echo $fecha.'<br>';
    
}    
