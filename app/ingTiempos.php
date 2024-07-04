<?php
require "conection.php";
require 'vendor/autoload.php'; 

date_default_timezone_set("America/Mexico_City");
$datefin=strtotime(date("d-m-Y 23:59"));
$dateIni=strtotime(date("d-m-Y 00:00"));


//$lider=$_POST['lider']?$_POST['lider']:"";
//$departamento=$_POST['departamento']?$_POST['departamento']:"";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Ingeniero');
$sheet->setCellValue('B1', 'Fecha de inicio');
$sheet->setCellValue('C1', 'Fecha de fin');
$sheet->setCellValue('D1', 'Tiempo de actividad');
$sheet->setCellValue('E1', 'Actividad');
$sheet->setCellValue('F1', 'Descripción de la actividad');
$t=2;
$buscarinfo=mysqli_query($con,"SELECT * FROM ingactividades  ORDER BY Id_request DESC");

while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha'];
    $quien= $row['Id_request'];
    $fint= $row['finT'];
    $actividad= $row['actividades'];
    $desciption= $row['desciption'];
    $tiempo=floatval(strtotime($fint)-strtotime($fecha))/60;


   
if($dateIni<strtotime($fecha) ){    
$sheet->setCellValue('A'.$t, $quien);
$sheet->setCellValue('B'.$t, $fecha);
$sheet->setCellValue('C'.$t, $fint);
$sheet->setCellValue('D'.$t, $tiempo);
$sheet->setCellValue('E'.$t, $actividad);
$sheet->setCellValue('F'.$t, $desciption);


$t++;}
}


    
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Engineering Activities .xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


