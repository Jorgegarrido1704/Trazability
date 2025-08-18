<?php 

require "../app/conection.php";

$registrosPPAP = mysqli_query($con, "SELECT fecha, NumPart FROM `registro` WHERE `rev` LIKE 'PPAP%' or `rev` LIKE 'PRIM%'");
while ($row = mysqli_fetch_array($registrosPPAP)) {
    $fechas = $row['fecha'];
    $numPart = $row['NumPart'];
    $fechas = date("Y-m-d", strtotime($fechas));
    echo "<tr>";
    echo "<td>$fechas</td>";
    echo "<td>$numPart</td>";
    echo "</tr><br>";
    mysqli_query($con, "UPDATE `workschedule` SET `UpOrderDate`='$fechas'   WHERE `pn`='$numPart' order by `id` DESC LIMIT 1");
}

