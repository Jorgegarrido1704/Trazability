<?php
require '../app/conection.php';

function readCSV($csvFile){
    $file_handle = fopen($csvFile, 'r');
    $line_of_text = [];
    while (!feof($file_handle)) {
        $line_of_text[] = fgetcsv($file_handle, 1024);
    }
    fclose($file_handle);
    return $line_of_text;
}

mysqli_query($con, "TRUNCATE TABLE datos");
// Leer el archivo CSV y extraer los datos
$csvFile = 'datos.csv';
$csvData = readCSV($csvFile);

$batchSize = 10000; // Tamaño del lote
$batch = [];

// Iterar a través de los datos y agruparlos en lotes
foreach ($csvData as $row) {
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

echo "Datos insertados exitosamente en la base de datos.";
header("location:busqueda.php");

// Función para insertar un lote de datos
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
?>
