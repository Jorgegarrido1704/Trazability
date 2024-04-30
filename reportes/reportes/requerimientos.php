<?php
require "../app/conectionEngi.php";
require '../../app/vendor/autoload.php';
date_default_timezone_set("America/Mexico_City");
$yesterday=strtotime(date('d-m-Y',strtotime('-1 week')));
$date=strtotime(date("d-m-Y"));
$last=$date-$yesterday;



//echo $lw.'--'.$date.'<br>';
   
$week=round($date);
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$datos = ['folio','fecha','who','description','note','qty','aprovadaComp','negada'];
$desviacion = new desviacion('*', 'desvation', 'ORDER BY id DESC', $datos);


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1','Folio' );
$sheet->setCellValue('B1', 'Fecha');
$sheet->setCellValue('C1', 'Quien solicita');
$sheet->setCellValue('D1', 'Descripcion');
$sheet->setCellValue('E1', 'notas');
$sheet->setCellValue('F1', 'Cantidad');
$sheet->setCellValue('G1', 'Aprovada');
$sheet->setCellValue('H1', 'Negada');

$t=2;

$select=mysqli_query($con,"SELECT * FROM material ORDER BY id DESC ");
While($row=mysqli_fetch_array($select)){
    for($i=0;$i<count($datos);$i++){
        $dato[$i]=$row[$datos[$i]];
        }
      
    $sheet->setCellValue('A'.$t, $dato[0] );
    $sheet->setCellValue('B'.$t, $dato[1] );
    $sheet->setCellValue('C'.$t, $dato[2] );
    $sheet->setCellValue('D'.$t, $dato[3] );
    $sheet->setCellValue('E'.$t, $dato[4] );
    $sheet->setCellValue('F'.$t, $dato[5] );
    $sheet->setCellValue('G'.$t, $dato[6] );
    $sheet->setCellValue('H'.$t, $dato[7] );
    
    
  
    $t++;
    }






$week = date('W');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Requerimientos  WEEK: ' . $week . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


