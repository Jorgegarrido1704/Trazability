<?php
require "conection.php";
require 'vendor/autoload.php'; 
date_default_timezone_set("America/Mexico_City");
$date=date("d-m-Y");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();$sheet = $spreadsheet->getActiveSheet();$t=2;
$sheet->setCellValue('A1', 'Date');
$sheet->setCellValue('B1', 'Part Num');
$sheet->setCellValue('C1', 'Client');
$sheet->setCellValue('D1', 'Rev');
$sheet->setCellValue('E1', 'Wo');
$sheet->setCellValue('F1', 'Sono');
$sheet->setCellValue('G1', 'Qty');
$sheet->setCellValue('H1', 'Where');
$sheet->setCellValue('I1', 'paro');
$buscarinfo=mysqli_query($con,"SELECT * FROM registro WHERE count!=20 and donde Like '%%corte'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha'];
    $parte= $row['NumPart'];
    $Client= $row['cliente'];
    $rev= $row['rev'];
    $wo= $row['wo'];
    $po= $row['po'];
    $Qty= $row['Qty'];
    $where= 'CUTTING';
    $paro= $row['paro'];
$sheet->setCellValue('A'.$t, $fecha);
$sheet->setCellValue('B'.$t, $parte);
$sheet->setCellValue('C'.$t, $Client);
$sheet->setCellValue('D'.$t, $rev);
$sheet->setCellValue('E'.$t, $wo);
$sheet->setCellValue('F'.$t, $po);
$sheet->setCellValue('G'.$t, $Qty);
$sheet->setCellValue('H'.$t, $where);
$sheet->setCellValue('I'.$t, $paro);
$t++;}
$buscarinfo=mysqli_query($con,"SELECT * FROM registro WHERE count!=20 and donde Like '%%liberacion'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha'];
    $parte= $row['NumPart'];
    $Client= $row['cliente'];
    $rev= $row['rev'];
    $wo= $row['wo'];
    $po= $row['po'];
    $Qty= $row['Qty'];
    $where= 'TERMINALS';
    $paro= $row['paro'];
$sheet->setCellValue('A'.$t, $fecha);
$sheet->setCellValue('B'.$t, $parte);
$sheet->setCellValue('C'.$t, $Client);
$sheet->setCellValue('D'.$t, $rev);
$sheet->setCellValue('E'.$t, $wo);
$sheet->setCellValue('F'.$t, $po);
$sheet->setCellValue('G'.$t, $Qty);
$sheet->setCellValue('H'.$t, $where);
$sheet->setCellValue('I'.$t, $paro);
$t++;}
$buscarinfo=mysqli_query($con,"SELECT * FROM registro WHERE count!=20 and donde Like '%%ensamble'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha'];
    $parte= $row['NumPart'];
    $Client= $row['cliente'];
    $rev= $row['rev'];
    $wo= $row['wo'];
    $po= $row['po'];
    $Qty= $row['Qty'];
    $where= 'ASSEMBLY';
    $paro= $row['paro'];
    $sheet->setCellValue('A'.$t, $fecha);
$sheet->setCellValue('B'.$t, $parte);
$sheet->setCellValue('C'.$t, $Client);
$sheet->setCellValue('D'.$t, $rev);
$sheet->setCellValue('E'.$t, $wo);
$sheet->setCellValue('F'.$t, $po);
$sheet->setCellValue('G'.$t, $Qty);
$sheet->setCellValue('H'.$t, $where);
$sheet->setCellValue('I'.$t, $paro);
$t++;}

$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha'];
    $parte= $row['NumPart'];
    $Client= $row['cliente'];
    $rev= $row['rev'];
    $wo= $row['wo'];
    $po= $row['po'];
    $Qty= $row['Qty'];
    $where= 'LOOMING';
    $paro= $row['paro'];
    $sheet->setCellValue('A'.$t, $fecha);
$sheet->setCellValue('B'.$t, $parte);
$sheet->setCellValue('C'.$t, $Client);
$sheet->setCellValue('D'.$t, $rev);
$sheet->setCellValue('E'.$t, $wo);
$sheet->setCellValue('F'.$t, $po);
$sheet->setCellValue('G'.$t, $Qty);
$sheet->setCellValue('H'.$t, $where);
$sheet->setCellValue('I'.$t, $paro);
$t++;}
$buscarinfo=mysqli_query($con,"SELECT * FROM registro WHERE count!=20 and donde Like '%%cables%%%'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha'];
    $parte= $row['NumPart'];
    $Client= $row['cliente'];
    $rev= $row['rev'];
    $wo= $row['wo'];
    $po= $row['po'];
    $Qty= $row['Qty'];
    $where= 'SPECIAL WIRE';
    $paro= $row['paro'];
    $sheet->setCellValue('A'.$t, $fecha);
$sheet->setCellValue('B'.$t, $parte);
$sheet->setCellValue('C'.$t, $Client);
$sheet->setCellValue('D'.$t, $rev);
$sheet->setCellValue('E'.$t, $wo);
$sheet->setCellValue('F'.$t, $po);
$sheet->setCellValue('G'.$t, $Qty);
$sheet->setCellValue('H'.$t, $where);
$sheet->setCellValue('I'.$t, $paro);
$t++;}
$buscarinfo=mysqli_query($con,"SELECT * FROM registro WHERE count!=20 and donde Like '%%loom%%'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha'];
    $parte= $row['NumPart'];
    $Client= $row['cliente'];
    $rev= $row['rev'];
    $wo= $row['wo'];
    $po= $row['po'];
    $Qty= $row['Qty'];
    $where= 'LOOMING';
    $paro= $row['paro'];
    $sheet->setCellValue('A'.$t, $fecha);
$sheet->setCellValue('B'.$t, $parte);
$sheet->setCellValue('C'.$t, $Client);
$sheet->setCellValue('D'.$t, $rev);
$sheet->setCellValue('E'.$t, $wo);
$sheet->setCellValue('F'.$t, $po);
$sheet->setCellValue('G'.$t, $Qty);
$sheet->setCellValue('H'.$t, $where);
$sheet->setCellValue('I'.$t, $paro);
$t++;}
$buscarinfo=mysqli_query($con,"SELECT * FROM registro WHERE count!=20 and donde Like '%%prueba%%'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha'];
    $parte= $row['NumPart'];
    $Client= $row['cliente'];
    $rev= $row['rev'];
    $wo= $row['wo'];
    $po= $row['po'];
    $Qty= $row['Qty'];
    $where= 'TESTING';
    $paro= $row['paro'];
    $sheet->setCellValue('A'.$t, $fecha);
$sheet->setCellValue('B'.$t, $parte);
$sheet->setCellValue('C'.$t, $Client);
$sheet->setCellValue('D'.$t, $rev);
$sheet->setCellValue('E'.$t, $wo);
$sheet->setCellValue('F'.$t, $po);
$sheet->setCellValue('G'.$t, $Qty);
$sheet->setCellValue('H'.$t, $where);
$sheet->setCellValue('I'.$t, $paro);
$t++;}
$buscarinfo=mysqli_query($con,"SELECT * FROM registro WHERE count!=20 and donde Like '%%embarque'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha'];
    $parte= $row['NumPart'];
    $Client= $row['cliente'];
    $rev= $row['rev'];
    $wo= $row['wo'];
    $po= $row['po'];
    $Qty= $row['Qty'];
    $where= 'SHIPPING';
    $paro= $row['paro'];
    $sheet->setCellValue('A'.$t, $fecha);
$sheet->setCellValue('B'.$t, $parte);
$sheet->setCellValue('C'.$t, $Client);
$sheet->setCellValue('D'.$t, $rev);
$sheet->setCellValue('E'.$t, $wo);
$sheet->setCellValue('F'.$t, $po);
$sheet->setCellValue('G'.$t, $Qty);
$sheet->setCellValue('H'.$t, $where);
$sheet->setCellValue('I'.$t, $paro);
$t++;}

$buscarinfo=mysqli_query($con,"SELECT * FROM registro WHERE count!=20 and donde Like '%%proceso'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha'];
    $parte= $row['NumPart'];
    $Client= $row['cliente'];
    $rev= $row['rev'];
    $wo= $row['wo'];
    $po= $row['po'];
    $Qty= $row['Qty'];
    $where= 'PLANNING';
    $paro= $row['paro'];
    $sheet->setCellValue('A'.$t, $fecha);
$sheet->setCellValue('B'.$t, $parte);
$sheet->setCellValue('C'.$t, $Client);
$sheet->setCellValue('D'.$t, $rev);
$sheet->setCellValue('E'.$t, $wo);
$sheet->setCellValue('F'.$t, $po);
$sheet->setCellValue('G'.$t, $Qty);
$sheet->setCellValue('H'.$t, $where);
$sheet->setCellValue('I'.$t, $paro);
$t++;}
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="WO Station '.$date.'.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


