<?php
require "../app/conection.php";

$buscar=mysqli_query($con,"SELECT * FROM registro WHERE count<'3'");
while($row=mysqli_fetch_array($buscar)){
    $pn=$row['NumPart'];
    $wo=$row['wo'];
    $cuantos=$row['Qty'];
    $insertar=mysqli_query($con,"INSERT INTO kits (`numeroParte`, `qty`, `wo`) VALUES ('$pn','$cuantos','$wo')");
}
