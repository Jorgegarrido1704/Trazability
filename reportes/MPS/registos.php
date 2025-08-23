<?php
require "../app/conectionTraza.php";

try {
    $currentWeek = date("W");
    $year = date("Y");

    $pnRegistros = [];
    $allDays = [];
    $weeksPerDay = []; // keep track of week per day

    // Get MPS records
    $registrosMPS = mysqli_query($con, "SELECT * FROM `datos_mps`");

    while ($row = mysqli_fetch_assoc($registrosMPS)) {
        $pn = $row['pn'];
        $week = date("W", strtotime($row['dq']));
        $qty = (int)$row['qtymps'];

        // Adjust week for next year
        /*if ($week < $currentWeek) {
            $week += 52;
        }*/

        // --- Split into 5 working days ---
        $baseQty   = intdiv($qty, 5);
        $remainder = $qty % 5;
        $dto = new DateTime();
        $dto->setISODate($year, $week); // Monday

        for ($i = 0; $i < 5; $i++) {
            $dayKey  = $dto->format('Y-m-d');
            $dayName = $dto->format('D');

            $dailyQty = $baseQty + ($i < $remainder ? 1 : 0);

            if (!isset($pnRegistros[$pn][$dayKey])) {
                $pnRegistros[$pn][$dayKey] = 0;
            }

            $pnRegistros[$pn][$dayKey] += $dailyQty;
            $allDays[$dayKey] = $dayName;
            $weeksPerDay[$dayKey] = $week; // map day → week

            $dto->modify('+1 day');
        }
    }

    // Sort days
    ksort($allDays);

    // Totals
    $totals = array_fill_keys(array_keys($allDays), 0);
    $grandTotal = 0;

    // Row totals
    $rowTotals = [];
    foreach ($pnRegistros as $pn => $days) {
        $rowTotals[$pn] = array_sum($days);
    }

    // Sort PN by total desc
    uksort($pnRegistros, function ($a, $b) use ($rowTotals) {
        return $rowTotals[$b] <=> $rowTotals[$a];
    });

    // Buttons
    echo "<button onclick='redirectToForm();'>Update Data</button>";
    echo "<button onclick='redirectTotiempos();'>Build Times</button>";
    echo "<button onclick='redirectToItems();'>Items</button>";
    echo "<button onclick='excelReport();'>Excel Report</button>";

    // --- Table ---
    echo "<table border='1' cellpadding='5' style='border-collapse:collapse; text-align:center;'>";

    // --- Week header row ---
    echo "<tr><th rowspan='2'>Part Number</th>";
    $lastWeek = null;
    foreach ($allDays as $dayKey => $dayName) {
        $week = $weeksPerDay[$dayKey];
        if($week >52){
            $week -= 52;
        }
        $bg = ($week % 2 == 0) ? "#e6f7ff" : "#ffe6e6"; // alternate colors
        echo "<th style='background:$bg'>{$week}</th>";
    }
    echo "<th rowspan='2'>Total Row</th></tr>";

    // --- Day header row ---
    echo "<tr>";
    foreach ($allDays as $dayKey => $dayName) {
        $week = $weeksPerDay[$dayKey];
        $bg = ($week % 2 == 0) ? "#e6f7ff" : "#ffe6e6";
        echo "<th style='background:$bg'>{$dayName}<br>{$dayKey}</th>";
    }
    echo "</tr>";

    // --- Rows per PN ---
    foreach ($pnRegistros as $pn => $days) {
        $rowTotal = 0;
        echo "<tr>";
        echo "<td><a href='dataAbout.php?pn={$pn}'>{$pn}</a></td>";
        foreach ($allDays as $dayKey => $dayName) {
            $week = $weeksPerDay[$dayKey];
            $bg = ($week % 2 == 0) ? "#e6f7ff" : "#ffe6e6";

            $value = isset($days[$dayKey]) ? $days[$dayKey] : 0;
            echo "<td style='background:$bg'>{$value}</td>";
            $rowTotal += $value;
            $totals[$dayKey] += $value;
        }
        echo "<td style='font-weight:bold;'>{$rowTotal}</td></tr>";
        $grandTotal += $rowTotal;
    }

    // --- Totals row ---
    echo "<tr style='font-weight:bold; background:#f0f0f0;'>";
    echo "<td>Total</td>";
    foreach ($totals as $dayKey => $sum) {
        $week = $weeksPerDay[$dayKey];
        $bg = ($week % 2 == 0) ? "#e6f7ff" : "#ffe6e6";
        echo "<td style='background:$bg'>{$sum}</td>";
    }
    echo "<td>{$grandTotal}</td></tr>";

    echo "</table>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<script>
    function redirectToForm() {
        window.location.href = "../../corte/busqueda.php";
    }
    function redirectTotiempos() {
        window.location.href = "tiempos.php";
    }
    function redirectToItems() {
        window.location.href = "items.php";
    }
    function excelReport() {
        window.location.href = "recopilacion/excel.php";
    }
</script>
