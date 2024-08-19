<?php
require "../app/conection.php";

$buscar=mysqli_query($con,"SELECT * FROM regsitrocalidad ");
while($row=mysqli_fetch_array($buscar)){
 $ids=$row['id'];   
$fecha=$row['fecha'];
$cambio=str_replace('/','-',$fecha);
$up=mysqli_query($con,"UPDATE regsitrocalidad SET fecha='$cambio' WHERE id='$ids'");
}
