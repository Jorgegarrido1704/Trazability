<?php
require "../../app/conection.php";
require '../../app/vendor/autoload.php'; 

$numero=isset($_GET['wo'])?$_GET['wo']:"007877";
date_default_timezone_set("America/Mexico_City");
$date=date("d-m-Y");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$archivo="BASE_LISTAS.xlsx";
$spreadsheet = IOFactory::load($archivo);
$sheet = $spreadsheet->getActiveSheet();$t=11;$i=1;$total=0;$diseño=0;
$control="";
$count=1;
$buscawo=mysqli_query($con,"SELECT * FROM registro WHERE wo=''");
while($rowo=mysqli_fetch_array($buscawo)){
    $pn=$rowo['NumPart'];
    $client=$rowo['cliente'];
    $qty=$rowo['Qty'];
    $rev=$rowo['rev'];
}
$sheet->setCellValue('H4', $client);
$sheet->setCellValue('E6', $pn);
$sheet->setCellValue('L6', $rev);
$sheet->setCellValue('M7', $numero);
$sheet->setCellValue('M5', $date);
$sheet->setCellValue('Q8', 'Liberado');

$sheet->getStyle('A11:R700')->getFont()->setSize('12');
$sheet->getStyle('A11:R700')->getFont()->setBold(TRUE);
$sheet->getStyle('A11:R700')->getFont()->setname('Calibri');
$sheet->getStyle('A11:R700')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A11:R700')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A11:R700')->getAlignment()->setWrapText(true);

$buscarinfo=mysqli_query($con,"SELECT * FROM listascorte WHERE pn='$pn' AND corte = ''");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $corte=$row['corte'];
 $cons=$row['cons'];
 $tipo=$row['tipo'];
 $awg=$row['aws'];
 $color=$row['color'];
 $tamano=$row['tamano'];
 $terminal1=$row['terminal1'];
 $terminal2=$row['terminal2'];
 $conector=$row['conector'];
 $dataForm=$row['dataFrom'];
 $dataTo=$row['dataTo'];
 $total+=$tamano;
  
$sheet->setCellValue('A'.$t, $corte);
$sheet->setCellValue('B'.$t, $cons);
$sheet->setCellValue('C'.$t, $tipo);
$sheet->setCellValue('D'.$t, $awg);
$sheet->setCellValue('E'.$t, $color);
$sheet->setCellValue('F'.$t, $tamano);
$sheet->setCellValue('G'.$t, '');
$sheet->setCellValue('H'.$t, $terminal1);
$sheet->setCellValue('I'.$t, '');
$sheet->setCellValue('J'.$t, '');
$sheet->setCellValue('K'.$t, $terminal2);
$sheet->setCellValue('L'.$t, '');
$sheet->setCellValue('M'.$t, $conector);
$sheet->setCellValue('N'.$t, '');
$sheet->setCellValue('O'.$t, $qty);
$sheet->setCellValue('P'.$t, $dataForm);
$sheet->setCellValue('Q'.$t, $dataTo);
$sheet->setCellValue('R'.$t, '');
$i++;
$t++;}
 
$buscarinfo=mysqli_query($con,"SELECT * FROM listascorte WHERE pn='$pn' AND corte LIKE 'TRENZADO%'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
   
   
    if($diseño==0){
        $t+=1;
        $sheet->getStyle('H'.$t)->getFont()->setSize('16');
        $sheet->getStyle('H'.$t)->getFont()->setBold(TRUE);
        $sheet->getStyle('H'.$t)->getFont()->setname('Calibri');
        $sheet->getStyle('H'.$t)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
        $sheet->setCellValue('H'.$t, 'TRENZADOS');
        $diseño=1;
        $t+=2;
}
$corte=$row['corte'];
 $cons=$row['cons'];
 $tipo=$row['tipo'];
 $awg=$row['aws'];
 $color=$row['color'];
 $tamano=$row['tamano'];
 $terminal1=$row['terminal1'];
 $terminal2=$row['terminal2'];
 $conector=$row['conector'];
 $dataForm=$row['dataFrom'];
 $dataTo=$row['dataTo'];
 $total+=$tamano;
  if($control!=$corte){
      
    $t+=1;
    $control=$corte;
  }
$sheet->setCellValue('A'.$t, $corte);
$sheet->setCellValue('B'.$t, $cons);
$sheet->setCellValue('C'.$t, $tipo);
$sheet->setCellValue('D'.$t, $awg);
$sheet->setCellValue('E'.$t, $color);
$sheet->setCellValue('F'.$t, $tamano);
$sheet->setCellValue('G'.$t, '');
$sheet->setCellValue('H'.$t, $terminal1);
$sheet->setCellValue('I'.$t, '');
$sheet->setCellValue('J'.$t, '');
$sheet->setCellValue('K'.$t, $terminal2);
$sheet->setCellValue('L'.$t, '');
$sheet->setCellValue('M'.$t, $conector);
$sheet->setCellValue('N'.$t, '');
$sheet->setCellValue('O'.$t, $qty);
$sheet->setCellValue('P'.$t, $dataForm);
$sheet->setCellValue('Q'.$t, $dataTo);
$sheet->setCellValue('R'.$t, '');
$i++;
$t++;}
 $diseño=0;
$buscarinfo=mysqli_query($con,"SELECT * FROM listascorte WHERE pn='$pn' AND corte LIKE 'JUMPER%'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    
    if($diseño==0){
        $t+=1;
        $sheet->getStyle('H'.$t)->getFont()->setSize('16');
        $sheet->getStyle('H'.$t)->getFont()->setBold(TRUE);
        $sheet->getStyle('H'.$t)->getFont()->setname('Calibri');
        $sheet->getStyle('H'.$t)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
    $sheet->setCellValue('H'.$t, 'JUMPERS');
    $diseño=1;
    $t+=1;
}
$corte=$row['corte'];
 $cons=$row['cons'];
 $tipo=$row['tipo'];
 $awg=$row['aws'];
 $color=$row['color'];
 $tamano=$row['tamano'];
 $terminal1=$row['terminal1'];
 $terminal2=$row['terminal2'];
 $conector=$row['conector'];
 $dataForm=$row['dataFrom'];
 $dataTo=$row['dataTo'];
 $total+=$tamano;
 if($control!=$corte){
    $t+=1;
    $control=$corte;
 }
 
 

$sheet->setCellValue('A'.$t, $corte);
$sheet->setCellValue('B'.$t, $cons);
$sheet->setCellValue('C'.$t, $tipo);
$sheet->setCellValue('D'.$t, $awg);
$sheet->setCellValue('E'.$t, $color);
$sheet->setCellValue('F'.$t, $tamano);
$sheet->setCellValue('G'.$t, '');
$sheet->setCellValue('H'.$t, $terminal1);
$sheet->setCellValue('I'.$t, '');
$sheet->setCellValue('J'.$t, '');
$sheet->setCellValue('K'.$t, $terminal2);
$sheet->setCellValue('L'.$t, '');
$sheet->setCellValue('M'.$t, $conector);
$sheet->setCellValue('N'.$t, '');
$sheet->setCellValue('O'.$t, $qty);
$sheet->setCellValue('P'.$t, $dataForm);
$sheet->setCellValue('Q'.$t, $dataTo);
$sheet->setCellValue('R'.$t, '');
$i++;
$t++;}
   $diseño=0;
$buscarinfo=mysqli_query($con,"SELECT * FROM listascorte WHERE pn='$pn' AND corte LIKE 'CORTE%'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    if($diseño==0){
        $t+=1;  
        $sheet->getStyle('H'.$t)->getFont()->setSize('16');
        $sheet->getStyle('H'.$t)->getFont()->setBold(TRUE);
        $sheet->getStyle('H'.$t)->getFont()->setname('Calibri');  
        $sheet->getStyle('H'.$t)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
    $sheet->setCellValue('H'.$t, 'CABLES ESPECIALES');
    $diseño=1;
    $t+=2;
}
$corte=$row['corte'];
 $cons=$row['cons'];
 $tipo=$row['tipo'];
 $awg=$row['aws'];
 $color=$row['color'];
 $tamano=$row['tamano'];
 $terminal1=$row['terminal1'];
 $terminal2=$row['terminal2'];
 $conector=$row['conector'];
 $dataForm=$row['dataFrom'];
 $dataTo=$row['dataTo'];
 $total+=$tamano;
 if($control!=$corte){
    $t+=1;
    $control=$corte;
 }
$sheet->setCellValue('A'.$t, $corte);
$sheet->setCellValue('B'.$t, $cons);
$sheet->setCellValue('C'.$t, $tipo);
$sheet->setCellValue('D'.$t, $awg);
$sheet->setCellValue('E'.$t, $color);
$sheet->setCellValue('F'.$t, $tamano);
$sheet->setCellValue('G'.$t, '');
$sheet->setCellValue('H'.$t, $terminal1);
$sheet->setCellValue('I'.$t, '');
$sheet->setCellValue('J'.$t, '');
$sheet->setCellValue('K'.$t, $terminal2);
$sheet->setCellValue('L'.$t, '');
$sheet->setCellValue('M'.$t, $conector);
$sheet->setCellValue('N'.$t, '');
$sheet->setCellValue('O'.$t, $qty);
$sheet->setCellValue('P'.$t, $dataForm);
$sheet->setCellValue('Q'.$t, $dataTo);
$sheet->setCellValue('R'.$t, '');
$i++;
$t++;}


$sheet->getStyle('E'.$t)->getFont()->setSize('16');
        $sheet->getStyle('E'.$t)->getFont()->setBold(TRUE);
        $sheet->getStyle('E'.$t)->getFont()->setname('Calibri');
        $sheet->getStyle('E'.$t)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$sheet->setCellValue('E'.$t, 'Total MM: ');
$sheet->setCellValue('F'.$t, $total);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$pn.'.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


