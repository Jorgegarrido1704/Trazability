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
                echo '<pre>'.print_r($data).'</pre>';
                for ($i = 1; $i < count($data); $i++) {
                    $row = $data[$i];
                    $update = mysqli_query($con,"INSERT INTO listasing (creadorLista, client, pn, rev, corte, cons, tipo, calibre, color, longitud, term1, term2, estamp, fromPos, toPos, comment, categoria) VALUES ($creador,$cli,$pn,$rev,'-',$row[7],$row[3],$row[1],$row[2],'0','-','-',$row[0],$row[4],$row[5],'-',$row[6])");
                }
            }  
                
            

        } catch (Exception $e) {
            // Handle errors
            echo 'Error loading file: ' . $e->getMessage();
        }
    } else {
        echo "File does not exist.";
    }

header("Location: ../Registros/index.php?db='Datos cargados con exito'");


}else{
    header("Location: ../Registros/importarDatos.php?db='Datos no cargados'");
}
    ?>
