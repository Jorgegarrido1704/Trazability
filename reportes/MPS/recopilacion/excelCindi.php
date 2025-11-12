<?php
require "../../app/conectionTraza.php";
require '../../../app/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();

// ===== 1. DEMAND =====
$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle("Demand");

$registrosMPS = mysqli_query($con, "SELECT pn, dq, qtymps FROM datos_mps WHERE pn='1001489409'OR pn='1001488939'OR pn='660925'OR pn='1002707335'OR pn='1001455147'OR pn='1003318064'
OR pn='B222992'OR pn='1000109371'OR pn='16517630'OR pn='1003312301'OR pn='1000473129'OR pn='1002835774'OR pn='16516661'OR pn='1002835044'OR pn='1003544214'OR pn='660320'OR pn='16516612'
OR pn='1000516139'OR pn='1001774292'OR pn='16517623'OR pn='16514775'OR pn='16514514'OR pn='1000230326'OR pn='1000312635'OR pn='1002719292'OR pn='16518485'OR pn='16518486'OR pn='1003359943'
OR pn='1002186052'OR pn='1001073962' ");
$data = [];
$allWeeks = [];
while ($row = mysqli_fetch_assoc($registrosMPS)) {
    $pn = $row['pn'];
    $week = date("W", strtotime($row['dq']));
    $data[$pn][$week] = ($data[$pn][$week] ?? 0) + (int)$row['qtymps'];
    $allWeeks[$week] = true;
}

$weeks = array_keys($allWeeks);
sort($weeks);

// Header
$sheet1->setCellValue("A1","PN");
$colIndex = 2;
foreach ($weeks as $week) {
    $sheet1->setCellValueByColumnAndRow($colIndex,1,"W$week");
    $colIndex++;
}

// Fill data
$rowNum = 2;
$fillColors = ["FFFFCC","CCFFCC","FFCCCC","CCE5FF","E0CCFF"];
foreach ($data as $pn => $weeksData) {
    $sheet1->setCellValue("A$rowNum",$pn);
    $colIndex = 2;
    $color = $fillColors[($rowNum-2) % count($fillColors)];
    foreach ($weeks as $week) {
        $sheet1->setCellValueByColumnAndRow($colIndex,$rowNum,$weeksData[$week] ?? 0);
        $sheet1->getStyleByColumnAndRow(1,$rowNum,$colIndex,$rowNum)
               ->getFill()->setFillType(Fill::FILL_SOLID)
               ->getStartColor()->setARGB($color);
        $colIndex++;
    }
    $rowNum++;
}

// Auto size
foreach(range('A',$sheet1->getHighestColumn()) as $col) {
    $sheet1->getColumnDimension($col)->setAutoSize(true);
}

// ===== 2. TIMES =====
$sheet2 = $spreadsheet->createSheet();
$sheet2->setTitle("Times");

// Processes
$processes = ['Sub-Assembly','Assembly','Quality','Packaging'];

// Header
$rowNum = 1;
$header = ["PN - Process"];
foreach ($weeks as $w) $header[] = "W$w";  // only weeks
$header[] = "Total";
$sheet2->fromArray($header,NULL,"A$rowNum");
$rowNum++;

// Totales por proceso
$totalsPerProcess = [];
foreach ($processes as $proc) $totalsPerProcess[$proc] = array_fill_keys($weeks,0);

// Fill data
foreach ($data as $pn => $weeksData) {
    $procesosBase = ['Cutting'=>0,'Terminals'=>0,'Sub-Assembly'=>0,'Assembly'=>0,'Quality'=>0,'Packaging'=>0];
    $assetsProcess = ['Cutting'=>0,'Terminals'=>0,'Sub-Assembly'=>0,'Assembly'=>0,'Quality'=>0,'Packaging'=>0];

    $timeProcess = mysqli_query($con, "SELECT `work_routing`, QtyTimes, timePerProcess 
                                       FROM `routing_models` 
                                       WHERE pn_routing = '$pn' and work_routing >10440");
    while ($row = mysqli_fetch_assoc($timeProcess)) {
        $wr = $row['work_routing'];
        $tpp = $row['QtyTimes'] * $row['timePerProcess'];
        if (($wr>10440 && $wr<10501) or ($wr>10950 && $wr < 11000)) {$procesosBase['Sub-Assembly'] += $tpp; $assetsProcess['Sub-Assembly']++;}
        if ($wr>10500 && $wr<10950) {$procesosBase['Assembly'] += $tpp; $assetsProcess['Assembly']++;}
        if ($wr>11500 && $wr<11700) {$procesosBase['Quality'] += $tpp; $assetsProcess['Quality']++;}
        if ($wr>11700 && $wr<12000) {$procesosBase['Packaging'] += $tpp; $assetsProcess['Packaging']++;}
    }

    $color = $fillColors[($rowNum-2) % count($fillColors)];
    foreach ($processes as $proc) {
        $row = [$pn . " - " . $proc];
        $rowTotal = 0;
        foreach ($weeks as $w) {
            $qty = $weeksData[$w] ?? 0;
            if($qty>0){
         //   $timeSec = ($procesosBase[$proc] * $qty * 1.2) + ($assetsProcess[$proc]*300);
            $timeSec = ($procesosBase[$proc] * $qty * 1.2);
            $h = floor($timeSec/3600);
            $m = round(($timeSec%3600)/60,0);
            $row[] = ($h<1 && $m<1) ? "0" : "{$h} h : {$m} m";

            $totalsPerProcess[$proc][$w] += $timeSec;
            $rowTotal += $timeSec;
            } else {
                $row[] = "0";
            }
        }

        $hTotal = floor($rowTotal/3600);
        $mTotal = round(($rowTotal%3600)/60);
        $row[] = ($hTotal<1 && $mTotal<1) ? "0" : "{$hTotal} h : {$mTotal} m";
    

        $sheet2->fromArray($row,NULL,"A$rowNum");
        $sheet2->getStyle("A$rowNum:".$sheet2->getHighestColumn().$rowNum)
               ->getFill()->setFillType(Fill::FILL_SOLID)
               ->getStartColor()->setARGB($color);
        $rowNum++;
    }
}

// Totales por semana
$totalRow = ["WEEKLY TOTAL"];
foreach ($weeks as $w) {
    $sum = array_sum(array_column($totalsPerProcess,$w));
    $h=floor($sum/3600);
    $m=round(($sum%3600)/60);
    $totalRow[] = ($h<1 && $m<1) ? "0" : "{$h} h : {$m} m";
}
$grandTotal = array_sum(array_map('array_sum',$totalsPerProcess));
$hG=floor($grandTotal/3600);
$mG=round(($grandTotal%3600)/60);
$totalRow[] = ($hG<1 && $mG<1) ? "0" : "{$hG} h : {$mG} m";
$sheet2->fromArray($totalRow,NULL,"A$rowNum");

// ===== 3. ITEMS =====
$sheet3 = $spreadsheet->createSheet();
$sheet3->setTitle("Items");

$items=[];
foreach($data as $pn=>$weeksData){
    $res=mysqli_query($con,"SELECT item, qty FROM datos WHERE part_num='$pn'");
    while($r=mysqli_fetch_assoc($res)){
        foreach($weeksData as $week=>$qty){
            $items[$r['item']][$week] = ($items[$r['item']][$week] ??0)+ $qty*$r['qty'];
        }
    }
}

// Header
$sheet3->setCellValue("A1","Item");
$colIndex=2;
foreach($weeks as $w) $sheet3->setCellValueByColumnAndRow($colIndex++,1,"W$w");
foreach(['Max','Min','Avg','StdDev','Total'] as $h) $sheet3->setCellValueByColumnAndRow($colIndex++,1,$h);

// Rows
$rowNum=2;
foreach($items as $item=>$weeksData){
    $sheet3->setCellValue("A$rowNum",$item);
    $values=[]; $colIndex=2;
    foreach($weeks as $w){
        $val=$weeksData[$w]??0;
        $sheet3->setCellValueByColumnAndRow($colIndex,$rowNum,$val);
        $values[]=$val;
        $colIndex++;
    }
    $sheet3->setCellValueByColumnAndRow($colIndex++,$rowNum,max($values));
    $sheet3->setCellValueByColumnAndRow($colIndex++,$rowNum,min($values));
    $sheet3->setCellValueByColumnAndRow($colIndex++,$rowNum,round(array_sum($values)/count($values),2));
    $sheet3->setCellValueByColumnAndRow($colIndex++,$rowNum,round(sqrt(array_sum(array_map(fn($x)=>($x-array_sum($values)/count($values))**2,$values))/count($values)),2));
    $sheet3->setCellValueByColumnAndRow($colIndex++,$rowNum,array_sum($values));
    $rowNum++;
}

// ===== GENERATE FILE =====
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="production.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
?>
