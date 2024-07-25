<?php
require "../app/conection.php";

if (isset($_POST['upload'])) {
    if (is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
        $csvFile = $_FILES['csv_file']['tmp_name'];   
        if (($handle = fopen($csvFile, 'r')) !== FALSE) {
            // Conectar a la base de datos
            try {
                // Preparar la consulta de inserción
                $insertQuery = "INSERT INTO listascorte (`pn`, `cons`, `tipo`, `aws`, `color`, `tamano`, `strip1`, `terminal1`, `strip2`, `terminal2`, `conector`, `dataFrom`, `dataTo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $insertQuery);

                if (!$stmt) {
                    die('Error en la preparación de la consulta: ' . mysqli_error($con));
                }

                // Variables para vincular los parámetros
                $pn = $cons = $tipo = $aws = $color = $tamano = $strip1 = $terminal1 = $strip2 = $terminal2 = $conector = $dataFrom = $dataTo = '';

                // Vincular los parámetros
                mysqli_stmt_bind_param($stmt, 'sssssssssssss', $pn, $cons, $tipo, $aws, $color, $tamano, $strip1, $terminal1, $strip2, $terminal2, $conector, $dataFrom, $dataTo);

                // Contador de filas procesadas
                $rowCount = 0;
                $batchSize = 10000;

                // Leer e insertar datos en bloques
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    // Asignar los valores a las variables vinculadas
                    list($pn, $cons, $tipo, $aws, $color, $tamano, $strip1, $terminal1, $strip2, $terminal2, $conector, $dataFrom, $dataTo) = $data;

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
                header("location:registro.php");
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
