<?php
require "../app/conectionTraza.php";

$numerosDeParte = [];

if (isset($_POST['upload'])) {
    if (is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
        $csvFile = $_FILES['csv_file']['tmp_name'];   
        if (($handle = fopen($csvFile, 'r')) !== FALSE) {
            try {

                // Limpiar la tabla antes de insertar
                mysqli_query($con, "DELETE FROM datos_mps");

                // Preparar la consulta de inserción
                $insertQuery = "INSERT INTO datos_mps (`pn`, `dq`, `qtymps`, `desc`) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $insertQuery);

                if (!$stmt) {
                    die('Error en la preparación de la consulta: ' . mysqli_error($con));
                }

                // Variables para vincular los parámetros
                $pn = $rev = $cons = $tipo = '';
                mysqli_stmt_bind_param($stmt, 'ssis', $pn, $rev, $cons, $tipo);

                $rowCount = 0;
                $batchSize = 10000;

                // Leer la primera línea y eliminar BOM si existe
                $firstLine = fgets($handle);
                if (strpos($firstLine, "\xEF\xBB\xBF") === 0) {
                    $firstLine = substr($firstLine, 3);
                }

                // Procesar la primera línea si no está vacía
                if (!empty(trim($firstLine))) {
                    $data = str_getcsv($firstLine);
                    list($pn, $rev, $cons, $tipo) = $data;

                    // Validar que $rev sea fecha dd/mm/yyyy
                    if (!empty($rev)) {
                        $dateObj = DateTime::createFromFormat('d/m/Y', $rev);
                        $rev = $dateObj ? $dateObj->format('d/m/Y') : ''; // Si no es válida, dejar vacía
                    }

                    mysqli_stmt_execute($stmt);
                    $numerosDeParte[] = $pn;
                    $rowCount++;
                }

                // Procesar el resto del CSV
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    list($pn, $rev, $cons, $tipo) = $data;

                    // Validar fecha dd/mm/yyyy
                    if (!empty($rev)) {
                        $dateObj = DateTime::createFromFormat('m/d/Y', $rev);
                        $rev = $dateObj ? $dateObj->format('m/d/Y') : '';
                    }

                    mysqli_stmt_execute($stmt);
                    $numerosDeParte[] = $pn;
                    $rowCount++;

                    // Confirmar inserciones por lotes
                    if ($rowCount % $batchSize == 0) {
                        mysqli_commit($con);
                        echo "Se han procesado $rowCount filas<br>";
                    }
                }

                // Confirmar inserciones restantes
                mysqli_commit($con);
                echo "Carga completa. Total de filas procesadas: $rowCount";
                fclose($handle);

                // Redirigir a registros
                header('Location: registos.php');
                exit;

            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        }
    } else {
        echo "No se ha subido ningún archivo.";
    }
}
