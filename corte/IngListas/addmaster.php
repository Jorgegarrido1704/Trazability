<?php
require "../../app/conection.php";
require '../../app/vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$manuf=isset($_POST['manuf'])?$_POST['manuf']:"";
$pInt=isset($_POST['pInt'])?$_POST['pInt']:"";
$desc=isset($_POST['desc'])?$_POST['desc']:"";
$vendor=isset($_POST['vendor'])?$_POST['vendor']:"";
$unit=isset($_POST['unit'])?$_POST['unit']:"";
$loc=isset($_POST['loc'])?$_POST['loc']:"";

if(!empty($manuf) || !empty($pInt) ){
    

$archivo="ayuda reporte.xlsx";
$spreadsheet = IOFactory::load($archivo);
$hojas = $spreadsheet->getAllSheets();
$ultimaHoja = $hojas[0];
$filaUltima = $ultimaHoja->getHighestRow();
$columnaUltima = $ultimaHoja->getHighestColumn();
$filaNueva = $filaUltima + 1;
$ultimaHoja->setCellValue('A' . $filaNueva, $manuf);
$ultimaHoja->setCellValue('B' . $filaNueva, $pInt);
$ultimaHoja->setCellValue('C' . $filaNueva, $desc);
$ultimaHoja->setCellValue('D' . $filaNueva, $vendor);
$ultimaHoja->setCellValue('E' . $filaNueva, $unit);
$ultimaHoja->setCellValue('F' . $filaNueva, $loc);


$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save($archivo);
}else{ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>datos</title>
</head>
<body>
<form action="addmaster.php" method="POST">   
    <label for="manuf">Numero Manufactura</label>
    <input type="text" name="manuf">
    <label for="pInt">Parte Interna</label>
    <input type="text" name="pInt">
    <label for="desc">Descripcion</label>
    <input type="text" name="desc">
    <label for="vendor">Vendor</label>
    <input type="text" name="vendor">
    <label for="unit">Unit</label>
    <input type="text" name="unit">
    <label for="loc">Loc</label>
    <input type="text" name="loc">
    <input type="submit" value="enviar">
</form>
</body>
</html>
<?php
}