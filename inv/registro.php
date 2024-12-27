<?php 

require "../app/conection.php";
require "../vendor/autoload.php";

$t=0;
$auditado=array();
$datos=array();
$select=isset($_GET['who'])?$_GET['who']:'';
$date=date("d-m-Y");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();$sheet = $spreadsheet->getActiveSheet();$t=2;

$sheet->setCellValue('A1', 'Item');
$sheet->setCellValue('B1', 'Qty');
$sheet->setCellValue('C1', 'Register By');
$sheet->setCellValue('D1', 'Part Number');

$selectDatos=mysqli_query($con,"SELECT * FROM inventario WHERE Register='$select'");
while($row=mysqli_fetch_array($selectDatos)){
    $datos[0]=$row['items'];
    $datos[1]=$row['Register'];
    $datos[2]=$row['qty'];
    $datos[3]=$row['id_workOrder'];       
   

$sheet->setCellValue('A'.$t, $datos[0]);
$sheet->setCellValue('B'.$t, $datos[2]);
$sheet->setCellValue('C'.$t, $datos[1]);
$sheet->setCellValue('D'.$t, $datos[3]);

$t++;}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Registro de Inventario '.$date.'.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


