<?php
require '../app/conection.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

function readExcel($filePath) {
    try {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = [];
        $columnToSkip = 2; // Columna C, índice 2

        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
            if ($rowIndex == 1) continue; // Omitir la primera línea (encabezados)

            $rowData = [];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $currentIndex = 0;
            foreach ($cellIterator as $cellIndex => $cell) {
                if ($currentIndex == $columnToSkip) {
                    $currentIndex++;
                    continue; // Omitir la columna C (índice 2)
                }
                $rowData[] = $cell->getValue();
                $currentIndex++;
            }

            if (!empty($rowData)) {
                $data[] = $rowData;
            }
        }
        return $data;
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        die('Error al cargar el archivo: ' . $e->getMessage());
    }
}

function insertBatch($batch, $con) {
    $values = [];
    foreach ($batch as $row) {
        $escapedValues = array_map(function($value) use ($con) {
            return mysqli_real_escape_string($con, $value);
        }, $row);
        $values[] = "('" . implode("', '", $escapedValues) . "')";
    }
    $sql = "INSERT INTO datos (part_num, rev, item, qty) VALUES " . implode(", ", $values);
    mysqli_query($con, $sql) or die(mysqli_error($con));
}

if (isset($_POST['upload'])) {
    if (is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
        $uploadedFile = $_FILES['excel_file']['tmp_name'];

        mysqli_query($con, "TRUNCATE TABLE datos");

        // Leer los datos del archivo Excel
        $excelData = readExcel($uploadedFile);

        $batchSize = 10000; // Tamaño del lote
        $batch = [];

        // Iterar a través de los datos y agruparlos en lotes
        foreach ($excelData as $row) {
            if ($row !== false) {
                $batch[] = $row;
                if (count($batch) == $batchSize) {
                    insertBatch($batch, $con);
                    $batch = []; // Reiniciar el lote
                }
            }
        }

        // Insertar el lote final si hay filas restantes
        if (count($batch) > 0) {
            insertBatch($batch, $con);
        }

        echo "Datos guardados exitosamente en la base de datos.";
        header("location:busqueda.php");
    } else {
        echo "No se ha subido ningún archivo.";
    }
} else {
    echo "No se ha enviado ningún archivo.";
}
?>
