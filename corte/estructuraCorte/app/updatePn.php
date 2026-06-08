<?php
require "conection.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$removertdos = mysqli_query($con, "DELETE FROM estructuracortetiempos");

if (!isset($_POST['upload'])) {
    exit("Formulario no enviado.");
}

if (!isset($_FILES['csv_file']) || !is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
    exit("No se ha subido ningún archivo válido.");
}

$csvFile = $_FILES['csv_file']['tmp_name'];

try {

    $handle = fopen($csvFile, 'r');
    if (!$handle) {
        throw new Exception("Error al abrir el archivo CSV.");
    }

    mysqli_begin_transaction($con);

    $insertQuery = "
        INSERT INTO estructuracortetiempos
        (`np`,`qty`)
        VALUES (?,?)
    ";

    $stmt = mysqli_prepare($con, $insertQuery);

    $pn = $rev = '';

    mysqli_stmt_bind_param(
        $stmt,
        'ss',
        $pn, $rev
    );

    $rowCount = 0;
    $maxRows = 600;

    // Leer primera línea (remover BOM)
    $firstLine = fgets($handle);

    if ($firstLine !== false) {

        if (strpos($firstLine, "\xEF\xBB\xBF") === 0) {
            $firstLine = substr($firstLine, 3);
        }

        $data = str_getcsv($firstLine);

        if (count($data) >= 2) {

            $pn = trim($data[0]);

            if ($pn !== "") {

                list(
                    $pn,$rev
                ) = $data;

                mysqli_stmt_execute($stmt);

                
                $rowCount++;
            }
        }
    }

    // Leer máximo 600 filas
    while (($data = fgetcsv($handle, 2000, ",")) !== false) {

        // detener si llega a 600 filas
        if ($rowCount >= $maxRows) {
            break;
        }

        // validar columnas
        if (count($data) < 2) {
            continue;
        }

        $pn = trim($data[0]);

        // detener si pn vacío
        if ($pn === "") {
            break;
        }

        list(
            $pn,$rev
        ) = $data;

        mysqli_stmt_execute($stmt);

       
        $rowCount++;
    }

    mysqli_commit($con);

    mysqli_stmt_close($stmt);
    fclose($handle);


   header("Location: ../index.html" );
    exit;

} catch (Exception $e) {

    mysqli_rollback($con);

    if (isset($stmt)) mysqli_stmt_close($stmt);
    if (isset($handle)) fclose($handle);

    echo "Error: " . $e->getMessage();
}
?>