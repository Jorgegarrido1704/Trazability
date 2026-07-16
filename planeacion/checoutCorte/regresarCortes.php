<?php 

require 'app/conection.php';



$selectcortes= mysqli_query($con, "SELECT cc.id_corte,cc.maquina FROM carga_congelada cc JOIN corte c ON cc.id_corte = c.id WHERE c.cutStatus = 'Activo'"); 

while($row = mysqli_fetch_array($selectcortes)){
    $id_corte = $row['id_corte'];
    $maq_asignada = $row['maquina'];
echo $id_corte."-".$maq_asignada."-<br>";
  $update = mysqli_query($con, "UPDATE corte SET maq_asignada = '$maq_asignada' WHERE id = '$id_corte'");
}