<?php
require '../conector.php';
require '../../vendor/autoload.php';

date_default_timezone_set("America/Mexico_City");

// FUNCIÓN CORREGIDA: Genera una cuadrícula HTML pura píxel por píxel (Cero imágenes, cero negro falso)
function generarDataMatrixHTML($texto) {
    try {
        $barcode = new \Com\Tecnick\Barcode\Barcode();
        $bobj = $barcode->getBarcodeObj(
            'DATAMATRIX', 
            $texto,       
            1, // Cada módulo medirá exactamente 1 píxel base
            1, 
            'black', 
            array(0, 0, 0, 0) // Sin márgenes internos
        )->setBackgroundColor('white');

        // Extraemos la estructura interna (la matriz de unos y ceros: 1 = negro, 0 = blanco)
        $grid = $bobj->getGridArray();
        
        if (!is_array($grid)) {
            return '<span style="color:red; font-size:4px;">Error en matriz</span>';
        }

        // Construimos una tabla HTML ultra-compacta
        $html = '<table style="border-collapse: collapse; border: 0; margin: auto; padding: 0; background-white; line-height: 0; font-size: 0;" cellpading="0" cellspacing="0">';
        foreach ($grid as $row) {
            $html .= '<tr style="padding: 0; margin: 0; height: 1px;">'; // Ajusta a 2px si sale demasiado pequeño
            foreach ($row as $cell) {
                // Si la celda es 1 pintamos negro, si es 0 blanco absoluto
                $color = ($cell == 1) ? '#000000' : '#FFFFFF';
                $html .= '<td style="width: 1px; height: 1px; background-color: ' . $color . '; padding: 0; margin: 0;"></td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        return $html;
    } catch (\Exception $e) {
        return '<span style="color:red; font-size:4px;">Error: ' . $e->getMessage() . '</span>';
    }
}

try {
    $wo = isset($_GET['wo']) ? $_GET['wo'] : "";
    $cons = isset($_GET['cons']) ? intval($_GET['cons']) : 0;
    
    $today_db = date('mdY'); 
    $today_qr = date('Ymd'); 
    $todayDate = date('Y-m-d'); 

    $buscar = mysqli_query($con, "SELECT * FROM `registro` where `wo` = '$wo' limit 1");
    
    if (mysqli_num_rows($buscar) > 0) {
        $rows = mysqli_fetch_array($buscar);
        $np = $rows['NumPart'];
        $rev = $rows['rev'];
        $info = $rows['info'];
        $desc = $rows['description'];
        $qty = $rows['Qty'];

        if (substr($rev, 0, 4) == "PPAP" or substr($rev, 0, 4) == "PRIM") {
            $rev = substr($rev, 5);
        }
        $rev= strtoupper($rev);

        $regstroCuenta = mysqli_query($con, "SELECT cuenta FROM `consterm` where `dias` = '$today_db' order by id desc limit 1");
        if(mysqli_num_rows($regstroCuenta) > 0){
            $rowsCuenta = mysqli_fetch_array($regstroCuenta);
            $inicio = $rowsCuenta['cuenta'] + 1;
            $cuentas = $rowsCuenta['cuenta'] + $cons;
        } else {
            $inicio = 1;
            $cuentas = $cons;
        }

        // PROCESAMIENTO E INSERCIÓN EN BD
        for($i = $inicio; $i <= $cuentas; $i++){
            $consecutivo = str_pad($i, 3, "0", STR_PAD_LEFT);
            if($np == '300-157000R01-R'){
                $np = '300-1922-00-R01';
            }
            $dataDB = '5703|'.$np.'|'.$rev.'|'.$today_qr.'|'.$consecutivo;
            mysqli_query($con, "INSERT INTO `registroqrs`( `infoQr`, `CodigoIdentificaicon`, `fecha`) VALUES ('$info','$dataDB','$todayDate')");
        }
        
        $buscarCuenta = mysqli_query($con, "SELECT * FROM `consterm` where `codigo` = '$info' ");
        if (mysqli_num_rows($buscarCuenta) > 0) {
            $rowsCuenta = mysqli_fetch_array($buscarCuenta);
            $total = $rowsCuenta['totalWo'] - $cons;
            mysqli_query($con, "UPDATE `consterm` SET `dias` = '$today_db', `totalWo`= '$total' WHERE `consterm`.`codigo` = '$info' ");    
            mysqli_query($con, "UPDATE `consterm` SET `cuenta` = '$cuentas' WHERE `dias` = '$today_db' ");
        } else {
            $rest = $qty - $cons;
            mysqli_query($con, "INSERT INTO `consterm`( `pn`, `cuenta`, `rev`, `descri`, `dias`, `codigo`, `totalWo`) VALUES ('$np','$cuentas','$rev','$desc','$today_db','$info','$rest')");
            mysqli_query($con, "UPDATE `consterm` SET `cuenta` = '$cuentas' WHERE `dias` = '$today_db' ");
        }

    } else {
        throw new Exception("No se encontró el registro con la WO proporcionada.");
    }
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
        width: 31.4mm;
        height: 15.3mm;
        border: 1px solid black;
        border-radius: 2mm;
        display: inline-block;
        padding: 0.3mm;
        font-family: Arial, sans-serif;
        font-size: 5px;
        box-sizing: border-box;
        margin: 0.3mm;
        background-color: white;
    }

    .row {
        display: flex;
    }

    /* Contenedor del QR modificado a tamaño fijo para evitar desbordes */
    .bloque1 {
        width: 13mm;
        height: 14mm;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: white !important;
    }

    .bloque2 {
        width: 17mm;
        height: 14.9mm;
        padding-top: 6px;
    }

    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .label, .bloque1, .qr, table, td {
            background-color: white !important;
        }
    }

    /* Área del QR aislada */
    .qr {
        display: inline-block;
        background-color: white !important;
        padding: 4px; /* Zona de silencio obligatoria */
        box-sizing: border-box;
    }

    .smallbox {
        border: 1px solid black;
        text-align: center;
        font-size: 7px;
        margin-bottom: 1px;
        white-space: nowrap;
        overflow: hidden;
    }
    .smallbox1 {
        border: 1px solid black;
        text-align: center;
        font-size: 5px;
        margin-bottom: 1px;
        white-space: nowrap;
        overflow: hidden;
    }
</style>
</head>

<body>

<?php 
for($j = $inicio; $j <= $cuentas; $j++){ 
    if($j < 10){
        $consecutivoSerial = "00".$j;
    }
    elseif($j < 100) {
        $consecutivoSerial = "0".$j;
    } else {
        $consecutivoSerial = $j;
    }
    $data = '5703|'.$np.'|'.$rev.'|'.$today_qr.'|'.$consecutivoSerial;
    $qrcode = generarDataMatrixHTML($data);
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
<?php } ?>

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
