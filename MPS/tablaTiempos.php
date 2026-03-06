<?php
require "../app/conection.php";

$timeProcess = mysqli_query($con, "SELECT * FROM tiemposderuteo  ORDER BY pn,id ASC");
echo "<button><a href='Tiempos60porciento.php'>60 %</a></button>";
echo "<button><a href='Tiempos90porciento.php'> 75%</a></button>";
echo "<h3>Production time per day in hours (55% efficiency)</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0' align='center' style='width: 75%;'>";
echo "<thead><tr><th rowspan='2'>PN</th><th rowspan='2'>Work</th><th rowspan='2'>Time per harness in hours</th><th rowspan='2'>Fatige 10%</th><th rowspan='2'>Setup</th><th rowspan='2'>Total</th></tr></thead><tbody>";

    while ($row = mysqli_fetch_assoc($timeProcess)) {
                $pn = $row['pn'];
                $work = $row['work'];
                $time = $row['processtime'];
                $fatiga = $time*0.1;
                $setup = $row['setupTime'];
                $total = $time + $setup+$fatiga;
                echo "<tr><td>{$pn}</td><td>{$work}</td><td>{$time}</td><td>{$fatiga}</td><td>{$setup}</td><td>{$total}</td></tr>";
    }

echo "</tbody>";
echo "</table>";
?>