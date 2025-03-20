<?php
require "../../../app/conection.php";
require '../../../app/vendor/autoload.php'; 

$numero=isset($_POST['wo'])?$_POST['wo']:"";
date_default_timezone_set("America/Mexico_City");
$date=date("d-m-Y");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
if(!empty($numero)){
$datosLista=explode(";",$numero);
//1=>pn,2=>rev,3=>cli,4=>creador
$pn=$datosLista[0];
$rev=$datosLista[1];
$client=$datosLista[2];
$creador=$datosLista[3];

$archivo="BASE_LISTAS.xlsx";
$spreadsheet = IOFactory::load($archivo);
$sheet = $spreadsheet->getActiveSheet();$t=11;$i=1;$total=0;$diseño=0;
$control="";
$count=1;

$sheet->setCellValue('D4', $creador);
$sheet->setCellValue('H4', $client);
$sheet->setCellValue('E6', $pn);
$sheet->setCellValue('K6', $rev);
$sheet->setCellValue('L5', $date);
$sheet->setCellValue('L7', '-');
$sheet->setCellValue('P8', 'pending');

$sheet->getStyle('A11:R700')->getFont()->setSize('12');
$sheet->getStyle('A11:R700')->getFont()->setBold(TRUE);
$sheet->getStyle('A11:R700')->getFont()->setname('Calibri');
$sheet->getStyle('A11:R700')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A11:R700')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A11:R700')->getAlignment()->setWrapText(true);

$buscarinfo=mysqli_query($con,"SELECT * FROM listasing WHERE pn='$pn' AND categoria = 1 order by cons ASC");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
    $corte=$row['corte'];
 $cons=$row['cons'];
 $tipo=$row['tipo'];
 $awg=$row['calibre'];
 $color=$row['color'];
 $tamano=$row['longitud'];
 //strip 1 
 $stp1='-';
 $terminal1=$row['term1'];
 //$sello1=$row['sello1'];
 //$nota1=$row['Nota1'];
 $stp2='-';
 $terminal2=$row['term2'];
 //$sello2=$row['sello2'];
 //$nota2=$row['Nota2'];
 $conector=$row['estamp'];
 $dataForm=$row['fromPos'];
 $dataTo=$row['toPos'];
 $qty=1;
 $comment=$row['comment'];
 $total+=$tamano;
  
$sheet->setCellValue('A'.$t, $corte);
$sheet->setCellValue('B'.$t, $cons);
$sheet->setCellValue('C'.$t, $tipo);
$sheet->setCellValue('D'.$t, $awg);
$sheet->setCellValue('E'.$t, $color);
$sheet->setCellValue('F'.$t, $tamano);
$sheet->setCellValue('G'.$t, $stp1);
$sheet->setCellValue('H'.$t, $terminal1);
$sheet->setCellValue('I'.$t, "");
$sheet->setCellValue('J'.$t, $stp2);
$sheet->setCellValue('K'.$t, $terminal2);
$sheet->setCellValue('L'.$t, "");
$sheet->setCellValue('M'.$t, $conector);
$sheet->setCellValue('N'.$t, "-");
$sheet->setCellValue('O'.$t, $qty);
$sheet->setCellValue('P'.$t, $dataForm);
$sheet->setCellValue('Q'.$t, $dataTo);
$sheet->setCellValue('R'.$t, $comment);


$i++;
$t++;}
 
   
   
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


   


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$pn.'.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

}else{ 
      $listas=mysqli_query($con,"SELECT DISTINCT pn,rev,client,creadorLista FROM listasing ");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listas de corte Activas</title>
</head>
<body>
    <form action="implista.php" method="POST">
        <label for="wo">Numero de parte</label>
        <select name="wo" id="wo">
            <option value="" disabled selected>Seleccionar</option>
            <?php
            while($row=mysqli_fetch_array($listas)){
                $pn=$row['pn'];
                $rev=$row['rev'];
                $cli=$row['client'];
                $creador=$row['creadorLista'];
                
                echo "<option value='$pn;$rev;$cli;$creador'>$pn ($rev)</option>"; 
            }
            ?>
        </select>
    <input type="submit" value="Buscar">
    </form>
</body>
</html>
<?php 
}
