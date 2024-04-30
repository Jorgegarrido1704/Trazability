<?php
require "../app/conectionEngi.php";
require '../../app/vendor/autoload.php';
date_default_timezone_set("America/Mexico_City");
$today=date("d-m-Y 00:00");
$todays=strtotime($today);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$datos = ['id', 'fecha', 'cliente', 'quien', 'Mafec', 'porg', 'psus', 'clsus', 'peridoDesv', 'Causa', 'accion', 'count', 'rechazo'];
$desviacion = new Desviacion('*', 'desviasion', 'id>1', $datos);


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Folio');
$sheet->setCellValue('B1', 'Fecha');
$sheet->setCellValue('C1', 'Cliente');
$sheet->setCellValue('D1', 'Quien solicita');
$sheet->setCellValue('E1', 'Modelo afectado');
$sheet->setCellValue('F1', 'Parte original');
$sheet->setCellValue('G1', 'Parte Sustituto');
$sheet->setCellValue('H1', 'Cantidad limite a sustituir');
$sheet->setCellValue('I1', 'Periodo de desviacion');
$sheet->setCellValue('J1', 'Causa');
$sheet->setCellValue('K1', 'Acción');
$sheet->setCellValue('L1', 'Estatus');
$sheet->setCellValue('M1', 'Motivo de rechazo');
$t=2;
$select=mysqli_query($con,"SELECT * FROM desvation ORDER BY id DESC");
while($row=mysqli_fetch_array($select)){
    
    for($i=0;$i<count($datos);$i++){
        $dato[$i]=$row[$datos[$i]];

    }
    $desvToday=strtotime($dato[1]);

    if($desvToday>$todays){
    
    $sheet->setCellValue('A'.$t, $dato[0] );
    $sheet->setCellValue('B'.$t, $dato[1] );
    $sheet->setCellValue('C'.$t, $dato[2] );
    $sheet->setCellValue('D'.$t, $dato[3] );
    $sheet->setCellValue('E'.$t, $dato[4] );
    $sheet->setCellValue('F'.$t, $dato[5] );
    $sheet->setCellValue('G'.$t, $dato[6] );
    $sheet->setCellValue('H'.$t, $dato[7] );
    $sheet->setCellValue('I'.$t, $dato[8] );
    $sheet->setCellValue('J'.$t, $dato[9] );
    $sheet->setCellValue('K'.$t, $dato[10] );
    if($dato[11]==4){
    $sheet->setCellValue('L'.$t, 'Aprovada' );
    }else if($dato[11]==5){
        $sheet->setCellValue('L'.$t, 'Negada' );
        }else if($dato[11]!=4 and $dato[11]!=5){
            $sheet->setCellValue('L'.$t, 'Pendiente' );
            }
    $sheet->setCellValue('M'.$t, $dato[12] );
    $t++;
    }
}






header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Desviaciones From ' . $today . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


