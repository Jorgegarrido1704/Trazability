<?php
error_reporting(E_ALL);
ini_set('display_errors', '1'); // Solo para diagnóstico temporal
ini_set('log_errors', '1');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require "../../app/conection.php";

// Conexión en UTF-8 (necesario para acentos y caracteres especiales)
mysqli_set_charset($con, 'utf8mb4');

// Convierte cualquier texto a UTF-8 (CSV de Excel suele venir en Windows-1252)
function aUtf8(string $s): string {
    return mb_check_encoding($s, 'UTF-8')
        ? $s
        : mb_convert_encoding($s, 'UTF-8', 'Windows-1252');
}

$deltehoy = date("Y-m-d");
mysqli_query($con, "DELETE FROM pantallas WHERE fecha_up = '$deltehoy'");

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

    while ($rowCount < $maxRows && ($data = fgetcsv($handle, 0, ",")) !== false) {
        if (count($data) < 11) {
            continue;
        }

        // Convierte todas las celdas a UTF-8
        $data = array_map('aUtf8', $data);

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
header("Location: index.html");
exit;