<?php
require "../app/conectionTraza.php";

$currentWeek = date("W");
$pnRegistros = [];
$allWeeks = [];

// Get MPS records
$registrosMPS = mysqli_query($con, "SELECT pn, dq, qtymps FROM `datos_mps`");

while ($row = mysqli_fetch_assoc($registrosMPS)) {
    $pn = $row['pn'];
    $week = date("W", strtotime($row['dq']));
    $qty = (int)$row['qtymps'];

    // Adjust week for next year
    if ($week < $currentWeek) {
        $week += 52;
    }

    // Track all weeks (for header)
    $allWeeks[$week] = true;

    // Initialize if not set
    if (!isset($pnRegistros[$pn][$week])) {
        $pnRegistros[$pn][$week] = 0;
    }

    // Sum quantities
    $pnRegistros[$pn][$week] += $qty;
}

// Sort weeks
ksort($allWeeks, SORT_NUMERIC);

// Calculate totals per part number
$rowTotals = [];
foreach ($pnRegistros as $pn => $weeks) {
    $rowTotals[$pn] = array_sum($weeks);
}

// Sort part numbers by total qty (desc)
uksort($pnRegistros, function ($a, $b) use ($rowTotals) {
    return $rowTotals[$b] <=> $rowTotals[$a];
});

// Table header
echo "<button><a href='registos.php'>Back</a></button>";
echo "<h3>Production time per week in hours (80% efficiency)</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0' align='center' style='width: 100%;'>";

// Header row
echo "<tr><th>PN - Process</th>";
foreach ($allWeeks as $week => $_) {
    echo "<th>Week {$week}</th>";
}
echo "<th>Total</th></tr>";

$procesosBase = ['Cutting' => 0, 'Terminals' => 0, 'Assembly' => 0, 'Quality' => 0, 'Packaging' => 0];

// Initialize totals per process
$totalsPerProcess = [];
foreach ($procesosBase as $p => $_) {
    foreach ($allWeeks as $week => $_) {
        $totalsPerProcess[$p][$week] = 0;
    }
    $totalsPerProcess[$p]['total'] = 0;
}

// Loop through each PN for processing times
foreach ($pnRegistros as $pn => $weeks) {
    $procesos = $procesosBase;
    $assetsProcess = $procesosBase;
    // Get routing times for this PN
    $timeProcess = mysqli_query($con, "SELECT `work_routing`, QtyTimes, timePerProcess, setUp_routing FROM `routing_models` WHERE pn_routing = '$pn'");
    while ($row = mysqli_fetch_assoc($timeProcess)) {
        if ($row['work_routing'] > 10000 && $row['work_routing'] < 10061) {
            $procesos['Cutting'] += ($row['QtyTimes'] * $row['timePerProcess']);
            $assetsProcess['Cutting'] += 1;
        }
        if ($row['work_routing'] > 10060 && $row['work_routing'] < 10441) {
            $procesos['Terminals'] += ($row['QtyTimes'] * $row['timePerProcess']);
            $assetsProcess['Terminals'] += 1;
        }
        if ($row['work_routing'] > 10440 && $row['work_routing'] < 10999) {
            $procesos['Assembly'] += ($row['QtyTimes'] * $row['timePerProcess']);
            $assetsProcess['Assembly'] += 1;
        }
        if ($row['work_routing'] > 11500 && $row['work_routing'] < 11700) {
            $procesos['Quality'] += ($row['QtyTimes'] * $row['timePerProcess']);
            $assetsProcess['Quality'] += 1;
        }
        if ($row['work_routing'] > 11700 && $row['work_routing'] < 12000) {
            $procesos['Packaging'] += ($row['QtyTimes'] * $row['timePerProcess']);
            $assetsProcess['Packaging'] += 1;
        }
    }

    // Display times per process
    foreach ($procesos as $key => $valor) {
        echo "<tr><td>{$pn} - {$key}</td>";
        $rowTotal = 0;
        foreach ($allWeeks as $week => $_) {
            $value = isset($weeks[$week]) ? $weeks[$week] : 0;
            if($value==0){
                echo "<td>0</td>";
            }else{
            $times = (($valor * $value) * 1.20)+($assetsProcess[$key]*300); // 80% efficiency adjustment
            $hours = floor($times / 3600);
            $min = round(($times % 3600) / 60);
            if ($min >= 60) {
                $hours += 1;
                $min -= 60;
            }

            if ($min < 1 && $hours < 1) {
                echo "<td>0</td>";
            } else {
                $qtyItems = "{$hours} h : {$min} min";
                echo "<td>{$qtyItems}</td>";
            }
        }
            // Save totals in seconds for accuracy
            $totalsPerProcess[$key][$week] += $times;
            $rowTotal += $times;
        }

        // Row total
        $rowH = floor($rowTotal / 3600);
        $rowM = round(($rowTotal % 3600) / 60);
        $totalsPerProcess[$key]['total'] += $rowTotal;

        if ($rowM >= 60) {
            $rowH += 1;
            $rowM -= 60;
        }
        $rowText = ($rowH < 1 && $rowM < 1) ? "0" : "{$rowH} h : {$rowM} min";
        echo "<td>{$rowText}</td>";
        echo "</tr>";
    }
}

// Final totals row
echo "<tr style='font-weight:bold; background:#f0f0f0;'><td colspan='" . (count($allWeeks) + 2) . "'>TOTALS</td></tr>";

foreach ($totalsPerProcess as $proc => $weeks) {
    echo "<tr style='font-weight:bold;'><td>{$proc} TOTAL</td>";
    foreach ($allWeeks as $week => $_) {
        $times = $weeks[$week];
        $hours = floor($times / 3600);
        $min = round(($times % 3600) / 60);
        if ($min >= 60) {
            $hours += 1;
            $min -= 60;
        }
        $cell = ($hours < 1 && $min < 1) ? "0" : "{$hours} h : {$min} min";
        echo "<td>{$cell}</td>";
    }
    // Grand total
    $total = $weeks['total'];
    $hours = floor($total / 3600);
    $min = round(($total % 3600) / 60);
    if ($min >= 60) {
        $hours += 1;
        $min -= 60;
    }
    $cell = ($hours < 1 && $min < 1) ? "0" : "{$hours} h : {$min} min";
    echo "<td>{$cell}</td>";
    echo "</tr>";
}

echo "</table><br><hr>";
?>
