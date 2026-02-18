<?php
require "../app/conection.php";
require '../app/vendor/autoload.php';
date_default_timezone_set("America/Mexico_City");
$today=date("d-m-Y");
$todays=strtotime($today);
$semana= intval(date("W"));
$year=date("Y");
$diaNumber=date("N");
$dias=["lunes","martes","miercoles","jueves","viernes","sabado","domingo"];
$hoy=$dias[$diaNumber-1];
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Asistencia');
$sheet->setCellValue('A1', 'Numero empleado');
$sheet->setCellValue('B1', 'Nombre empleado');
$sheet->setCellValue('C1', 'Supervisor');
$sheet->setCellValue('D1', 'Fecha '.$today);
$t=2;
//echo $semana."-".$year."-".$diaNumber."-".$hoy;
$select=mysqli_query($con,"SELECT id_empleado,lider,`name` FROM assistence WHERE `week`='$semana' AND `yearOfAssistence`='$year' AND ('$hoy'='OK' OR '$hoy'='R' OR '$hoy'='PCT' OR '$hoy'='ASM' or '$hoy'='SCE') ORDER BY lider DESC");
while($row=mysqli_fetch_array($select)){
    $sheet->setCellValue('A'.$t, $row['id_empleado'] );
    $sheet->setCellValue('B'.$t, $row['name'] );
    $sheet->setCellValue('C'.$t, $row['lider'] );  
    $t++;
  //  echo $row['id_empleado']."<br>";
    }
    
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Lista de asistencia ' . $today . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


