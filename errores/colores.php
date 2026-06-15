<?php 

require '../app/conection.php';

$qry = "SELECT id,color FROM listascorte  ORDER BY color ASC ";

$result = $con->query($qry);
$bulk = [];

while ($row = $result->fetch_array()) {
    $color = $row['color'];
    $id = $row['id'];
    $buscarColor=mysqli_query($con,"SELECT tintaOrg FROM coloresencables WHERE  eng_short_color = '$color' OR
    eng_color = '$color' OR spn_color = '$color' ");
    $colorOrg = mysqli_fetch_array($buscarColor);
    if($colorOrg == null){
         $sqlInsert = "UPDATE `listascorte` SET `colorTinta` = null WHERE `id` = '$id'"; 
    }else{
         $tintaOrg = $colorOrg['tintaOrg'];
    $sqlInsert = "UPDATE `listascorte` SET `colorTinta` = '$tintaOrg' WHERE `id` = '$id'"; 
    $con->query($sqlInsert);
    }
   
   
}