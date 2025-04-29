<?php
require '../../app/conection.php';
$datosResp= array();
$responsable=array();
$resp=mysqli_query($con, "SELECT DISTINCT resposible FROM workschedule ");
while($row=mysqli_fetch_array($resp)){
    $responsable[$row['resposible']]=$row['resposible'];
}
$respCust=mysqli_query($con, "SELECT DISTINCT customer FROM workschedule ");
while($row=mysqli_fetch_array($respCust)){
    $datosResp[$row['customer']]=$row['customer'];
}

?>
