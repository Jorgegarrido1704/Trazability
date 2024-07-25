<?php
require "conection.php";
require 'vendor/autoload.php';
date_default_timezone_set("America/Mexico_City");
$lasmonth=date('m-Y',strtotime('-1 month'));

$firstH=date('01-'.$lasmonth.' 00:00');
$finalH=date('01-m-Y 23:59' );
$dateH=date("d-m-Y 23:59",strtotime($finalH."-1 day"));
$fist=date('01-'.$lasmonth);
$final=date('01-m-Y' );
$date=date("d-m-Y",strtotime($final."-1 day"));

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Desviations');
$sheet->setCellValue('A1','Date' );
$sheet->setCellValue('B1', 'Client');
$sheet->setCellValue('C1', 'Supervisor');
$sheet->setCellValue('D1', 'Afected Part Number ');
$sheet->setCellValue('E1', 'Original piece');
$sheet->setCellValue('F1', 'Sustituted piece');
$sheet->setCellValue('G1', 'Quantity');
$sheet->setCellValue('H1', 'Reason');
$sheet->setCellValue('I1', 'Action');
$sheet->setCellValue('J1', 'Status');
$sheet->setCellValue('K1', 'Rejection Reason');
$t=2;
$buscarventa=mysqli_query($con,"SELECT * FROM  desvation  " );
While($row=mysqli_fetch_array($buscarventa)){

    $fecha=$row['fecha'];
    $client=$row['cliente'];
    $supervisor=$row['quien'];
    $pn=$row['Mafec'];
    $original=$row['porg'];
    $sustituted=$row['psus'];
    $quantity=$row['clsus'];
    $reason=$row['Causa'];
    $action=$row['accion'];
    $status=$row['count'];
    $rejection=$row['rechazo'];
    if(strtotime($fecha)>strtotime($firstH) && strtotime($fecha)<strtotime($dateH) ){
    $sheet->setCellValue('A'.$t, $fecha );
    $sheet->setCellValue('B'.$t, $client );
    $sheet->setCellValue('C'.$t, $supervisor );
    $sheet->setCellValue('D'.$t, $pn );
    $sheet->setCellValue('E'.$t, $original );
    $sheet->setCellValue('F'.$t, $sustituted );
    $sheet->setCellValue('G'.$t, $quantity );
    $sheet->setCellValue('H'.$t, $reason );
    $sheet->setCellValue('I'.$t, $action );
    if($status==4){
        $sheet->setCellValue('J'.$t, "Aproved" );
        $sheet->setCellValue('K'.$t, "N/A" );
    }else if($status==5){
        $sheet->setCellValue('J'.$t, "Rejected" );
        $sheet->setCellValue('K'.$t, $rejection );
    }else{
        $sheet->setCellValue('J'.$t, "Pending" );
        $sheet->setCellValue('K'.$t, 'N/A' );
    }        

    $t++;
    }}

$sheet1=$spreadsheet->createSheet();
$sheet1->setTitle('Engineering Activities' );
$sheet1->setCellValue('A1','Date' );
$sheet1->setCellValue('B1', 'Engineer');
$sheet1->setCellValue('C1', 'Activity');
$sheet1->setCellValue('D1', 'Description');
$sheet1->setCellValue('E1', 'Time (min)');
$t=2;
$actEng=mysqli_query($con,"SELECT * FROM  ingactividades" );



 

$week = date('M');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="General Report '. $week . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

