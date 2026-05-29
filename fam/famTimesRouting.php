<?php
require "../app/conection.php";

//Conection de tiempo de ruteo. 


$registrosMPS = mysqli_query($con,"SELECT * FROM `tiemposderuteo` ORDER BY pn ASC");

echo "<table border='1' cellpadding='5' cellspacing='0' align='center' style='width: 60%;'>";
echo "<thead>";
echo "<tr><th>PN</th><th>Work</th><th>Process Time (hrs)</th><th>Setup Time (hrs)</th></tr>";
echo "</thead>";
echo "<tbody>";    
while ($row = mysqli_fetch_assoc($registrosMPS)) {
    $pn = $row['pn'];
    $work = $row['work'];
    $processtime = $row['processtime'];
    $setupTime = $row['setupTime'];

    echo "<tr><td>{$pn}</td><td>{$work}</td><td style='font-weight:bold;'>{$processtime}</td><td style='font-weight:bold;'>{$setupTime}</td></tr>";

}
echo "</tbody>";
echo "</table>";