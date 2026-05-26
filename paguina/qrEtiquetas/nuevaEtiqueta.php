<?php
require '../../vendor/autoload.php';
session_start();
date_default_timezone_set("America/Mexico_City");

// Eliminamos los "use" de BarcodeBakery para limpiar el script

try {
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<style>
    .sheet{
        width:85mm; height:97mm;
        padding-top:1mm;
        padding-left:2mm;
    }

    .label{
        width:24.5mm;
        height:24.5mm;
        display:inline-block;
        padding-left:10px;
        padding-right:15px;
        font-family:Arial;
        font-size:7px;
        box-sizing:border-box;
        margin:0.3mm;
    }

    .row{
        display:flex;
    }
    /* 1. Forzar al navegador a imprimir fondos y gráficos vectoriales */
@media print {
    * {
        -webkit-print-color-adjust: exact !important; /* Chrome, Edge, Safari */
        print-color-adjust: exact !important;         /* Firefox */
    }
    
    .qr, .qr div, .qr svg {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
}

/* 2. Asegurar que los pequeños bloques (rectángulos) del SVG no se oculten */
.qr div, .qr div * {
    visibility: visible !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
}

   /* Forzamos a que el contenedor y el SVG sean visibles y midan lo correcto */
        .qr div, .qr div * {
            visibility: visible !important;
        }

        .qr  {
            width: 10.5mm !important;
            height: 10.5mm !important;
            display: block;
            margin-left : 7px;
           
        }
        
    .smallbox{
        text-align:center;
        font-size:10px;
        margin-bottom:1px;
        padding-top:5px;
    }
</style>
</head>
<body>

<?php
// Función modificada para retornar la estructura HTML/SVG nativa de la librería libre
function generarDataMatrixHTML($texto) {
    try {
        $barcode = new \Com\Tecnick\Barcode\Barcode();
        
        // El método correcto es getBarcodeObj
        $bobj = $barcode->getBarcodeObj(
            'DATAMATRIX', // Tipo de código
            $texto,       // Texto a codificar
            -3,           // Ancho (escala relativa)
            -3,           // Alto (escala relativa)
            'black',      // Color de los módulos
            array(-1, -1, -1, -1) // Márgenes limpios
        );

        // Retorna el div con el código en formato SVG vectorial integrado
        return $bobj->getHtmlDiv(); 
    } catch (\Exception $e) {
        return '<span style="color:red;">Error al generar: ' . $e->getMessage() . '</span>';
    }
}
?>

<div class="sheet">
    <div class="row">
        <div class="col-4" id="etiqueta1">
            <div class="label">
                <div class="smallbox"><strong>KB030-POS</strong></div>
                <div class="qr">
                    <?php echo generarDataMatrixHTML('KB030-POS'); ?>
                </div>
            </div>            
        </div>
        <div class="col-4" id="etiqueta2">
            <div class="label">
                <div class="smallbox"><strong>KB030-POS</strong></div>
                <div class="qr">
                    <?php echo generarDataMatrixHTML('KB030-POS'); ?>
                </div>
            </div>            
        </div>
        <div class="col-4" id="etiqueta3">
            <div class="label">
                <div class="smallbox"><strong>KB030-POS</strong></div>
                <div class="qr">
                    <?php echo generarDataMatrixHTML('KB030-POS'); ?>
                </div>
            </div>            
        </div>
    </div>
</div>
<div class="sheet">
    <div class="row">
        <div class="col-4" id="etiqueta1">
            <div class="label">
                <div class="smallbox"><strong>KB031-NEG</strong></div>
                <div class="qr">
                    <?php echo generarDataMatrixHTML('KB031-NEG'); ?>
                </div>
            </div>            
        </div>
        <div class="col-4" id="etiqueta2">
            <div class="label">
                <div class="smallbox"><strong>KB031-NEG</strong></div>
                <div class="qr">
                    <?php echo generarDataMatrixHTML('KB031-NEG'); ?>
                </div>
            </div>            
        </div>
        <div class="col-4" id="etiqueta3">
            <div class="label">
                <div class="smallbox"><strong>KB031-NEG</strong></div>
                <div class="qr">
                    <?php echo generarDataMatrixHTML('KB031-NEG'); ?>
                </div>
            </div>            
        </div>
    </div>
</div>
<script>
window.onload = function() {
    print();
}

function returnqr() {
    setTimeout(function() {
        window.location.href = "nuevaEtiqueta.php";
    }, 10000);
}

returnqr();
</script>

</body>
</html>

<?php
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>