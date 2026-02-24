<?php
require "../../app/conection.php";

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

echo "<button><a href='../Tiempos90porciento.php'>BACK</a></button>";
echo "<button><a href='corte.php'> Cutting</a></button>";
echo "<button><a href='liberacion.php'> Terminals</a></button>";
echo "<button><a href='ensamble.php'> Assembly</a></button>";
echo "<button><a href='loom.php'> lomming</a></button>";
echo "<button><a href='calidad.php'> Quality</a></button>";
echo "<button><a href='embarque.php'>Packing</a></button>";
echo "<h3>Production time per day in hours (75% efficiency)</h3>";
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
    

    $timeProcess = mysqli_query($con, "SELECT * FROM tiemposderuteo WHERE pn = '$pn' and work = 'Terminals' ORDER BY id ASC ");

    while ($row = mysqli_fetch_assoc($timeProcess)) {

        echo "<tr><td>{$row['pn']} - {$row['work']}</td>";
        $rowTotal = 0;

        foreach ($allWeeks as $week => $_) {

            $value = $weeks[$week] ?? 0;
            $times = 0;

            if ($value > 0) {
              //  echo "<td>{$value} - {$row['processtime']} - {$row['setupTime']}</td>";
                $times = (($row['processtime'] * $value) + $row['setupTime'])*1.15;
                $perDay = $times / 5;
                $perDay = round($perDay/60, 2);
                for ($i = 0; $i < 5; $i++) {
                    if (!isset($totalsPerDay[$week])) {
                        $totalsPerDay[$week] = 0;
                    }
                    echo "<td>{$perDay}</td>";
                }
                $totalsPerDay[$week] += $times;
            } else {
                for ($i = 0; $i < 5; $i++) {
                    echo "<td>0</td>";
                }
            }
            $rowTotal += round($times/60,2);
        }
        echo "<td style='font-weight:bold;'>{$rowTotal}</td>";
        echo "</tr>";
    }
}

// Fila TOTAL
echo "<tr style='font-weight:bold; background:#f0f0f0;'><td>TOTAL</td>";

foreach ($allWeeks as $week => $_) {
    for ($i = 0; $i < 5; $i++) {
        $times = $totalsPerDay[$week]/5 ?? 0;
        $times = round($times/60, 2);
        echo "<td>{$times}</td>";
    }
}
echo "</tr>";
echo "</table>";
?>
