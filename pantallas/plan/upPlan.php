<?php
error_reporting(E_ALL);
ini_set('display_errors', '1'); // Solo para diagnóstico temporal
ini_set('log_errors', '1');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require "../../app/conection.php";



$numerosDeParte = [];

if (!isset($_POST['upload'])) {
    exit("Formulario no enviado.");
}

if (!isset($_FILES['csv_file']) || !is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
    exit("No se ha subido ningún archivo válido.");
}

$csvFile = $_FILES['csv_file']['tmp_name'];
$transaccionActiva = false;

try {
    $handle = fopen($csvFile, 'rb');
    if ($handle === false) {
        throw new RuntimeException("Error al abrir el archivo CSV.");
    }

    // Si el archivo empieza con un BOM UTF-8, avanza después de él.
    if (fread($handle, 3) !== "\xEF\xBB\xBF") {
        rewind($handle);
    }

    // Lee y descarta el encabezado antes de insertar los datos.
    if (fgetcsv($handle, 0, ',', '"', '') === false) {
        throw new RuntimeException("El archivo CSV está vacío.");
    }

    mysqli_begin_transaction($con);
    $transaccionActiva = true;

    $insertQuery = "
        INSERT INTO pantallas (
            customer, np, wo, qty_wo, corte, ensa, loom, cal, emb, fecha, comentarios
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($con, $insertQuery);

    $pn = $rev = $cons = $tipo = $aws = $color = $tamano =
        $strip1 = $terminal1 = $strip2 = $terminal2 = '';

    mysqli_stmt_bind_param(
        $stmt,
        'sssssssssss',
        $pn, $rev, $cons, $tipo, $aws, $color, $tamano,
        $strip1, $terminal1, $strip2, $terminal2
    );

    $rowCount = 0;
    $maxRows = 600;

    while ($rowCount < $maxRows && ($data = fgetcsv($handle, 2000, ",")) !== false) {
        if (count($data) < 11) {
            continue;
        }

        if (trim($data[0]) === "") {
            break;
        }

        [
            $pn, $rev, $cons, $tipo, $aws, $color, $tamano,
            $strip1, $terminal1, $strip2, $terminal2
        ] = $data;

        mysqli_stmt_execute($stmt);
        $numerosDeParte[] = $pn;
        $rowCount++;
    }

    mysqli_commit($con);
    $transaccionActiva = false;
} catch (Exception $e) {
    if ($transaccionActiva) {
        mysqli_rollback($con);
    }

    echo "Error: " . $e->getMessage();
    return;
} finally {
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
    if (isset($handle) && is_resource($handle)) {
        fclose($handle);
    }
}

$numerosDeParte = array_unique($numerosDeParte);
header("Location: duplicados.php?np=" . urlencode(implode(',', $numerosDeParte)));
exit;
