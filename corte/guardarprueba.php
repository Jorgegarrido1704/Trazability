<?php
require "../app/conection.php";

ini_set('memory_limit', '1024M'); 
ini_set('max_execution_time', 0);

if (isset($_POST['upload'])) {
    if (is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
        $csvFile = $_FILES['excel_file']['tmp_name'];   
        if (($handle = fopen($csvFile, 'r')) !== FALSE) {
            try {
                $delete = mysqli_query($con,"DELETE FROM `datos`");

                mysqli_autocommit($con, FALSE); // Desactivar autocommit

                $insertQuery = "INSERT INTO `datos`( `part_num`, `rev`, `item`, `qty`) VALUES (?,?,?,?)";
                $stmt = mysqli_prepare($con, $insertQuery);

                if (!$stmt) {
                    die('Error en la preparación de la consulta: ' . mysqli_error($con));
                }

                mysqli_stmt_bind_param($stmt, 'ssss', $pn, $rev, $cons, $tipo);

                $rowCount = 0;
                $batchSize = 50000;

                // Saltar encabezado
                fgetcsv($handle);

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (count($data) >= 4) {
                        $pn   = $data[0];
                        $rev  = $data[1];
                        $cons = $data[2];
                        $tipo = $data[3];

                        mysqli_stmt_execute($stmt);
                        $rowCount++;
                    }

                    if ($rowCount % $batchSize == 0) {
                        mysqli_commit($con);
                        echo "Se han procesado $rowCount filas<br>";
                    }
                }

                mysqli_commit($con); // Confirmar lo pendiente
                fclose($handle);

                echo "Carga completa. Total de filas procesadas: $rowCount";
                header("location:busqueda.php");

            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        } else {
            echo "Error al abrir el archivo CSV.";
        }
    } else {
        echo "No se ha subido ningún archivo.";
    }
} else {
    echo "Formulario no enviado.";
}
?>
