<?php
require "../app/conection.php";
require '../app/vendor/autoload.php'; 
date_default_timezone_set("America/Mexico_City");

$dateIni=strtotime(date("d-m-Y 00:00"));
$today = date("d-m-Y");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Herrmental');
$sheet->setCellValue('B1', 'Terminal');
$sheet->setCellValue('C1', 'Golpes Diarios');

$t=2;
$buscarinfo=mysqli_query($con,"SELECT * FROM mant_golpes  ORDER BY id DESC");
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha_reg'];
    $herramental= $row['herramental'];
    $termianl= $row['terminal'];
    $golpesDiarios= $row['golpesDiarios'];
 
if($dateIni<strtotime($fecha) ){   
$sheet->setCellValue('A'.$t, $quien);
$sheet->setCellValue('B'.$t, $fecha);
$sheet->setCellValue('C'.$t, $fint);
$t++;} }  
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte Diario Herramentales.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


