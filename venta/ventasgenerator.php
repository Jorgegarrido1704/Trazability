<?php
require "../app/conection.php";
require '../app/vendor/autoload.php'; 

date_default_timezone_set("America/Mexico_City");
//$today=date("d-m-Y");

$today=date("d-m-Y");
$partes=[];
$parte=mysqli_query($con,"SELECT * FROM regsitrocalidad  WHERE fecha LIKE '$today%' ");
foreach($parte as $part){
    $partes[]=$part['pn']; 
}
$partes=array_unique($partes);


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();$sheet = $spreadsheet->getActiveSheet();$t=2;
$sheet->setCellValue('A1', 'Fecha');
$sheet->setCellValue('B1', 'Numero de Parte ');
$sheet->setCellValue('C1', 'Precio Unitario');
$sheet->setCellValue('D1', 'Cantidad registrada');
$sheet->setCellValue('E1', 'Total');

foreach($partes as $parte){
    $buscarInfo=mysqli_query($con,"SELECT * FROM regsitrocalidad WHERE pn='$parte'");
    $numRow=mysqli_num_rows($buscarInfo);
    $buscarPrice=mysqli_query($con,"SELECT * FROM precios WHERE pn='$parte'");
    $rowPrice=mysqli_fetch_array($buscarPrice);
    $price=$rowPrice['price'];
    $client=$rowPrice['client'];
   

$sheet->setCellValue('A'.$t, $today);
$sheet->setCellValue('B'.$t, $parte);
$sheet->setCellValue('C'.$t, $price);
$sheet->setCellValue('D'.$t, $numRow);
$sheet->setCellValue('E'.$t, $numRow*$price);
$t++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Ventad ' . $today. '.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();



