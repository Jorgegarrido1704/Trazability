<?php
require "../app/conectionTraza.php";

$currentWeek = date("W");
$pnRegistros = [];
$allWeeks = [];

// Obtener registros MPS
$registrosMPS = mysqli_query($con, "SELECT pn, dq, qtymps FROM `datos_mps` WHERE pn='1001489409'OR pn='1001488939'OR pn='660925'OR pn='1002707335'OR pn='1001455147'OR pn='1003318064'OR pn='B222992'OR pn='1000109371'OR pn='16517630'OR pn='1003312301'OR pn='1000473129'OR pn='1002835774'OR pn='16516661'OR pn='1002835044'OR pn='1003544214'OR pn='660320'OR pn='16516612'OR pn='1000516139'OR pn='1001774292'OR pn='16517623'OR pn='16514775'OR pn='16514514'OR pn='1000230326'OR pn='1000312635'OR pn='1002719292'OR pn='16518485'OR pn='16518486'OR pn='1003359943'OR pn='1002186052'OR pn='1001073962' ");
while ($row = mysqli_fetch_assoc($registrosMPS)) {
    $pn = $row['pn'];
    $week = date("W", strtotime($row['dq']));
    $qty = (int)$row['qtymps'];

   // if ($week < $currentWeek) $week += 52;

    $allWeeks[$week] = true;
    if (!isset($pnRegistros[$pn][$week])) $pnRegistros[$pn][$week] = 0;
    $pnRegistros[$pn][$week] += $qty;
}

ksort($allWeeks, SORT_NUMERIC);

// Totales por PN
$rowTotals = [];
foreach ($pnRegistros as $pn => $weeks) $rowTotals[$pn] = array_sum($weeks);

// Ordenar PN por total
uksort($pnRegistros, function($a,$b) use($rowTotals) {
    return $rowTotals[$b] <=> $rowTotals[$a];
});

echo "<button><a href='registos.php'>Back</a></button>";
echo "<h3>Production time per day in hours (80% efficiency)</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0' align='center' style='width:100%; border-collapse:collapse; text-align:center;'>";

// Header semanas
echo "<tr><th rowspan='2'>PN - Process</th>";
foreach ($allWeeks as $week => $_) echo "<th colspan='5' style='background:" . (($week % 2 == 0) ? "#e6f7ff" : "#ffe6e6") . "'>W{$week}</th>";
echo "<th rowspan='2'>Total</th></tr>";

// Header días
$dias = ["Mon","Tue","Wed","Thu","Fri"];
echo "<tr>";
foreach ($allWeeks as $week => $_) foreach ($dias as $d) echo "<th>$d</th>";
echo "</tr>";

// Procesos base
$procesosBase = ['Cutting'=>0,'Terminals'=>0,'Assembly'=>0,'Quality'=>0,'Packaging'=>0];

// Totales por proceso y por día
$totalsPerProcess = [];
$totalsPerDay = []; // array para total por día
foreach ($procesosBase as $p => $_) {
    foreach ($allWeeks as $week => $_) {
        for($i=0;$i<5;$i++) $totalsPerDay[$week][$i] = 0;
        $totalsPerProcess[$p][$week] = 0;
    }
    $totalsPerProcess[$p]['total'] = 0;
}

// Recorrer PNs
foreach ($pnRegistros as $pn => $weeks) {
    $procesos = $procesosBase;
    $assetsProcess = $procesosBase;

    // Obtener tiempos de ruteo
    $timeProcess = mysqli_query($con, "SELECT `work_routing`, QtyTimes, timePerProcess, setUp_routing 
                                       FROM `routing_models` 
                                       WHERE pn_routing = '$pn'");
    while ($row = mysqli_fetch_assoc($timeProcess)) {
        if ($row['work_routing'] > 10000 && $row['work_routing'] < 10061) {$procesos['Cutting'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Cutting']++;}
        if ($row['work_routing'] > 10060 && $row['work_routing'] < 10441) {$procesos['Terminals'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Terminals']++;}
        if ($row['work_routing'] > 10440 && $row['work_routing'] < 10999) {$procesos['Assembly'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Assembly']++;}
        if ($row['work_routing'] > 11500 && $row['work_routing'] < 11700) {$procesos['Quality'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Quality']++;}
        if ($row['work_routing'] > 11700 && $row['work_routing'] < 12000) {$procesos['Packaging'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Packaging']++;}
    }

    foreach ($procesos as $key => $valor) {
        echo "<tr><td>{$pn} - {$key}</td>";
        $rowTotal = 0;

        foreach ($allWeeks as $week => $_) {
            $value = isset($weeks[$week]) ? $weeks[$week] : 0;

            if ($value == 0) {
                for ($i=0;$i<5;$i++) echo "<td>0</td>";
                $times = 0;
            } else {
                $times = (($valor*$value)*1.20) + ($assetsProcess[$key]*300);
                $perDay = $times / 5;
                for ($i=0;$i<5;$i++) {
                    $hours = floor($perDay/3600);
                    $min = round(($perDay%3600)/60);
                    $qtyItems = ($hours<1 && $min<1)?"0":"{$hours} h : {$min} m";
                    echo "<td>{$qtyItems}</td>";
                    $totalsPerDay[$week][$i] += $perDay;
                }
            }
            $totalsPerProcess[$key][$week] += $times;
            $rowTotal += $times;
        }

        $rowH = floor($rowTotal/3600);
        $rowM = round(($rowTotal%3600)/60);
        $rowText = ($rowH<1 && $rowM<1)?"0":"{$rowH} h : {$rowM} m";
        echo "<td style='font-weight:bold;'>{$rowText}</td>";
        echo "</tr>";

        $totalsPerProcess[$key]['total'] += $rowTotal;
    }
}

// Fila de totales por día
echo "<tr style='font-weight:bold; background:#f0f0f0;'><td>DAILY TOTAL</td>";
foreach ($allWeeks as $week => $_) {
    for ($i=0;$i<5;$i++) {
        $times = $totalsPerDay[$week][$i];
        $hours = floor($times/3600);
        $min = round(($times%3600)/60);
        $cell = ($hours<1 && $min<1)?"0":"{$hours} h : {$min} m";
        echo "<td>{$cell}</td>";
    }
}
$grandTotal = array_sum(array_map('array_sum', $totalsPerDay));
$gH = floor($grandTotal/3600);
$gM = round(($grandTotal%3600)/60);
$gCell = ($gH<1 && $gM<1)?"0":"{$gH} h : {$gM} m";
echo "<td style='font-weight:bold;'>{$gCell}</td>";
echo "</tr>";

echo "</table>";
?>
