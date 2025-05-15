<?php
require '../app/conection.php';
$fecha = '04-2025';
$i=0;
$busqueda=mysqli_query($con,"SELECT  DISTINCT np FROM `retiradad` WHERE fecharetiro LIKE '%-$fecha'");
while($row=mysqli_fetch_array($busqueda)){
    $np=$row['np'];
   
    echo $np.'  <br>';+
    $i++;
}
echo 'Total: '.$i;
?>