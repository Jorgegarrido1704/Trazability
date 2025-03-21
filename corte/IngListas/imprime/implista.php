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
$sheet->getStyle('D4')->getFont()->setSize('18');
$sheet->setCellValue('D4', $creador);
$sheet->getStyle('H4')->getFont()->setSize('24');
$sheet->setCellValue('H4', $client);
$sheet->getStyle('E6')->getFont()->setSize('48');
$sheet->setCellValue('E6', $pn);
$sheet->getStyle('K6')->getFont()->setSize('48');
$sheet->setCellValue('K6', $rev);
$sheet->getStyle('L5')->getFont()->setSize('10');
$sheet->setCellValue('L5', $date);
$sheet->getStyle('L7')->getFont()->setSize('24');
$sheet->setCellValue('L7', '-');
$sheet->getStyle('P8')->getFont()->setSize('24');
$sheet->setCellValue('P8', 'pending');

$sheet->getStyle('A11:R700')->getFont()->setSize('16');
$sheet->getStyle('A1:R700')->getFont()->setBold(TRUE);
$sheet->getStyle('A1:R700')->getFont()->setname('Calibri');
$sheet->getStyle('A1:R700')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:R700')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:R700')->getAlignment()->setWrapText(true);

$buscarinfo=mysqli_query($con,"SELECT * FROM listasing WHERE pn='$pn' AND categoria = 1 order by cons ASC");
if(mysqli_num_rows($buscarinfo)>0){
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
}

$buscarjumper=mysqli_query($con,"SELECT * FROM listasing WHERE pn='$pn' AND categoria = 3 order by corte ASC");
if(mysqli_num_rows($buscarjumper)>0){
        $t+=1;
        $sheet->getStyle('H'.$t)->getFont()->setSize('36');
        $sheet->getStyle('H'.$t)->getFont()->setBold(TRUE);
        $sheet->getStyle('H'.$t)->getFont()->setname('Calibri');
        $sheet->getStyle('H'.$t)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
        $sheet->setCellValue('H'.$t, 'JUMPERS');
        $t+=1;
        $ultimo='';
    while($jumper=mysqli_fetch_array($buscarjumper)){
        $corte=$jumper['corte'];
        if($corte != $ultimo){
            $t+=1;
        }
        $cons=$jumper['cons'];
        $tipo=$jumper['tipo'];
        $awg=$jumper['calibre'];
        $color=$jumper['color'];
        $tamano=$jumper['longitud'];
        //strip 1 
        $stp1='-';
        $terminal1=$jumper['term1'];
        //$sello1=$jumper['sello1'];
        //$nota1=$jumper['Nota1'];
        $stp2='-';
        $terminal2=$jumper['term2'];
        //$sello2=$jumper['sello2'];
        //$nota2=$jumper['Nota2'];
        $conector=$jumper['estamp'];
        $dataForm=$jumper['fromPos'];
        $dataTo=$jumper['toPos'];
        $qty=1;
        $comment=$jumper['comment'];
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
        $t+=1;
        $ultimo=$corte;
    }
}
$buscartwst=mysqli_query($con,"SELECT * FROM listasing WHERE pn='$pn' AND categoria = 2  order by corte ASC");
if(mysqli_num_rows($buscartwst)>0){
        $t+=1;
        $sheet->getStyle('H'.$t)->getFont()->setSize('26');
        $sheet->getStyle('H'.$t)->getFont()->setBold(TRUE);
        $sheet->getStyle('H'.$t)->getFont()->setname('Calibri');
        $sheet->getStyle('H'.$t)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
        $sheet->setCellValue('H'.$t, 'Trenzados');
        $t+=2;
    while($twst=mysqli_fetch_array($buscartwst)){
        $corte=$twst['corte'];
       if($corte != $ultimo){
        $t+=1;
       }
        $cons=$twst['cons'];
        $tipo=$twst['tipo'];
        $awg=$twst['calibre'];
        $color=$twst['color'];
        $tamano=$twst['longitud'];
        //strip 1 
        $stp1='-';
        $terminal1=$twst['term1'];
        //$sello1=$twst['sello1'];
        //$nota1=$twst['Nota1'];
        $stp2='-';
        $terminal2=$twst['term2'];
        //$sello2=$twst['sello2'];
        //$nota2=$twst['Nota2'];
        $conector=$twst['estamp'];
        $dataForm=$twst['fromPos'];
        $dataTo=$twst['toPos'];
        $qty=1;
        $comment=$twst['comment'];
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
       $t++;   
       $ultimo=$corte;
    }
    }
    $buscarjumper=mysqli_query($con,"SELECT * FROM listasing WHERE pn='$pn' AND categoria = 4 order by corte ASC");
    if(mysqli_num_rows($buscarjumper)>0){
            $t+=1;
            $sheet->getStyle('H'.$t)->getFont()->setSize('24');
            $sheet->getStyle('H'.$t)->getFont()->setBold(TRUE);
            $sheet->getStyle('H'.$t)->getFont()->setname('Calibri');
            $sheet->getStyle('H'.$t)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            $sheet->getStyle('I'.$t)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            $sheet->setCellValue('H'.$t, 'CABLES ESPECIALES');
            $t+=2;
        while($jumper=mysqli_fetch_array($buscarjumper)){
            $corte=$jumper['corte'];
            if($corte != $ultimo){
                $t+=1;
            }
            $cons=$jumper['cons'];
            $tipo=$jumper['tipo'];
            $awg=$jumper['calibre'];
            $color=$jumper['color'];
            $tamano=$jumper['longitud'];
            //strip 1 
            $stp1='-';
            $terminal1=$jumper['term1'];
            //$sello1=$jumper['sello1'];
            //$nota1=$jumper['Nota1'];
            $stp2='-';
            $terminal2=$jumper['term2'];
            //$sello2=$jumper['sello2'];
            //$nota2=$jumper['Nota2'];
            $conector=$jumper['estamp'];
            $dataForm=$jumper['fromPos'];
            $dataTo=$jumper['toPos'];
            $qty=1;
            $comment=$jumper['comment'];
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
           $t++;   
           $ultimo=$corte; 
        }
    }

            $t+=1;
            $sheet->getStyle('E'.$t)->getFont()->setSize('24');
            $sheet->getStyle('E'.$t)->getFont()->setBold(TRUE);
            $sheet->getStyle('E'.$t)->getFont()->setname('Calibri');
            $sheet->getStyle('E'.$t)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            $sheet->setCellValue('E'.$t, 'Total MM ');
            $sheet->getStyle('F'.$t)->getFont()->setSize('16');
            $sheet->setCellValue('F'.$t, $total);






header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$pn.' REV '.$rev.'.xlsx"');
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
