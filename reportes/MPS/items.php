<?php
require "../app/conectionTraza.php";

$currentWeek = date("W");
$pnRegistros = [];
$allWeeks = [];

// Get MPS records
$registrosMPS = mysqli_query($con, "SELECT pn,dq,qtymps FROM `datos_mps`");

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