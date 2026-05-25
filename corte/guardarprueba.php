<?php
require "../app/conection.php";
// Asegúrate de requerir el autoloader de Composer para que cargue la librería
require "../vendor/autoload.php"; 

use PhpOffice\PhpSpreadsheet\IOFactory;

ini_set('memory_limit', '1024M'); 
ini_set('max_execution_time', 0);

if (isset($_POST['upload'])) {
    if (is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
        $excelFile = $_FILES['excel_file']['tmp_name'];   
        
        try {
            // Cargar el archivo XLSX de forma optimizada (solo lectura)
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($excelFile);
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Limpiar la tabla antes de la nueva carga
            mysqli_query($con, "DELETE FROM `datos`");
            mysqli_autocommit($con, FALSE); // Desactivar autocommit para inserción masiva

            $insertQuery = "INSERT INTO `datos` (`part_num`, `rev`, `item`, `qty`) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $insertQuery);

            if (!$stmt) {
                die('Error en la preparación de la consulta: ' . mysqli_error($con));
            }

            mysqli_stmt_bind_param($stmt, 'ssss', $pn, $rev, $cons, $tipo);

            $rowCount = 0;
            $batchSize = 5000; // Ajustamos el tamaño del bloque para balancear rendimiento

            // Recorrer las filas del archivo de Excel
            foreach ($worksheet->getRowIterator() as $row) {
                $rowIndex = $row->getRowIndex();
                
                // Saltar la primera fila (encabezados: BOMNO, REV, PARTNO, QTY)
                if ($rowIndex == 1) {
                    continue;
                }

                // Obtener el valor de las 4 columnas específicas (A, B, C, D)
                $pn   = trim((string)$worksheet->getCell('A' . $rowIndex)->getValue()); // BOMNO
                $rev  = trim((string)$worksheet->getCell('B' . $rowIndex)->getValue()); // REV
                //la columna C no se usa para la inserción, pero la obtenemos por si se necesita para validaciones futuras
                $cons = trim((string)$worksheet->getCell('D' . $rowIndex)->getValue()); // PARTNO
                $tipo = trim((string)$worksheet->getCell('E' . $rowIndex)->getValue()); // QTY

                // Si la fila está completamente vacía (por ejemplo, el final del archivo), rompemos el ciclo
                if (empty($pn) && empty($rev) && empty($cons)) {
                    continue;
                }

                // Limpieza de caracteres especiales ocultos si aplica
                $pn   = str_replace("\xA0", " ", $pn);
                $rev  = str_replace("\xA0", " ", $rev);
                $cons = mb_convert_encoding(str_replace("\xA0", " ", $cons), 'UTF-8', 'UTF-8');
                $tipo = str_replace("\xA0", " ", $tipo);

                mysqli_stmt_execute($stmt);
                $rowCount++;

                // Guardar bloques en la base de datos para no saturarla
                if ($rowCount % $batchSize == 0) {
                    mysqli_commit($con);
                }
            }

            mysqli_commit($con); // Confirmar los últimos registros
            mysqli_stmt_close($stmt);

            // Redirección exitosa
            header("location:busqueda.php");
            exit();

        } catch (Exception $e) {
            echo 'Error al procesar el archivo Excel: ' . $e->getMessage();
        }
    } else {
        echo "No se ha subido ningún archivo válido.";
    }
} else {
    echo "Formulario no enviado.";
}
?>