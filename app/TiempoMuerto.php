<?php
require "conection.php";
require 'vendor/autoload.php'; 

date_default_timezone_set("America/Mexico_City");
$date=date("d-m-Y");
$datestart=strtotime(date("d-m-Y 00:00"));
$dia1=date("d-m-y", strtotime("-6 day"));
$dia2=date("d-m-y", strtotime("-5 day"));
$dia3=date("d-m-y", strtotime("-4 day"));
$dia4=date("d-m-y", strtotime("-3 day"));
$dia5=date("d-m-y", strtotime("-2 day"));
$datefin=strtotime(date("d-m-Y 00:00", strtotime("-1 day")));
$week=date("W", strtotime("-5 day"));


//$lider=$_POST['lider']?$_POST['lider']:"";
//$departamento=$_POST['departamento']?$_POST['departamento']:"";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
$archivo="Tiempo Muerto.xlsx";
$spreadsheet = IOFactory::load($archivo);
$sheet = $spreadsheet->getActiveSheet();$t=7;


$buscarinfo=mysqli_query($con,"SELECT * FROM timedead WHERE area='Calidad'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
$fechasControl=strtotime($row['fecha'] );  
$fecha=$row['fecha'];
$cliente=$row['cliente'];
$np=$row['np'];

$defecto=$row['defecto'];
if($row['timeIni']!="No Aun" && $row['timeFin']!='No Aun'){
$timeIni=date("d-m-Y H:i", ($row['timeIni'] ));
$timeFin=date("d-m-Y H:i", ($row['timeFin'] ));
}else if($row['timeIni']!="No Aun" && $row['timeFin']=='No Aun'){
    $timeIni=date("d-m-Y H:i", ($row['timeIni'] ));
$timeFin="Activa";}

$total=$row['Total'];
$who=$row['whoDet'];
$respArea=$row['respArea'];

   
//if($fechasControl>$datestart ){    
    if($fechasControl>$datefin ){ 
$sheet->setCellValue('A'.$t, $fecha);
$sheet->setCellValue('B'.$t, $cliente);
$sheet->setCellValue('C'.$t, $np);
$sheet->setCellValue('D'.$t, $defecto);
$sheet->setCellValue('F'.$t, $timeIni);
$sheet->setCellValue('G'.$t, $timeFin);
$sheet->setCellValue('H'.$t, $total);
$sheet->setCellValue('J'.$t, $who);
$sheet->setCellValue('L'.$t, $respArea);

$t++;}}



header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="TimeDead Calidad  '.$date.'.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


