<?php
require "../conection.php";
require '../vendor/autoload.php'; 
require "funforexl.php";
$producF=['A','B','C','D','E','F','G','H'];

date_default_timezone_set("America/Mexico_City");
$date=date("d-m-Y");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Families');
$sheet->setCellValue('A1', "Family A");
$sheet->setCellValue('B1', 'MORE THAN 300');
$sheet->setCellValue('A2', 'Family B');
$sheet->setCellValue('B2', 'FROM 200 TO 299');
$sheet->setCellValue('A3', 'Family C');
$sheet->setCellValue('B3', 'FROM 100 TO 199');
$sheet->setCellValue('A4', 'Family D');
$sheet->setCellValue('B4', 'FROM 50 TO 99');
$sheet->setCellValue('A5', 'Family E');
$sheet->setCellValue('B5', 'FROM 25 TO 49');
$sheet->setCellValue('A6', 'Family F');
$sheet->setCellValue('B6', 'FROM 10 TO 24');
$sheet->setCellValue('A7', 'Family G');
$sheet->setCellValue('B7', 'FROM 5 TO 9');
$sheet->setCellValue('A8', 'Family H');
$sheet->setCellValue('B8', 'LESS THAN 5');


foreach($producF as $fam){
    $sheet1 = $spreadsheet->createSheet();  
    $sheet1->setTitle('Family '.$fam);
    $registro = excels($fam);
    $t=2;
    $sheet1->setCellValue('A1', 'Part Number');
    $sheet1->setCellValue('B1', 'PN common');
    $sheet1->setCellValue('C1', 'Compatibility %');
    // $registro[][]=
    foreach ($registro as $reg) {
        $sheet1->setCellValue('A' . $t, $reg[0]);
        $sheet1->setCellValue('B' . $t, $reg[1]);
        $sheet1->setCellValue('C' . $t, $reg[2]);
        $t++;
    }
   
}
/*
$sheet1->setTitle('Family A');
$registro = excels('A');
$t=2;
$sheet1->setCellValue('A1', 'Part Number');
$sheet1->setCellValue('B1', 'PN common');
$sheet1->setCellValue('C1', 'Compatibility %');
// $registro[][]=
foreach ($registro as $reg) {
    $sheet1->setCellValue('A' . $t, $reg[0]);
    $sheet1->setCellValue('B' . $t, $reg[1]);
    $sheet1->setCellValue('C' . $t, $reg[2]);
    $t++;
}
$sheet2 = $spreadsheet->createSheet();  
$sheet2->setTitle('Family B');
$registro = excels('B');
$t=2;
$sheet2->setCellValue('A1', 'Part Number');
$sheet2->setCellValue('B1', 'PN common');
$sheet2->setCellValue('C1', 'Compatibility %');
// $registro[][]=
foreach ($registro as $reg) {
    $sheet2->setCellValue('A' . $t, $reg[0]);
    $sheet2->setCellValue('B' . $t, $reg[1]);
    $sheet2->setCellValue('C' . $t, $reg[2]);
    $t++;
}
$sheet3 = $spreadsheet->createSheet();  
$sheet3->setTitle('Family C');
$sheet4 = $spreadsheet->createSheet();  
$sheet4->setTitle('Family D');
$sheet5 = $spreadsheet->createSheet();  
$sheet5->setTitle('Family E');
$sheet6 = $spreadsheet->createSheet();  
$sheet6->setTitle('Family F');
$sheet7 = $spreadsheet->createSheet();  
$sheet7->setTitle('Family G');
$sheet8 = $spreadsheet->createSheet();  
$sheet8->setTitle('Family H');
*/

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="WO Station '.$date.'.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


