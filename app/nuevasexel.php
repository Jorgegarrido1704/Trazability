<?php
require "conection.php";
require 'vendor/autoload.php'; 
date_default_timezone_set("America/Mexico_City");
$date=date("d-m-Y");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();$sheet = $spreadsheet->getActiveSheet();$t=2;
$sheet->setCellValue('A1', 'Date');
$sheet->setCellValue('B1', 'Part Num');
$sheet->setCellValue('C1', 'Client');
$sheet->setCellValue('D1', 'Rev');
$sheet->setCellValue('E1', 'Wo');
$sheet->setCellValue('F1', 'Sono');
$sheet->setCellValue('G1', 'Qty');
$sheet->setCellValue('H1', 'Where');
$sheet->setCellValue('I1', 'paro');
$sheet->setCellValue('J1', 'Test');
$buscarinfo=mysqli_query($con,"SELECT * FROM registro ORDER BY count ASC");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha'];
    $parte= $row['NumPart'];
    $Client= $row['cliente'];
    $rev= $row['rev'];
    $wo= $row['wo'];
    $po= $row['po'];
    $Qty= $row['Qty'];
    $count=$row['count'];
        if($count==2 or $count==3){
            $where='CUTTING';
        }elseif($count==4 or $count==5){
            $where='TERMINALS';
        }elseif($count==6 or $count==7){
            $where='ASSEMBLY';
        }elseif($count==8 or $count==9){
            $where='LOOMING';
        }elseif($count==10 or $count==11){
            $where='TESTING';
        }elseif($count==12 or $count==20){ 
            $where='SHIPPING';
        }elseif($count==13 or $count==14 or $count==16 or $count==17 or $count==18 or $count==19){
            $where='Engineery';
        }
        elseif($count==15){
            $where='SPECIAL WIRE';
        }

    
    $paro= $row['paro'];
    $info=$row['info'];
    $po=$row['po'];
    $busacartest=mysqli_query($con,"SELECT * FROM regsitrocalidad WHERE info='$info'");
    $rowscal=mysqli_num_rows($busacartest);
  $buscarpo=mysqli_query($con,"SELECT qty FROM po WHERE po = '$po'");
  while($rows=mysqli_fetch_array($buscarpo)){
  $cantpo=$rows['qty'];}
$sheet->setCellValue('A'.$t, $fecha);
$sheet->setCellValue('B'.$t, $parte);
$sheet->setCellValue('C'.$t, $Client);
$sheet->setCellValue('D'.$t, $rev);
$sheet->setCellValue('E'.$t, $wo);
$sheet->setCellValue('F'.$t, $po);
$sheet->setCellValue('G'.$t, $Qty);
$sheet->setCellValue('H'.$t, $where);
$sheet->setCellValue('I'.$t, $paro);
if($rowscal>$cantpo){
    $sheet->setCellValue('J'.$t, $cantpo."/".$cantpo);    
}else{
$sheet->setCellValue('J'.$t, $rowscal."/".$cantpo);
}
$t++;}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="WO Station '.$date.'.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


