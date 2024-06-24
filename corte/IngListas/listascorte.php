<?php
require "../../app/conection.php";
require '../../app/vendor/autoload.php'; 

$numero=isset($_POST['wo'])?$_POST['wo']:"";
date_default_timezone_set("America/Mexico_City");
$date=date("d-m-Y");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
if(!empty($numero)){
    
$archivo="BASE_LISTAS.xlsx";
$spreadsheet = IOFactory::load($archivo);
$sheet = $spreadsheet->getActiveSheet();$t=11;$i=1;$total=0;$diseño=0;
$control="";
$count=1;
$buscawo=mysqli_query($con,"SELECT * FROM registro WHERE NumPart='$numero'");
while($rowo=mysqli_fetch_array($buscawo)){
    $pn=$rowo['NumPart'];
    $client=$rowo['cliente'];
    $qty=$rowo['Qty'];
    $rev=$rowo['rev'];
}
$sheet->setCellValue('H4', $client);
$sheet->setCellValue('E6', $pn);
$sheet->setCellValue('M6', $rev);
$sheet->setCellValue('P7', $numero);
$sheet->setCellValue('P5', $date);
$sheet->setCellValue('T8', 'Liberado');

$sheet->getStyle('A11:R700')->getFont()->setSize('12');
$sheet->getStyle('A11:R700')->getFont()->setBold(TRUE);
$sheet->getStyle('A11:R700')->getFont()->setname('Calibri');
$sheet->getStyle('A11:R700')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A11:R700')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A11:R700')->getAlignment()->setWrapText(true);

$buscarinfo=mysqli_query($con,"SELECT * FROM listascorte WHERE pn='$pn' AND tipo_cons = ''");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $corte=$row['tipo_cons'];
 $cons=$row['cons'];
 $tipo=$row['tipo'];
 $awg=$row['aws'];
 $color=$row['color'];
 $tamano=$row['tamano'];
 $stp1=$row['strip1'];
 $terminal1=$row['terminal1'];
 $sello1=$row['sello1'];
 $nota1=$row['Nota1'];
 $stp2=$row['strip2'];
 $terminal2=$row['terminal2'];
 $sello2=$row['sello2'];
 $nota2=$row['Nota2'];
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
$sheet->setCellValue('G'.$t, $stp1);
$sheet->setCellValue('H'.$t, $terminal1);
$sheet->setCellValue('I'.$t, $sello1);
$sheet->setCellValue('J'.$t, $nota1);
$sheet->setCellValue('K'.$t, "");
$sheet->setCellValue('L'.$t, $stp2);
$sheet->setCellValue('M'.$t, $terminal2);
$sheet->setCellValue('N'.$t, $sello2);
$sheet->setCellValue('O'.$t, $nota2);
$sheet->setCellValue('P'.$t, '');
$sheet->setCellValue('Q'.$t, $conector);
$sheet->setCellValue('R'.$t, '');
$sheet->setCellValue('S'.$t, $qty);
$sheet->setCellValue('T'.$t, $dataForm);
$sheet->setCellValue('U'.$t, $dataTo);

$i++;
$t++;}
 
$buscarinfo=mysqli_query($con,"SELECT * FROM listascorte WHERE pn='$pn' AND tipo_cons LIKE 'TRENZADO%'");
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
$corte=$row['tipo_cons'];
$cons=$row['cons'];
$tipo=$row['tipo'];
$awg=$row['aws'];
$color=$row['color'];
$tamano=$row['tamano'];
$stp1=$row['strip1'];
$terminal1=$row['terminal1'];
$sello1=$row['sello1'];
$nota1=$row['Nota1'];
$stp2=$row['strip2'];
$terminal2=$row['terminal2'];
$sello2=$row['sello2'];
$nota2=$row['Nota2'];
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
  $sheet->setCellValue('G'.$t, $stp1);
  $sheet->setCellValue('H'.$t, $terminal1);
  $sheet->setCellValue('I'.$t, $sello1);
  $sheet->setCellValue('J'.$t, $nota1);
  $sheet->setCellValue('K'.$t, "");
  $sheet->setCellValue('L'.$t, $stp2);
  $sheet->setCellValue('M'.$t, $terminal2);
  $sheet->setCellValue('N'.$t, $sello2);
  $sheet->setCellValue('O'.$t, $nota2);
  $sheet->setCellValue('P'.$t, '');
  $sheet->setCellValue('Q'.$t, $conector);
  $sheet->setCellValue('R'.$t, '');
  $sheet->setCellValue('S'.$t, $qty);
  $sheet->setCellValue('T'.$t, $dataForm);
  $sheet->setCellValue('U'.$t, $dataTo);
$i++;
$t++;}
 $diseño=0;
$buscarinfo=mysqli_query($con,"SELECT * FROM listascorte WHERE pn='$pn' AND tipo_cons LIKE 'JUMPER%'");
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
$corte=$row['tipo_cons'];
$cons=$row['cons'];
$tipo=$row['tipo'];
$awg=$row['aws'];
$color=$row['color'];
$tamano=$row['tamano'];
$stp1=$row['strip1'];
$terminal1=$row['terminal1'];
$sello1=$row['sello1'];
$nota1=$row['Nota1'];
$stp2=$row['strip2'];
$terminal2=$row['terminal2'];
$sello2=$row['sello2'];
$nota2=$row['Nota2'];
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
 $sheet->setCellValue('G'.$t, $stp1);
 $sheet->setCellValue('H'.$t, $terminal1);
 $sheet->setCellValue('I'.$t, $sello1);
 $sheet->setCellValue('J'.$t, $nota1);
 $sheet->setCellValue('K'.$t, "");
 $sheet->setCellValue('L'.$t, $stp2);
 $sheet->setCellValue('M'.$t, $terminal2);
 $sheet->setCellValue('N'.$t, $sello2);
 $sheet->setCellValue('O'.$t, $nota2);
 $sheet->setCellValue('P'.$t, '');
 $sheet->setCellValue('Q'.$t, $conector);
 $sheet->setCellValue('R'.$t, '');
 $sheet->setCellValue('S'.$t, $qty);
 $sheet->setCellValue('T'.$t, $dataForm);
 $sheet->setCellValue('U'.$t, $dataTo); 

$i++;
$t++;}
   $diseño=0;
$buscarinfo=mysqli_query($con,"SELECT * FROM listascorte WHERE pn='$pn' AND tipo_cons LIKE 'CORTE%'");
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
$corte=$row['tipo_cons'];
$cons=$row['cons'];
$tipo=$row['tipo'];
$awg=$row['aws'];
$color=$row['color'];
$tamano=$row['tamano'];
$stp1=$row['strip1'];
$terminal1=$row['terminal1'];
$sello1=$row['sello1'];
$nota1=$row['Nota1'];
$stp2=$row['strip2'];
$terminal2=$row['terminal2'];
$sello2=$row['sello2'];
$nota2=$row['Nota2'];
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
 $sheet->setCellValue('G'.$t, $stp1);
 $sheet->setCellValue('H'.$t, $terminal1);
 $sheet->setCellValue('I'.$t, $sello1);
 $sheet->setCellValue('J'.$t, $nota1);
 $sheet->setCellValue('K'.$t, "");
 $sheet->setCellValue('L'.$t, $stp2);
 $sheet->setCellValue('M'.$t, $terminal2);
 $sheet->setCellValue('N'.$t, $sello2);
 $sheet->setCellValue('O'.$t, $nota2);
 $sheet->setCellValue('P'.$t, '');
 $sheet->setCellValue('Q'.$t, $conector);
 $sheet->setCellValue('R'.$t, '');
 $sheet->setCellValue('S'.$t, $qty);
 $sheet->setCellValue('T'.$t, $dataForm);
 $sheet->setCellValue('U'.$t, $dataTo);

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

}else{   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listas de corte Activas</title>
</head>
<body>
    <form action="listascorte.php" method="POST">
        <label for="wo">Numero de parte</label>
    <input type="text" name="wo">
    <input type="submit" value="Buscar">
    </form>
</body>
</html>
<?php 
}
