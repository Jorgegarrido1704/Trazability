<?php
require "../app/conection.php";
require '../vendor/autoload.php';
date_default_timezone_set("America/Mexico_City");
$lastDate=strtotime(date('d-m-Y 00:00',strtotime('-1 week')));

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Paros Semanal');
$sheet->setCellValue('A1','Fecha' );
$sheet->setCellValue('B1', 'Aplicador');
$sheet->setCellValue('C1', 'Terminal');
$sheet->setCellValue('D1', 'Quien Solicito');
$sheet->setCellValue('E1', 'Atendio');
$sheet->setCellValue('F1', 'Tiempo (min) de respuesta');
$sheet->setCellValue('G1', 'Tiempo (min) de atencion'); 
$t=2;
$buscarventa=mysqli_query($con,"SELECT * FROM  registro_paro WHERE equipo='Bancos para terminales' " );
While($row=mysqli_fetch_array($buscarventa)){

    $fecha=$row['fecha'];
    $equipo=$row['nombreEquipo'];
    $quien=$row['quien'];
    $atiende=$row['atiende'];
    $ini=$row['inimant'];
    $fin=$row['finhora'];
   if(strtotime($fecha)>$lastDate)  { 
    if(strpos($equipo,'/')){
        $equipo=explode('/',$equipo);
        $herr=$equipo[0];
        $ter=$equipo[1];
    }else{
    $herr=$equipo;
    $ter="";
    }
    $sheet->setCellValue('A'.$t, $fecha );
    $sheet->setCellValue('B'.$t, $herr );
    $sheet->setCellValue('C'.$t, $ter );
    $sheet->setCellValue('D'.$t, $quien );
    $sheet->setCellValue('E'.$t, $atiende );
    if($ini!=''){
        $tiempoAten=floatval(strtotime($ini)-strtotime($fecha))/60;
    }else{
        $tiempoAten="No se Ha atendido";
    }
    
    $sheet->setCellValue('F'.$t, $tiempoAten );
    if($fin!=''){
        $tiempoFin=floatval(strtotime($fin)-strtotime($ini))/60;
    }else{
        $tiempoFin="No se Ha Completado";
    }
    $sheet->setCellValue('G'.$t, $tiempoFin );
    $t++;
    }}

    //golpes

$sheet1=$spreadsheet->createSheet(); 
$sheet1->setTitle('Golpes Semanal');
$sheet1->setCellValue('A1', 'Herrmental');
$sheet1->setCellValue('B1', 'Terminal');
$sheet1->setCellValue('C1', 'Golpes Diarios');

$t=2;
$buscarinfo=mysqli_query($con,"SELECT * FROM mant_golpes  ORDER BY id DESC");
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha_reg'];
    $herramental= $row['herramental'];
    $termianl= $row['terminal'];
    $golpesDiarios= $row['golpesDiarios'];
 
if(strtotime($fecha)>$lastDate ){   
$sheet1->setCellValue('A'.$t, $herramental);
$sheet1->setCellValue('B'.$t, $termianl);
$sheet1->setCellValue('C'.$t, $golpesDiarios);
$t++;} }  
//mantenimientos pendientes
$sheet2=$spreadsheet->createSheet(); 
$sheet2->setTitle('Mantenimiento faltante Semanal');
$sheet2->setCellValue('A1', 'Herrmental');
$sheet2->setCellValue('B1', 'Terminal');
$sheet2->setCellValue('C1', 'Numero de Mantenimientos');

$t=2;

$mant3m=strtotime(date('d-m-Y 00:00',strtotime('-3 month')));
$buscarinfo=mysqli_query($con,"SELECT * FROM mant_golpes_diarios  ORDER BY id DESC");
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha_reg'];
    $herramental= $row['herramental'];
    $termianl= $row['terminal'];
    $totalmant= $row['totalmant'];
    $mantenimiento= $row['mantenimiento'];
 
if(strtotime($fecha)<$mant3m or $mantenimiento=='falta' ){   
$sheet2->setCellValue('A'.$t, $herramental);
$sheet2->setCellValue('B'.$t, $termianl);
$sheet2->setCellValue('C'.$t, $totalmant);
$t++;} }
// mantenimientos realizados en la semana
$sheet3=$spreadsheet->createSheet(); 
$sheet3->setTitle('Mantenimiento Realizados ');
$sheet3->setCellValue('A1', 'Herrmental');
$sheet3->setCellValue('B1', 'Terminal');
$sheet3->setCellValue('C1', 'Quien Realizo el Mantenimiento');
$sheet3->setCellValue('D1', 'Tiempo de Mantenimiento (min)');
$sheet3->setCellValue('E1', 'Descripción de Mantenimiento');


$t=2;
$buscarinfo=mysqli_query($con,"SELECT * FROM mant_herramental  ORDER BY id DESC");
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha_reg'];
    $tiempos= $row['tiempos'];
    $herramental= $row['herramental'];
    $termianl= $row['terminal'];
    $Minutos= $row['Minutos'];
    $quien= $row['quien'];
    $docMant= $row['docMant'];
    $date=$fecha." ".$tiempos;
if(strtotime($date)>$lastDate){   
$sheet3->setCellValue('A'.$t, $herramental);
$sheet3->setCellValue('B'.$t, $termianl);
$sheet3->setCellValue('C'.$t, $quien);
$sheet3->setCellValue('D'.$t, $Minutos);
$sheet3->setCellValue('E'.$t, $docMant);
$t++;} }

$week = date('W');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte Semenal herramentales  WEEK: ' . $week . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


