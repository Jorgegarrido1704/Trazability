<?php
require "../app/conectionTraza.php";

$currentWeek = date("W");
$pnRegistros = [];
$allWeeks = [];

// Get MPS records
$registrosMPS = mysqli_query($con, "SELECT pn,dq,qtymps FROM `datos_mps` WHERE pn='1001489409'OR pn='1001488939'OR pn='660925'OR pn='1002707335'OR pn='1001455147'OR pn='1003318064'OR pn='B222992'OR pn='1000109371'OR pn='16517630'OR pn='1003312301'OR pn='1000473129'OR pn='1002835774'OR pn='16516661'OR pn='1002835044'OR pn='1003544214'OR pn='660320'OR pn='16516612'OR pn='1000516139'OR pn='1001774292'OR pn='16517623'OR pn='16514775'OR pn='16514514'OR pn='1000230326'OR pn='1000312635'OR pn='1002719292'OR pn='16518485'OR pn='16518486'OR pn='1003359943'OR pn='1002186052'OR pn='1001073962' ");

while ($row = mysqli_fetch_assoc($registrosMPS)) {
    $pn = $row['pn'];
    $week = date("W", strtotime($row['dq']));
    $qty = (int)$row['qtymps'];
    $registroItems=mysqli_query($con, "SELECT item, qty FROM `datos` WHERE part_num = '$pn' ");
while ($row = mysqli_fetch_assoc($registroItems)) {
    $item=$row['item'];
    $qtyItems=$row['qty'];

    // Adjust week for next year
    if ($week < $currentWeek) {
        $week += 52;
    }

    // Track all weeks (for header)
    $allWeeks[$week] = true;

    // Initialize if not set
    if (!isset($pnRegistros[$item][$week])) {
        $pnRegistros[$item][$week] = 0;
    }

    // Sum quantities
    $pnRegistros[$item][$week] += round(($qtyItems *$qty),2);
}
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
echo "<button><a href='registos.php'>Back</a></button>";

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Items</th>";
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
    echo "<td>{$pn}</td>";
    foreach ($allWeeks as $week => $_) {
        $value = isset($weeks[$week]) ? $weeks[$week] : 0;
        echo "<td>{$value}</td>";
        $rowTotal += $value;
        $totals[$week] += $value;
    }
    echo "<td style='font-weight:bold;'>{$rowTotal}</td>";
    echo "</tr>";
   // $grandTotal += $rowTotal;
}

/* Totals row
echo "<tr style='font-weight:bold; background:#f0f0f0;'>";
echo "<td>Total</td>";
foreach ($totals as $week => $sum) {
    echo "<td>{$sum}</td>";
}
echo "<td>{$grandTotal}</td>";
echo "</tr>";
*/
echo "</table>";
?>

<script>
    function redirectToForm() {
        window.location.href = "../../corte/busqueda.php";
    }
</script>