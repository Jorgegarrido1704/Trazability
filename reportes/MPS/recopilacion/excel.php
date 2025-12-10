<?php
// Note: This script is designed to run on a PHP server with the
// PhpSpreadsheet library installed via Composer.

// Ensure these files exist and are correctly configured for your server environment.
require "../../app/conectionTraza.php";
require '../../../app/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Set up the Spreadsheet object
$spreadsheet = new Spreadsheet();

// ====================================================================
// ===== 1. DEMAND SHEET LOGIC (Currently using mocked data) =====
// ====================================================================
$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle("Demand");

/*
// Original Database Query (Commented out in provided code)
$registrosMPS = mysqli_query($con, "SELECT pn, dq, qtymps FROM datos_mps WHERE pn='1001489409'OR pn='1001488939'OR pn='660925'OR pn='1002707335'OR pn='1001455147'OR pn='1003318064'
OR pn='B222992'OR pn='1000109371'OR pn='16517630'OR pn='1003312301'OR pn='1000473129'OR pn='1002835774'OR pn='16516661'OR pn='1002835044'OR pn='1003544214'OR pn='660320'OR pn='16516612'
OR pn='1000516139'OR pn='1001774292'OR pn='16517623'OR pn='16514775'OR pn='16514514'OR pn='1000230326'OR pn='1000312635'OR pn='1002719292'OR pn='16518485'OR pn='16518486'OR pn='1003359943'
OR pn='1002186052'OR pn='1001073962' ");
$data = [];
$allWeeks = [];
//only one week one time

while ($row = mysqli_fetch_assoc($registrosMPS)) {
    $pn = $row['pn'];
    // Assuming dq is a date field, date("W", ...) extracts the week number
    $week = date("W", strtotime($row['dq']));
    $data[$pn][$week] = ($data[$pn][$week] ?? 0) + (int)$row['qtymps'];
    $allWeeks[$week] = true;
}
*/
// Current Mocked Data (Replace this block with the commented-out query above when ready)
$pn_list = [
    '1000109371', 'B222992', '1002719292', '660925', '1003359943',
    '1000473129', '1000516139', '1001488939', '1001489409', '1001455147',
    '660320', '1000230326', '1000312635', '1002186052', '16514514',
    '16514775', '16516612', '16516661', '16517623', '16517630',
    '16518485', '16518486', '1001073962', '1002707335', '1003318064',
    '1003312301', '1003544214', '1001774292', '1002835044', '1002835774'
];

$data = [];
$allWeeks = [];

// Mocking one unit of demand for each PN in "Week 0"
foreach ($pn_list as $pn_item) {
    $data[$pn_item][0] = 1;
}
$allWeeks[0] = true;
// End Mocked Data

$weeks = array_keys($allWeeks);
sort($weeks);

// Header
$sheet1->setCellValue("A1","PN");
$colIndex = 2;
foreach ($weeks as $week) {
    $sheet1->setCellValueByColumnAndRow($colIndex,1,"W$week");
    $colIndex++;
}

// Fill data and apply alternating row colors
$rowNum = 2;
$fillColors = ["FFFFCC","CCFFCC","FFCCCC","CCE5FF","E0CCFF"];
foreach ($data as $pn => $weeksData) {
    $sheet1->setCellValue("A$rowNum",$pn);
    $colIndex = 2;
    // Cycle through colors based on the row index (minus header row)
    $color = $fillColors[($rowNum-2) % count($fillColors)];
    foreach ($weeks as $week) {
        $sheet1->setCellValueByColumnAndRow($colIndex,$rowNum,$weeksData[$week] ?? 0);
        $colIndex++;
    }

    // Apply color to the entire row range used
    $sheet1->getStyle("A$rowNum:".$sheet1->getHighestColumn($rowNum).$rowNum)
           ->getFill()->setFillType(Fill::FILL_SOLID)
           ->getStartColor()->setARGB($color);

    $rowNum++;
}

// Auto size columns
foreach(range('A',$sheet1->getHighestColumn()) as $col) {
    $sheet1->getColumnDimension($col)->setAutoSize(true);
}

// ====================================================================
// ===== 2. TIMES SHEET LOGIC =====
// ====================================================================
$sheet2 = $spreadsheet->createSheet();
$sheet2->setTitle("Times");
$sheet2->setCellValue("A1","Item");
$sheet2->setCellValue("B1","Op Seq");
$sheet2->setCellValue("C1","Description");
$sheet2->setCellValue("D1","Standar");



$i=2;

    foreach ($pn_list as $pn) {
    // Query routing data for the current PN
    $total=0;
    $timeProcess = mysqli_query($con, "SELECT `work_routing`, QtyTimes, timePerProcess
                                       FROM `routing_models`
                                       WHERE pn_routing = '$pn' and work_routing >= 10440");

    // Aggregate time into base processes based on work_routing ranges
    while ($row = mysqli_fetch_assoc($timeProcess)) {
        $wr = $row['work_routing'];
        // Time in seconds for this routing step
        $total += ($row['QtyTimes'] * $row['timePerProcess'])*(1.3*1.2);
        
    }
    $color = $fillColors[($i-1) % 2];
    // Generate rows for each relevant process for this PN
               $m = round(($total ) / 60,2);
            
        $sheet2->setCellValue("A$i","$pn");
        $sheet2->setCellValue("B$i","");
        $sheet2->setCellValue("C$i","Total Assemble, Test, and Pack");
        $sheet2->setCellValue("D$i","$m");
       

       
        // Add row to sheet and apply color
    
        $sheet2->getStyle("A$i:".$sheet2->getHighestColumn().$i)
               ->getFill()->setFillType(Fill::FILL_SOLID)
               ->getStartColor()->setARGB($color);
                $i++;
        
    }


// Auto size columns for Times sheet
foreach(range('A',$sheet2->getHighestColumn()) as $col) {
    $sheet2->getColumnDimension($col)->setAutoSize(true);
}


// ====================================================================
// ===== 3. ITEMS SHEET LOGIC =====
// ====================================================================
$sheet3 = $spreadsheet->createSheet();
$sheet3->setTitle("Items per PN");
$sheet3->setCellValue("A1","PN");
$sheet3->setCellValue("B1","Item");
$sheet3->setCellValue("C1","QTY");

$fillColors;
 $i=2; 
// Iterate through each part number (PN) and its weekly demand
foreach ($pn_list as $pn) {
    $colores=$fillColors[($i-2) % count($fillColors)];
      
    // Query for bill of materials (BOM) for the current PN
    $res = mysqli_query($con, "SELECT item, qty FROM datos WHERE part_num = '$pn'");
    while ($r = mysqli_fetch_assoc($res)) {
        $sheet3->getStyle("A$i:".$sheet3->getHighestColumn().$i)
               ->getFill()->setFillType(Fill::FILL_SOLID)
               ->getStartColor()->setARGB($colores);
        // Calculate item demand for each week
        $sheet3->setCellValue("A$i",$pn);
        $sheet3->setCellValue("B$i",$r['item']);
        $sheet3->setCellValue("C$i",$r['qty']);
        $i++;
    }
    
}



// Auto size columns for Items sheet
foreach(range('A',$sheet3->getHighestColumn()) as $col) {
    $sheet3->getColumnDimension($col)->setAutoSize(true);
}
// ====================================================================
// ===== 4. ITEMS SHEET LOGIC =====
// ====================================================================
$sheet4 = $spreadsheet->createSheet();
$sheet4->setTitle("Items");

$items = [];
// Iterate through each part number (PN) and its weekly demand
foreach ($data as $pn => $weeksData) {
    // Query for bill of materials (BOM) for the current PN
    $res = mysqli_query($con, "SELECT item, qty FROM datos WHERE part_num='$pn'");
    while ($r = mysqli_fetch_assoc($res)) {
        // Calculate item demand for each week
        foreach ($weeksData as $week => $qty) {
            // item demand = (existing item demand) + (PN demand * qty required per PN)
            $items[$r['item']][$week] = ($items[$r['item']][$week] ?? 0) + $qty * $r['qty'];
        }
    }
}

// Header
$sheet4->setCellValue("A1","Item");
$colIndex=2;
foreach($weeks as $w) $sheet4->setCellValueByColumnAndRow($colIndex++,1,"W$w");
foreach(['Max','Min','Avg','StdDev','Total'] as $h) $sheet4->setCellValueByColumnAndRow($colIndex++,1,$h);

// Rows
$rowNum=2;
foreach($items as $item => $weeksData) {
    $sheet4->setCellValue("A$rowNum",$item);
    $values = [];
    $colIndex = 2;

    // Output weekly values and collect for stats
    foreach ($weeks as $w) {
        $val = $weeksData[$w] ?? 0;
        $sheet4->setCellValueByColumnAndRow($colIndex, $rowNum, $val);
        $values[] = $val;
        $colIndex++;
    }

    $count = count($values);
    $sum = array_sum($values);
    $avg = $count > 0 ? $sum / $count : 0;

    // Calculate and output statistical columns
    $sheet4->setCellValueByColumnAndRow($colIndex++, $rowNum, max($values)); // Max
    $sheet4->setCellValueByColumnAndRow($colIndex++, $rowNum, min($values)); // Min
    $sheet4->setCellValueByColumnAndRow($colIndex++, $rowNum, round($avg, 2)); // Avg

    // Standard Deviation Calculation
    if ($count > 0) {
        $variance = array_sum(array_map(fn($x) => ($x - $avg) ** 2, $values)) / $count;
        $stdDev = sqrt($variance);
    } else {
        $stdDev = 0;
    }
    $sheet4->setCellValueByColumnAndRow($colIndex++, $rowNum, round($stdDev, 2)); // StdDev

    $sheet4->setCellValueByColumnAndRow($colIndex++, $rowNum, $sum); // Total
    $rowNum++;
}

// Auto size columns for Items sheet
foreach(range('A',$sheet4->getHighestColumn()) as $col) {
    $sheet4->getColumnDimension($col)->setAutoSize(true);
}


// ====================================================================
// ===== GENERATE AND OUTPUT FILE =====
// ====================================================================

// Set the active sheet back to the first one (Demand)
$spreadsheet->setActiveSheetIndex(0);

$writer = new Xlsx($spreadsheet);

// Set HTTP headers for file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="production.xlsx"');
header('Cache-Control: max-age=0');

// Output the file content
$writer->save('php://output');
exit;
?>