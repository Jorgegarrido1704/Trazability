<?php 

require "../../../app/conection.php";
require "../Registros/index.php";
require "../../../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
//read cvs file
$pn=isset($_POST['pn'])?$_POST['pn']:"";
$rev=isset($_POST['rev'])?$_POST['rev']:"";
$cli=isset($_POST['cliente'])?$_POST['cliente']:"";
$creador=isset($_POST['creador'])?$_POST['creador']:"";
$date=intval(strtotime(date("d-m-Y H:i")));



if (!empty($pn) || !empty($rev) || !empty($cli) || !empty($creador)) {
    
    // Get the uploaded file's temporary name
    $fileCsv = $_FILES['file']['tmp_name'];

    // Ensure the file is uploaded and exists
    if (file_exists($fileCsv)) {

        try {
            // Load the Excel file (supports CSV, XLSX, etc.)
            $spreadsheet = IOFactory::load($fileCsv);

            // Get the active sheet
            $sheet = $spreadsheet->getActiveSheet();

            // Initialize an array to hold the data
            $data = [];
            $header = ['CIRCUIT', 'GA', 'COLOR', 'TYPE', 'FROM', 'TO','TIP','CONS'];
            
            // Loop through rows
            $rowIndex = 1; // Start from the first row
            foreach ($sheet->getRowIterator() as $row) {
                $rowData = [];
                // Loop through the columns of each row
                $cellIndex = 0;
                foreach ($row->getCellIterator() as $cell) {
                    $value = $cell->getValue();
                    $rowData[] = $value;

                    // If CIRCUIT column is empty (or null), stop processing
                    if ($cellIndex == 0 && (empty($value) || $value === null)) {
                        break 2; // Exit both loops
                    }
                    $cellIndex++;
                }

                // Store row data if it's not empty in CIRCUIT
                if (!empty($rowData[0])) {  // CIRCUIT is the first column
                    $data[] = $rowData;
                }
                $rowIndex++;
            }
            $reateDB=mysqli_query($con,"INSERT INTO `registromovimientoslistas`( `creadorLista`, `pn`, `cliente`, `rev`, `Tipodemovimiento`, `ultimaFechaRegistro`) VALUES ('$creador','$pn','$cli','$rev','Creacion de lista',$date)");
            if($reateDB){
                
                for ($i = 1; $i < count($data); $i++) {
                    $row = $data[$i];
                    $update = mysqli_query($con,"INSERT INTO listasing (creadorLista, client, pn, rev, corte, cons, tipo, calibre, color, longitud, term1, term2, estamp, fromPos, toPos, comment, categoria) VALUES ('$creador','$cli','$pn','$rev','-','$row[7]','$row[3]','$row[1]','$row[2]','0','-','-','$row[0]','$row[4]','$row[5]','-','$row[6]')");
                }
            }  
                
            

        } catch (Exception $e) {
            // Handle errors
            echo 'Error loading file: ' . $e->getMessage();
        }
    } else {
        echo "File does not exist.";
    }


}else{
   // echo '<pre>'.print_r($data).'</pre>';
}
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Carga de Datos</title>
</head>
<body>
    <hr>
    <form action="importarDatos.php" method="POST" enctype="multipart/form-data" class="form-group">
        <div class="input-group mb-3">
            <input type="file" name="file" id="file" accept=".csv" required>
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend"><span class="input-group-text" >Creador</span></div>
            
                <select name="creador" id="creador" class="form-control">
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="NA">Nancy Aldana</option>
                    <option value="PS">Paola Silva</option>
                    <option value="JC">Jesus Cervera</option>
                    <option value="JR">Jose Rodriguez</option>
                    <option value="JG">Jorge Garrido</option>
                    <option value="BS">Brandon Sanchez</option>
                    <option value="AS">Arturo Santos</option>
                    <option value="AV">Alejando Vargas</option>
                </select>   
            
      </div>
<!-- Cliente -->
<div class="input-group mb-3">
    <div class="input-group-prepend"><span class="input-group-text">Cliente</span></div>
    <select name="cliente" id="cliente" class="form-control" required>
                                              <option value="" disabled selected>Seleccionar</option>
                                              <option value="MORGAN OLSON">MORGAN OLSON</option>
                                              <option value="JOHN DEERE">JOHN DEERE</option>
                                              <option value="OP MOBILITY">OP MOBILITY</option>
                                              <option value="BROWN">BROWN</option>
                                              <option value="DUR-A-LIFT">DUR-A-LIFT</option>
                                              <option value="BERSTROMG">BERGSTROM</option>
                                              <option value="BLUE BIRD">BLUE BIRD</option>
                                                <option value="ATLAS">ATLAS</option>
                                                <option value="UTILIMASTER">UTILIMASTER</option>
                                                <option value="CALIFORNIA">CALIFORNIA</option>
                                                <option value="TICO MANUFACTURING">TICO MANUFACTURING</option>
                                                <option value="SPARTAN">SPARTAN</option>
                                                <option value="PHOENIX">PHOENIX</option>
                                                <option value="FOREST RIVER">FOREST RIVER</option>
                                                <option value="SHYFT">SHYFT</option>
                                                <option value="KALMAR">KALMAR</option>
                                                <option value="MODINE">MODINE</option>
                                                <option value="NILFISK">NILFISK</option>
                                                <option value="PLASTIC OMNIUM">PLASTIC OMNIUM</option>
                                                <option value="ZOELLER">ZOELLER</option>
                                                <option value="COLLINS">COLLINS</option>
                                                <option value="Proterra Powered LLC">Proterra Powered LLC.</option>
                                                </select>
</div>
<!-- Part Number -->
<div class="input-group mb-3">
    <div class="input-group-prepend"><span class="input-group-text">Numero de Parte</span></div>
    <input type="text" name="pn" id="pn" class="form-control" required>
</div>
<!-- Revision -->
<div class="input-group mb-3">
    <div class="input-group-prepend"><span class="input-group-text">Revision</span></div>
    <input type="text" name="rev" id="rev" class="form-control" required>
</div>
        <div class="input-group mb-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>       
    </form>

</body>
</html>
   