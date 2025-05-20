<?php
require "conection.php";
require 'vendor/autoload.php';
date_default_timezone_set("America/Mexico_City");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$todays=date("d-m-Y");
$today=date("d-m-Y 00:00");


$spreadsheet = new Spreadsheet();
$sheetwo = $spreadsheet->getActiveSheet();
// Work orders
$sheetwo->setTitle('Work order '.$todays);
$sheetwo->setCellValue('A1','Part Number');
$sheetwo->setCellValue('B1', 'Work Order');
$sheetwo->setCellValue('C1', 'Original Quantity');
$sheetwo->setCellValue('D1', 'Cutting');
$sheetwo->setCellValue('E1', 'Terminals');
$sheetwo->setCellValue('F1', 'Assembly');
$sheetwo->setCellValue('G1', 'Looming');
$sheetwo->setCellValue('H1', 'Pre testing');
$sheetwo->setCellValue('I1', 'Testing');
$sheetwo->setCellValue('J1', 'Quality Errors');
$sheetwo->setCellValue('K1', 'Engineering');
$sheetwo->setCellValue('L1', 'Shipping');
$sheetwo->setCellValue('M1', 'Shipped');
$sheetwo->setCellValue('N1', 'Tiempo en proceso');
$t=2;
$buscarWo=mysqli_query($con,"SELECT * FROM `registroparcial` INNER JOIN `registro` ON registroparcial.codeBar=registro.info ORDER BY `pn` DESC");
While($row=mysqli_fetch_array($buscarWo)){
    $pn=$row['pn'];
    $wo=$row['wo'];
    $orgQty=$row['orgQty'];
    $cortPar=$row['cortPar'];
    $libePar=$row['libePar'];
    $ensaPar=$row['ensaPar'];
    $loomPar=$row['loomPar'];
    $testPar=$row['testPar'];
    $embPar=$row['embPar'];  
    $eng=$row['eng'];
    $fallasCalidad=$row['fallasCalidad'];
    $preCalidad=$row['preCalidad'];
    $shipped=$orgQty-($cortPar+$libePar+$ensaPar+$loomPar+$testPar+$embPar+$eng+$fallasCalidad+$preCalidad); 
    $tiempo=$row['tiempototal'];
$sheetwo->setCellValue('A'.$t, $pn);
$sheetwo->setCellValue('B'.$t, $wo);
$sheetwo->setCellValue('C'.$t, $orgQty);
$sheetwo->setCellValue('D'.$t, $cortPar);
$sheetwo->setCellValue('E'.$t, $libePar);
$sheetwo->setCellValue('F'.$t, $ensaPar);
$sheetwo->setCellValue('G'.$t, $loomPar);
$sheetwo->setCellValue('H'.$t, $preCalidad);
$sheetwo->setCellValue('I'.$t, $testPar);
$sheetwo->setCellValue('J'.$t, $fallasCalidad);
$sheetwo->setCellValue('K'.$t, $eng);
$sheetwo->setCellValue('L'.$t, $embPar);
$sheetwo->setCellValue('M'.$t, $shipped);
$sheetwo->setCellValue('N'.$t, $tiempo);
$t++;        
}



$week = date('W');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte General ' . $todays . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit();


