<?php

require "../app/conection.php";
try {
 $registrosInvnetarios=mysqli_query($con,"SELECT `id_item`, `first_qty_count`, `second_qty_count` FROM `inventarioGlobal` WHERE `second_qty_count`>0 AND `first_qty_count`>0"); 

while ($row = mysqli_fetch_array($registrosInvnetarios)) {
$primer=$row['first_qty_count'];
$segundo=$row['second_qty_count'];
$item= $row['id_item'];
$differencia=abs($segundo-$primer);
    $update="UPDATE `inventarioGlobal` SET `difference`='$differencia' WHERE `id_item`='$item'";
echo $differencia."<br>";
    
} 
   // header("Location: ../corte/busqueda.php");

}catch (Exception $e) {
    echo $e->getMessage();
}