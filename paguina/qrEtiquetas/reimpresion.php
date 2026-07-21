<?php
require '../conector.php';
require '../../vendor/autoload.php';

date_default_timezone_set("America/Mexico_City");

// FUNCIÓN DEFINITIVA: Genera el DataMatrix usando sombras CSS (Garantiza nitidez y visibilidad)
// $sizeMM controla el tamaño FÍSICO final del código (ancho = alto), independiente de cuántos módulos tenga la matriz.
function generarDataMatrixHTML($texto, $sizeMM = 10) {
    try {
        $barcode = new \Com\Tecnick\Barcode\Barcode();
        $bobj = $barcode->getBarcodeObj(
            'DATAMATRIX', 
            $texto,       
            1, 
            1, 
            'black', 
            array(0, 0, 0, 0)
        )->setBackgroundColor('white');

        $grid = $bobj->getGridArray();
        if (!is_array($grid)) return '';

        $numCols = count($grid[0]);
        $numRows = count($grid);

        // Conversión mm -> px a 96dpi (estándar de render del navegador)
       // $mmToPx = 3.7795275591;
      //  $mmToPx = 3.8;
        $mmToPx = 3.8;
        // pixelSize EXACTO para que el total dé el tamaño deseado en mm,
        // sin importar cuántos módulos tenga la matriz generada
        $pixelSize = ($sizeMM * $mmToPx) / $numCols;

        $shadows = array();

        foreach ($grid as $y => $row) {
            foreach ($row as $x => $cell) {
                if ($cell == 1) {
                    // Calculamos la posición exacta de cada píxel negro
                    $posX = $x * $pixelSize;
                    $posY = $y * $pixelSize;
                    $shadows[] = "{$posX}px {$posY}px 0 #000000c1";
                }
            }
        }

        // El ancho y alto total dependen del tamaño de la matriz
        $totalWidth = $numCols * $pixelSize;
        $totalHeight = $numRows * $pixelSize;
        $shadowString = implode(', ', $shadows);

        // Creamos un contenedor con la sombra para dibujar el código
        $html = '<div style="position: relative; width: ' . $totalWidth . 'px; height: ' . $totalHeight . 'px; background: #FFFFFF; margin: auto;">';
        $html .= '<div style="position: absolute; left: -' . $pixelSize . 'px; top: -' . $pixelSize . 'px; width: ' . $pixelSize . 'px; height: ' . $pixelSize . 'px; background: transparent; box-shadow: ' . $shadowString . '; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important;"></div>';
        $html .= '</div>';

        return $html;
    } catch (\Exception $e) {
        return '<span style="color:red; font-size:4px;">Error: ' . $e->getMessage() . '</span>';
    }
}

try {
   $np='300-1922-00-R01';
   $rev='01';
   $today_qr='20260612';
   $consecutivoSerial='007';

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Impresión de Etiquetas</title>

<style>
    @page {
        size: 38.5mm 104.5mm;
        margin: 0;
    }

    .sheet {
        width: 38.5mm; 
        height: 104.5mm;
        padding-top: 21mm;
        padding-left: 5mm;
        box-sizing: border-box;
        page-break-after: always;
    }

    .label {
        width: 35.4mm;
        height: 20.3mm;
       
        display: inline-block;
        padding: 0.3mm;
        font-family: Arial, sans-serif;
        font-size: 5px;
        box-sizing: border-box;
        margin: 0.3mm;
        background-color: #FFFFFF !important;
    }

    .row {
        display: flex;
    }

    .bloque1 {
        width: 16mm;
        height: 16mm;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #FFFFFF !important;
    }

    .bloque2 {
        width: 19mm;
        height: 16.9mm;
        padding-top: 6px;   
    }

    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .label, .bloque1, .qr {
            background-color: #FFFFFF !important;
        }
    }

    .qr {
        display: block;
        background-color: #FFFFFF !important;
        padding: 4px; /* Margen de silencio esencial */
    }

    .smallbox {
        border: 1px solid black;
        text-align: center;
        font-size: 9px;
        margin-bottom: 1px;
        white-space: nowrap;
        overflow: hidden;
        background-color: #FFFFFF;
    }
    .smallbox1 {
        border: 1px solid black;
        text-align: center;
        font-size: 7px;
        margin-bottom: 1px;
        white-space: nowrap;
        overflow: hidden;
        background-color: #FFFFFF;
    }
</style>
</head>

<body>

<?php 

    $data = '5703|'.$np.'|'.$rev.'|'.$today_qr.'|'.$consecutivoSerial;
    $qrcode = generarDataMatrixHTML($data, 15); // <-- tamaño en mm aquí
?>
    <div class="sheet">
        <div class="label">
            <div class="row">
                <div class="bloque1">
                    <div class="qr">
                        <?php echo $qrcode; ?>
                    </div>
                </div>
                <div class="bloque2">
                    <div class="smallbox">5703</div>
                    <div class="smallbox1"><?php echo htmlspecialchars($np."|".$rev);?></div>
                    <div class="smallbox"><?php echo htmlspecialchars($today_qr."|".$consecutivoSerial); ?></div>
                </div>
            </div>
        </div>
    </div>


<script>
window.onload = function() {
    window.print();
    returnqr();
}

function returnqr() {
    setTimeout(function() {
        window.location.href = "../qrs.php";
    }, 10000);
}
</script>
</body>
</html>

<?php
} catch(Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}
?>