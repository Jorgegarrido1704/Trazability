<?php
require "conection.php";
require 'vendor/autoload.php'; 
date_default_timezone_set("America/Mexico_City");
$today=date("d-m-Y");
$year=strtotime("01-01-2024");
$date=strtotime(date("d-m-Y 17:00"));

$last=604800;
$week=round(($date-$year)/$last);
$lastweek=$date-$last;
//echo $date."-".$last."-".$lastweek;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();$sheet = $spreadsheet->getActiveSheet();$t=2;
$sheet->setCellValue('A1', 'Folio');
$sheet->setCellValue('B1', 'Fecha');
$sheet->setCellValue('C1', 'Cliente');
$sheet->setCellValue('D1', 'Numero de Parte');
$sheet->setCellValue('E1', 'Cantidad');
$sheet->setCellValue('F1', 'Codigo');
$sheet->setCellValue('G1', 'Serial');
$sheet->setCellValue('H1', 'Responsable');


$buscarinfo=mysqli_query($con,"SELECT * FROM regsitrocalidad ");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $parte=$row['pn'];
    $qty=$row['resto'];
    $codigo=$row['codigo'];
    $prueba=$row['prueba'];
    $id=$row['id'];
    $fecha=$row['fecha'];
    $client=$row['client'];
    $info=$row['info'];
    $resp=$row['Responsable'];
    $fechas=strtotime($fecha);
if($fechas<=$date && $fechas>=$lastweek){
$sheet->setCellValue('A'.$t, $id);
$sheet->setCellValue('B'.$t, $fecha);
$sheet->setCellValue('C'.$t, $client);
$sheet->setCellValue('D'.$t, $parte);
$sheet->setCellValue('E'.$t, $qty);
$sheet->setCellValue('F'.$t, $codigo);
$sheet->setCellValue('G'.$t, $prueba);
$sheet->setCellValue('H'.$t, $prueba);

$t++;}}


// Dynamic Sheet
$dynamicSpreadsheet = new Spreadsheet();
$dynamicSheet = $dynamicSpreadsheet->getActiveSheet();
$writer = new Xlsx($spreadsheet);
$dynamicSheet->setCellValue('A1', 'Folio');
$dynamicSheet->setCellValue('B1', 'Fecha');
$dynamicSheet->setCellValue('C1', 'Cliente');
$dynamicSheet->setCellValue('D1', 'Numero de Parte');
$dynamicSheet->setCellValue('E1', 'Cantidad');
$dynamicSheet->setCellValue('F1', 'Codigo');
$dynamicSheet->setCellValue('G1', 'Serial');

$buscarinfo=mysqli_query($con,"SELECT * FROM regsitrocalidad ");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $parte=$row['pn'];
    $qty=$row['resto'];
    $codigo=$row['codigo'];
    $prueba=$row['prueba'];
    $id=$row['id'];
    $fecha=$row['fecha'];
    $client=$row['client'];
    $info=$row['info'];
    $fechas=strtotime($fecha);
if($fechas<=$date && $fechas>=$lastweek){
$dynamicSheet->setCellValue('A'.$t, $id);
$dynamicSheet->setCellValue('B'.$t, $fecha);
$dynamicSheet->setCellValue('C'.$t, $client);
$dynamicSheet->setCellValue('D'.$t, $parte);
$dynamicSheet->setCellValue('E'.$t, $qty);
$dynamicSheet->setCellValue('F'.$t, $codigo);
$dynamicSheet->setCellValue('G'.$t, $prueba);

$t++;}}


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Calidad  WEEK: '.$week.'.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();



