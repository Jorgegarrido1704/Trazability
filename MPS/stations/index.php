<?php
require "../../app/conection.php";

$fecha_seleccionada = isset($_POST['fecha_seleccionas'])? $_POST['fecha_seleccionas']:"";

if($fecha_seleccionada != ""){

// Obtener registros MPS
$registrosMPS = mysqli_query($con, "SELECT dm.pn, dm.qtymps, tr.work, tr.processtime, tr.setupTime 
                    FROM datos_mps dm JOIN tiemposderuteo tr ON dm.pn = tr.pn 
                    where dm.dq = '$fecha_seleccionada' ORDER BY tr.work ");

while ($row = mysqli_fetch_assoc($registrosMPS)) {
    
    $pn =$row['pn'];
    $qty = $row['qtymps'];
    $work = $row['work'];
    $time_process = $row['processtime'];
    $set_up = $row['setupTime'];

echo $pn." ".$qty." ".$work." ".$fecha_seleccionada;
    
}




}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form id="setUptime" method="POST">
        <input type="date" name="fecha_seleccionas" id="fecha_seleccionas" onChange=alerta(this.value)>
        <input type="submit" value ="seleccionar">
</form>    
</body>
</html>
<script>
    function alerta(valor){
        alert(valor);
    }
    </script>