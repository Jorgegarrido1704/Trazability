<?php
require "../app/conection.php";

//Conection de tiempo de ruteo. 


$registrosMPS = mysqli_query($con,"SELECT DISTINCT pn FROM `tiemposderuteo` ORDER BY pn ASC");

echo "<table border='1' cellpadding='5' cellspacing='0' align='center' style='width: 60%;'>";
echo "<thead>";
//echo "<tr><th>PN</th><th>Work</th><th>Process Time (hrs)</th><th>Setup Time (hrs)</th></tr>";
echo "<tr><th>Numero de parte</th>
<th>Corte</th><th>Corte Set Up</th>
<th>Liberacion</th><th>Liberacion Set Up</th>
<th>Ensamble</th><th>Ensamble Set Up</th>
<th>Loom</th><th>Loom Set Up</th>
<th>Calidad</th><th>Calidad Set Up</th>
<th>Embarque</th><th>Embarque Set Up</th></tr>";
echo "</thead>";
echo "<tbody>";    
while ($row = mysqli_fetch_assoc($registrosMPS)) {
     $pn = $row['pn'];
    $datos_de_trabajo= mysqli_query($con,"SELECT   processtime, setupTime FROM `tiemposderuteo` WHERE pn = '$pn' ORDER BY id ASC");
    $pn = $row['pn'];
      echo "<tr><td>{$pn}</td>";
    while ($row2 = mysqli_fetch_assoc($datos_de_trabajo)) {
      
         $processtime = $row2['processtime'];
         $setupTime = $row2['setupTime'];
    echo "<td style='font-weight:bold;'>{$processtime}</td><td style='font-weight:bold;'>{$setupTime}</td>";
    }
   echo "</tr>";

}
echo "</tbody>";
echo "</table>";