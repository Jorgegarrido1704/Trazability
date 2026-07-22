<?php
require "../app/conection.php";

$pn=isset($_GET['routingNumber']) ? $_GET['routingNumber'] : '';
$wd= isset($_GET['descripcionRuteo']) ? $_GET['descripcionRuteo'] : '';
$ps= isset($_GET['posiblesAssets']) ? $_GET['posiblesAssets'] : '';
$rn= isset($_GET['routing_number']) ? $_GET['routing_number'] : '';
$qt= isset($_GET['qtyTimes']) ? $_GET['qtyTimes'] : '';
$tp= isset($_GET['timeProcess']) ? $_GET['timeProcess'] : '';



    $Verificar = mysqli_query($con,"SELECT * FROM routing_models JOIN routing_process ON routing_models.id_process = routing_process.id WHERE pn_routing = '$pn'  limit 1 ");
    if(mysqli_num_rows($Verificar)>0){
        return json_encode(['status' => 'error', 'message' => 'Data not inserted']);
    }
        $todarId=mysqli_query($con,"SELECT id FROM routing_process WHERE routingNumber='$rn' AND  routingDescription='$wd' AND  posibleAssets='$ps'"); 
       while($row=mysqli_fetch_array($todarId)){
           $id=$row['id'];
       }
       
    
        $sql=mysqli_query($con,"INSERT INTO routing_models ( `pn_routing`,  `QtyTimes`, `timePerProcess`, `setUp_routing`, `id_process`) 
        VALUES ('$pn','$qt','$tp','300','$id')");
    
    echo json_encode([ 'status' => 'success', 'message' => 'Data inserted successfully']);
    

?>