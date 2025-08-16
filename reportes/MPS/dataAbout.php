<?php

$pn=isset($_GET['pn'])?$_GET['pn']:"";
require "../app/conectionTraza.php";

$currentWeek = date("W");
$pnRegistros = [];
$allWeeks = [];

// Get MPS records
$registrosMPS = mysqli_query($con, "SELECT * FROM `datos_mps` WHERE pn = '$pn'");

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

// Prepare totals per week and calculate row totals
$totals = array_fill_keys(array_keys($allWeeks), 0);
$grandTotal = 0;

// Calculate row totals and store them
$rowTotals = [];
foreach ($pnRegistros as $pn => $weeks) {
    $rowTotals[$pn] = array_sum($weeks);
}

// Sort part numbers by total qty (desc)
uksort($pnRegistros, function($a, $b) use ($rowTotals) {
    return $rowTotals[$b] <=> $rowTotals[$a];
});

// Table header
echo "<button onclick='redirectToForm();'>Back</button>";
echo "<h1 align='center'>Part Number: {$pn}</h1><hr><h3>MPS QTY</h3>";

echo "<table border='1' cellpadding='5' cellspacing='0' align='center' style='width: 100%;'>";
echo '<tr style="font-weight:bold; align: center;"><th>Part Number</th>';
foreach ($allWeeks as $week => $_) {
    $displayWeek = $week > 52 ? ($week - 52) . " (next year)" : $week;
    echo "<th>W{$displayWeek}</th>";
}
echo "<th>Total Row</th>";
echo "</tr>";

// Table rows and accumulate totals
foreach ($pnRegistros as $pn => $weeks) {
    $rowTotal = 0;
    echo "<tr>";
    echo "<td>{$pn}</a></td>";
    foreach ($allWeeks as $week => $_) {
        $value = isset($weeks[$week]) ? $weeks[$week] : 0;
        echo "<td>{$value}</td>";
        $rowTotal += $value;
        $totals[$week] += $value;
    }
    echo "<td style='font-weight:bold;'>{$rowTotal}</td>";
    echo "</tr>";
    $grandTotal += $rowTotal;
}
echo "</table><br><hr>";

//Items per week
echo "<h3>Items per week</h3>";

echo "<table border='1' cellpadding='5' cellspacing='0' align='center' style='width: 100%;'>";
echo "<tr'>";

$registroItems=mysqli_query($con, "SELECT * FROM `datos` WHERE part_num = '$pn' ");
while ($row = mysqli_fetch_assoc($registroItems)) {
    echo "<td>{$row['item']}</td>";

    $rowTotal = 0;
 foreach ($allWeeks as $week => $_) {
        $value = isset($weeks[$week]) ? $weeks[$week] : 0;
        $qtyItems=$row['qty']*$value;
        echo "<td>{$qtyItems}</td>";
        $rowTotal += $qtyItems;
        $totals[$week] += $value;
    }
echo "<td style='font-weight:bold;'>{$rowTotal}</td>";
echo "</tr>";
}
echo "</table><br><hr>";
echo "<h3>Production time per week in hours (80% efficiency ) </h3>";

echo "<table border='1' cellpadding='5' cellspacing='0' align='center' style='width: 100%;'>";
$timeProcess=mysqli_query($con, "SELECT `work_routing`,QtyTimes, timePerProcess,setUp_routing FROM `routing_models` WHERE pn_routing = '$pn' ");
$presesos=['Cutting'=>0,'Terminals'=>0,'Assembly'=>0,'Quality'=>0,'Packaging'=>0];
$assetProces=['Cutting'=>0,'Terminals'=>0,'Assembly'=>0,'Quality'=>0,'Packaging'=>0];
while ($row = mysqli_fetch_assoc($timeProcess)) {
   if($row['work_routing']>10000 and $row['work_routing']<10061){
       $presesos['Cutting']+=($row['QtyTimes']*$row['timePerProcess']);
       $assetProces['Cutting']+=1;
   }
   if($row['work_routing']>10060 and $row['work_routing']<10441){
       $presesos['Terminals']+=($row['QtyTimes']*$row['timePerProcess']);
       $assetProces['Terminals']+=1;
   }
   if($row['work_routing']>10440 and $row['work_routing']<10999){
       $presesos['Assembly']+=($row['QtyTimes']*$row['timePerProcess']);
       $assetProces['Assembly']+=1;
   }
  
   if($row['work_routing']>11500 and $row['work_routing']<11700){
       $presesos['Quality']+=($row['QtyTimes']*$row['timePerProcess']);
       $assetProces['Quality']+=1;
   }
   if($row['work_routing']>11700 and $row['work_routing']<12000){
       $presesos['Packaging']+=($row['QtyTimes']*$row['timePerProcess']);
       $assetProces['Packaging']+=1;
   }
}






    foreach ($presesos as $key => $valor) {
        echo "<tr><td>{$key}</td>";
    
    $grandTotal=0;
 foreach ($allWeeks as $week => $_) {
        
        $value = isset($weeks[$week]) ? $weeks[$week] : 0;
        $times=(($valor*$value)*1.20)+($assetProces[$key]*300);
        $hours=round(($times/3600),0);
        $min= round(($times%3600)/60,0);
        if($min>=60){
            $hours=$hours+1;
            $min=$min-60;
        }
        $qtyItems="{$hours} h : {$min} min";
        echo "<td>{$qtyItems}</td>";
       
    }

    }

echo "</tr> ";
    
echo "</table><br><hr>";
    

?>

<script>
    function redirectToForm() {
        window.location.href = "registos.php";
    }
</script>