<?php
require "conection.php";
require 'vendor/autoload.php'; 

date_default_timezone_set("America/Mexico_City");
$date=date("d-m-Y");
$datestart=date("d-m-y", strtotime("-7 day"));
$dia1=date("d-m-y", strtotime("-6 day"));
$dia2=date("d-m-y", strtotime("-5 day"));
$dia3=date("d-m-y", strtotime("-4 day"));
$dia4=date("d-m-y", strtotime("-3 day"));
$dia5=date("d-m-y", strtotime("-2 day"));
$datefin=date("d-m-y", strtotime("-1 day"));
$week=date("W", strtotime("-5 day"));
$semana=$week-1;
$lider="Angel_G";
$departamento="Corte-Liberacion";
//$lider=$_POST['lider']?$_POST['lider']:"";
//$departamento=$_POST['departamento']?$_POST['departamento']:"";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
$archivo="asistencia.xlsx";
$spreadsheet = IOFactory::load($archivo);
$sheet = $spreadsheet->getActiveSheet();$t=12;
$sheet->setCellValue('C6', 'Corte-liberacion');
$sheet->setCellValue('J6', 'Angel Gonzalez');
$sheet->setCellValue('D8', $week);
$sheet->setCellValue('G8', $datestart);
$sheet->setCellValue('l8', $datefin);
$sheet->setCellValue('c11', $datestart);
$sheet->setCellValue('d11', $dia1);
$sheet->setCellValue('e11', $dia2);
$sheet->setCellValue('f11', $dia3);
$sheet->setCellValue('g11', $dia4);
$sheet->setCellValue('h11', $dia5);
$sheet->setCellValue('i11', $datefin);

$buscarinfo=mysqli_query($con,"SELECT * FROM assistence WHERE week='$week' and lider Like '$lider'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
   $name= $row['name'];
  
   $lunes= str_replace("-","",strtoupper($row['lunes']));
   $martes= str_replace("-","",strtoupper($row['martes']));
   $miercoles= str_replace("-","",strtoupper($row['miercoles']));
   $jueves= str_replace("-","",strtoupper($row['jueves']));
   $viernes= str_replace("-","",strtoupper($row['viernes']));
   $sabado= str_replace("-","",strtoupper($row['sabado']));
   $domingo= str_replace("-","",strtoupper($row['domingo']));
   
   $extras= $row['extras']; 
    
$sheet->setCellValue('A'.$t, $name);
$sheet->setCellValue('B'.$t, "");
$sheet->setCellValue('C'.$t, $lunes);
$sheet->setCellValue('D'.$t, $martes);
$sheet->setCellValue('E'.$t, $miercoles);
$sheet->setCellValue('F'.$t, $jueves);
$sheet->setCellValue('G'.$t, $viernes);
$sheet->setCellValue('H'.$t, $sabado);
$sheet->setCellValue('I'.$t, $domingo);
$sheet->setCellValue('J'.$t, $extras);
if(($lunes=="OK" OR $lunes=="T") and ($martes=="OK" OR $martes=="T") and ($miercoles=="OK" OR $miercoles=="T") and ($jueves=="OK" OR $jueves=="T") and ($viernes=="OK" OR $viernes=="T") ){
    $sheet->setCellValue('K'.$t, 'NO');
    $sheet->setCellValue('L'.$t, 'OK');
    $sheet->setCellValue('N'.$t, 'OK');
}else if($lunes=="R"  or $martes=="R" or $miercoles=="R" or $jueves=="R" or $viernes=="R"){
    $sheet->setCellValue('K'.$t, 'NO');
    $sheet->setCellValue('M'.$t, 'OK');
    $sheet->setCellValue('N'.$t, 'NOK');
}else if($lunes=="F"  or $martes=="F" or $miercoles=="F" or $jueves=="F" or $viernes=="F"){
    $sheet->setCellValue('K'.$t, 'SI');
    $sheet->setCellValue('M'.$t, 'NOK');
    $sheet->setCellValue('O'.$t, 'NOK');
}

$t++;}
$t+=2;
$sheet->setCellValue('B'.$t, 'FALTA');
$sheet->setCellValue('C'.$t, 'F');
$sheet->setCellValue('D'.$t, 'PERMISO SIN SUELDO');
$sheet->setCellValue('E'.$t, 'PSS');
$sheet->setCellValue('G'.$t, 'OBSERVACIONES: ');
$t=$t+1;
$sheet->setCellValue('B'.$t, 'PERMISO CON SUELDO');
$sheet->setCellValue('C'.$t, 'PCS');
$sheet->setCellValue('D'.$t, 'VACACIONES');
$sheet->setCellValue('E'.$t, 'V');
$t=$t+1;
$sheet->setCellValue('B'.$t, 'INCAPACIDAD');
$sheet->setCellValue('C'.$t, 'INC');
$sheet->setCellValue('D'.$t, 'SUSPENSION');
$sheet->setCellValue('E'.$t, 'SUS');
$sheet->setCellValue('M'.$t, '___________________');
$t=$t+1;
$sheet->setCellValue('B'.$t, 'TIEMPO EXTRA');
$sheet->setCellValue('C'.$t, 'TE');
$sheet->setCellValue('M'.$t, 'Autorizado por');



header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="DEPARTAMENTO '.$departamento.' '.$week.'.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


