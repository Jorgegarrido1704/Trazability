<?php
require "../app/conectionTraza.php";

$numerosDeParte = [];

if (isset($_POST['upload'])) {
    if (is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
        $csvFile = $_FILES['csv_file']['tmp_name'];   
        if (($handle = fopen($csvFile, 'r')) !== FALSE) {
            // Conectar a la base de datos
            try {

                mysqli_query($con, "DELETE FROM datos_mps");
                // Preparar la consulta de inserción
                $insertQuery = "INSERT INTO datos_mps ( `pn`, `dq`, `qtymps`, `desc`) VALUES (?,?, ?, ?)";
                $stmt = mysqli_prepare($con, $insertQuery);

                if (!$stmt) {
                    die('Error en la preparación de la consulta: ' . mysqli_error($con));
                }

                // Variables para vincular los parámetros
                $pn =$rev=$cons =$tipo= '';

                // Vincular los parámetros
                mysqli_stmt_bind_param($stmt, 'ssss', $pn, $rev, $cons, $tipo);

                // Contador de filas procesadas
                $rowCount = 0;
                $batchSize = 10000;

                // Leer la primera línea y eliminar el BOM si está presente
                $firstLine = fgets($handle);
                if (strpos($firstLine, "\xEF\xBB\xBF") === 0) {
                    $firstLine = substr($firstLine, 3);
                }

                // Convertir la primera línea en un array si no está vacía
                if (!empty($firstLine)) {
                    $data = str_getcsv($firstLine);
                    list($pn,$rev, $cons, $tipo) = $data;
                    mysqli_stmt_execute($stmt);
                    $numerosDeParte[] = $pn;
                    $rowCount++;
                }

                // Leer e insertar datos en bloques
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    // Asignar los valores a las variables vinculadas

                    list($pn,$rev, $cons, $tipo) = $data;
                    $numerosDeParte[] = $pn;
                    // Ejecutar la consulta de inserción
                    mysqli_stmt_execute($stmt);
                    $rowCount++;

                    // Confirmar las inserciones después de cada lote
                    if ($rowCount % $batchSize == 0) {
                        mysqli_commit($con);
                        echo "Se han procesado $rowCount filas<br>";
                    }
                }

                // Confirmar las inserciones restantes
                mysqli_commit($con);
                echo "Carga completa. Total de filas procesadas: $rowCount";
                fclose($handle);
                
                header('Location: registos.php');
                
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }

        }}}