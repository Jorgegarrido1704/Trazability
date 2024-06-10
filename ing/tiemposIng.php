<?php
require "../app/conection.php";
require '../app/vendor/autoload.php'; 
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
$sheet->setCellValue('A1', 'Ingeniero');
$sheet->setCellValue('B1', 'Mes');
$sheet->setCellValue('C1', 'Fecha');
$sheet->setCellValue('D1', 'fin');
$sheet->setCellValue('E1', 'actividades');
$sheet->setCellValue('f1', 'descripcion');
$sheet->setCellValue('g1', 'minutos');



$buscarinfo=mysqli_query($con,"SELECT * FROM ingactividades ");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $id=$row['Id_request']; 
    $fecha=$row['fecha'];
    $fin=$row['finT'];
    $actividades=$row['actividades'];
    $desciption=$row['desciption'];
    $mes=substr($fecha,3,2);
    if(!empty($fin)){
    $min=(strtotime($fin)-strtotime($fecha))/60;

}else {
    $min=0;
}
$sheet->setCellValue('A'.$t, $id);
$sheet->setCellValue('b'.$t, $mes);
$sheet->setCellValue('c'.$t, str_replace("-","/",substr( $fecha,0, 10,)));
$sheet->setCellValue('d'.$t, str_replace("-","/",substr($fin,0, 10,)));
$sheet->setCellValue('e'.$t, $actividades);
$sheet->setCellValue('f'.$t, $desciption);
$sheet->setCellValue('g'.$t, $min);


$t++;}


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
$dynamicSheet->setCellValue('G1', 'Responsable');

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
$dynamicSheet->setCellValue('A'.$t, $id);
$dynamicSheet->setCellValue('B'.$t, $fecha);
$dynamicSheet->setCellValue('C'.$t, $client);
$dynamicSheet->setCellValue('D'.$t, $parte);
$dynamicSheet->setCellValue('E'.$t, $qty);
$dynamicSheet->setCellValue('F'.$t, $codigo);
$dynamicSheet->setCellValue('G'.$t, $prueba);
$dynamicSheet->setCellValue('H'.$t, $resp);

$t++;}}


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Tiempos Ing.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();



