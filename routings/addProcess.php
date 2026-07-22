<?php
require "app/conection.php";


    
$routings = mysqli_query($con,"SELECT * FROM routing_process ORDER BY routingNumber DESC");

if(isset($_GET['descripcionRuteo']) and isset($_GET['qtyTimes']) and isset($_GET['timeProcess']) and isset($_GET['pn'])){
    $descripcionRuteo=$_GET['descripcionRuteo']?$_GET['descripcionRuteo']:"";
    $qtyTimes=$_GET['qtyTimes']?$_GET['qtyTimes']:"";
    $timeProcess=$_GET['timeProcess']?$_GET['timeProcess']:"";
    $verificar = mysqli_query($con,"SELECT * FROM routing_process WHERE routingDescription = '$descripcionRuteo' limit 1 ");
        $updateAss=mysqli_fetch_assoc($verificar);
        $possibleAsset=$updateAss['posibleAssets'];
        $numberRoutingUpdate=$updateAss['routingNumber'];
        $pn=$_GET['pn'];
       
    $sql=mysqli_query($con,"INSERT INTO routing_models ( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
    VALUES ('$pn','$numberRoutingUpdate','$possibleAsset','$descripcionRuteo','$qtyTimes','$timeProcess','300')");
    header("Location: addProcess.php?pn=$pn");
}

?>