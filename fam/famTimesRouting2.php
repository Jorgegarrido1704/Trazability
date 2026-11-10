<?php
require "../app/conection.php";

//Conection de tiempo de ruteo. 


$registrosMPS = mysqli_query($con,"SELECT DISTINCT pn FROM `tiemposderuteo` ORDER BY pn ASC");

echo "<table border='1' cellpadding='5' cellspacing='0' align='center' style='width: 60%;'>";
echo "<thead>";
//echo "<tr><th>PN</th><th>Work</th><th>Process Time (hrs)</th><th>Setup Time (hrs)</th></tr>";
echo "<tr><th>partNumber</th>
<th>Area</th>
<th>Minutes</th>
</tr>";
echo "</thead>";
echo "<tbody>";    

    $datos_de_trabajo= mysqli_query($con,"SELECT pn,  processtime, work FROM `tiemposderuteo` WHERE  ORDER BY pn ASC");
    while ($row2 = mysqli_fetch_assoc($datos_de_trabajo)) {
        $pn =$row2['pn'];
         $processtime = $row2['processtime'];
         $work = $row2['work'];

          echo "<tr><td>{$pn}</td><td style='font-weight:bold;'>{$work}</td><td style='font-weight:bold;'>{$processtime}</td>";
    
   echo "</tr>";
    }

echo "</tbody>";
echo "</table>";