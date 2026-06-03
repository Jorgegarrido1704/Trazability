<?php
require '../conector.php';
require '../../vendor/autoload.php';

date_default_timezone_set("America/Mexico_City");

// Mover la función aquí arriba evita el Fatal Error por duplicación en el bucle
function generarDataMatrixHTML($texto) {
    try {
        $barcode = new \Com\Tecnick\Barcode\Barcode();
        $bobj = $barcode->getBarcodeObj(
            'DATAMATRIX', 
            $texto,       
            34,           
            34,           
            'black',    
            array(0, 0, 0, 0) 
        )->setBackgroundColor('white');

        return $bobj->getHtmlDiv(); 
    } catch (\Exception $e) {
        return '<span style="color:red;">Error: ' . $e->getMessage() . '</span>';
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
            // str_pad añade los ceros a la izquierda automáticamente (ej: 001, 015, 100)
            $consecutivo = str_pad($i, 3, "0", STR_PAD_LEFT);
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
    /* Configuración de la página para la impresora de etiquetas */
    @page {
        size: 38.5mm 104.5mm;
        margin: 0;
    }

    .sheet {
        width: 38.5mm; 
        height: 104.5mm;
        padding-top: 23mm;
        padding-left: 5mm;
        box-sizing: border-box;
        page-break-after: always; /* Crucial: Obliga a la impresora a saltar de etiqueta */
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
    }

    .row {
        display: flex;
    }

    .bloque1 {
        padding-left: 3px;
        width: 14mm;
        height: 15mm;
    }

    .bloque2 {
        width: 16mm;
        height: 14.9mm;
        padding-top: 6px;
    }

    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .qr, .qr div, .qr svg {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    }

    .qr {
        width: 11.0mm !important;
        height: 11.0mm !important;
        display: block;
        padding-top: 5px;
        margin-left: 5px;
    }

    /* Asegurar visibilidad de los vectores del Data Matrix */
    .qr div, .qr div * {
        visibility: visible !important;
    }

    .smallbox, .smallbox1 {
        border: 1px solid black;
        text-align: center;
        font-size: 7px;
        margin-bottom: 1px;
        white-space: nowrap;
        overflow: hidden;
    }

    #logo {
        padding-left: 2px;
        width: 11mm;
        height: 2.5mm;
    }
</style>
</head>

<body>

<?php 
// Bucle exclusivo para renderizar el HTML de las etiquetas
for($j = $inicio; $j <= $cuentas; $j++){ 
    $consecutivoSerial = str_pad($j, 3, "0", STR_PAD_LEFT);
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