<?php
// Asegúrate de haber ejecutado: composer require smalot/pdfparser
require '../vendor/autoload.php';

use Smalot\PdfParser\Parser;

// Procesamiento del archivo cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['plano_pdf'])) {
    
    $archivoTmp = $_FILES['plano_pdf']['tmp_name'];
    $nombreArchivo = $_FILES['plano_pdf']['name'];

    // Validar que sea un PDF
    $tipoArchivo = mime_content_type($archivoTmp);
    if ($tipoArchivo !== 'application/pdf') {
        die("Error: El archivo debe ser un PDF.");
    }

    try {
        // 1. Extraer el texto del PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($archivoTmp);
        $textoPdf = $pdf->getText();

        // 2. Extraer los componentes mediante Expresiones Regulares (Regex)
        // Este patrón busca: [Número de parte] + [AMP o PACKARD] + [Descripción]
        $patronBOM = '/([0-9A-Za-z\-]{4,15})\s+(AMP|PACKARD)\s+(TERMINAL.*?|CONNECTOR.*?)(?=\n|$)/i';
        preg_match_all($patronBOM, $textoPdf, $coincidencias, PREG_SET_ORDER);

        if (empty($coincidencias)) {
            die("No se encontraron materiales en el plano subido.");
        }

        // 3. Generar y descargar el archivo CSV (Excel)
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="BOM_Extraido_' . time() . '.csv"');
        
        $salida = fopen('php://output', 'w');
        
        // Agregar BOM (Byte Order Mark) para que Excel reconozca acentos y caracteres especiales UTF-8
        fputs($salida, "\xEF\xBB\xBF");
        
        // Escribir los encabezados
        fputcsv($salida, ['Supplier Part No.', 'Supplier Name', 'Description']);

        // Escribir los datos extraídos
        foreach ($coincidencias as $item) {
            $partNo = trim($item[1]);
            $supplier = trim($item[2]);
            $description = trim($item[3]);
            
            // Limpiar posibles comillas residuales de la lectura del PDF
            $description = str_replace('"', '', $description);

            fputcsv($salida, [$partNo, $supplier, $description]);
        }

        fclose($salida);
        exit; // Terminar el script para que no imprima el HTML debajo en el archivo CSV

    } catch (Exception $e) {
        die("Error al procesar el PDF: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extractor de BOM desde Planos PDF</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 50px; }
        .contenedor { max-width: 500px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { color: #333; text-align: center; }
        .form-group { margin-bottom: 20px; }
        input[type="file"] { display: block; width: 100%; padding: 10px; margin-top: 5px; }
        button { background-color: #007bff; color: white; border: none; padding: 10px 20px; width: 100%; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<div class="contenedor">
    <h2>Subir Plano para Extraer Materiales</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="plano_pdf">Selecciona el dibujo (PDF):</label>
            <input type="file" name="plano_pdf" id="plano_pdf" accept=".pdf" required>
        </div>
        <button type="submit">Procesar y Descargar Excel</button>
    </form>
</div>

</body>
</html>