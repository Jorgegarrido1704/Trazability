<?php
require "../app/conection.php";

$currentWeek = date("W");
$pnRegistros = [];
$allWeeks = [];
$totalsPerDay = [];

// Obtener registros MPS
$registrosMPS = mysqli_query($con, "SELECT pn, dq, qtymps FROM datos_mps");

while ($row = mysqli_fetch_assoc($registrosMPS)) {
    $pn = $row['pn'];
    //echo $pn;
    $week = date("W", strtotime($row['dq']));
    
   // echo $week. " || ".$row['dq']. "<br>";
    $qty = (int)$row['qtymps'];

    if ($week < $currentWeek) $week = $currentWeek;
    $allWeeks[$week] = true;

    if (!isset($pnRegistros[$pn][$week])) {
        $pnRegistros[$pn][$week] = 0;
    }

    $pnRegistros[$pn][$week] += $qty;
}

ksort($allWeeks, SORT_NUMERIC);

// Totales por PN
$rowTotals = [];
foreach ($pnRegistros as $pn => $weeks) {
    $rowTotals[$pn] = array_sum($weeks);
}

// Ordenar PN por total descendente
uksort($pnRegistros, function($a, $b) use ($rowTotals) {
    return $rowTotals[$b] <=> $rowTotals[$a];
});

echo "<h3>MPS</h3>";

echo "<table border='1' cellpadding='5' cellspacing='0' align='center' style='width:100%; border-collapse:collapse; text-align:center;'>";

// Header semanas
echo "<tr><th rowspan='2'>PN - Process</th>";
foreach ($allWeeks as $week => $_) {
    $color = ($week % 2 == 0) ? "#e6f7ff" : "#ffe6e6";
    echo "<th colspan='5' style='background:$color'>W{$week}</th>";
}
echo "<th rowspan='2'>Total</th></tr>";

// Header días
$dias = ["Mon","Tue","Wed","Thu","Fri"];
echo "<tr>";
foreach ($allWeeks as $week => $_) {
    foreach ($dias as $d) {
        echo "<th>$d</th>";
    }
}
echo "</tr>";

// Recorrer PNs
foreach ($pnRegistros as $pn => $weeks) {
    

    $timeProcess = mysqli_query($con, "SELECT COUNT(*) as totalArness FROM listascorte WHERE pn = '$pn' AND tamano > 0 ORDER BY id ASC ");
    if(mysqli_num_rows($timeProcess) == 0) continue;
    $row = mysqli_fetch_assoc($timeProcess);
    
   
       

       
        $rowTotal = 0;

        foreach ($allWeeks as $week => $_) {

            $value = $weeks[$week] ?? 0;
            $times = 0;

            if ($value > 0) {
                 echo "<tr><td>".$pn."- piezas ".$value."</td>";
                $totalTiempo = $row['totalArness']*2.9*$value;
                $setUptime=180*$row['totalArness'];
                $tiempoTotal = $totalTiempo + $setUptime;
             
                $times = ($tiempoTotal);
                $perDay = $times / 5;
                $perDay = round($perDay/60, 2);
                for ($i = 0; $i < 5; $i++) {
                    if (!isset($totalsPerDay[$week])) {
                        $totalsPerDay[$week] = 0;
                    }
                    echo "<td>{$perDay}</td>";
                }
                $totalsPerDay[$week] += $times;
            }
            $rowTotal += round($times/60,2);
        }
        echo "<td style='font-weight:bold;'>{$rowTotal}</td>";
        echo "</tr>";
    }


// Fila TOTAL
echo "<tr style='font-weight:bold; background:#f0f0f0;'><td>TOTAL</td>";


echo "</tr>";
echo "</table>";
?>
