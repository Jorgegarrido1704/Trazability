<?php

require 'conector.php';
require 'vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};
try{
$wo=isset($_POST['wo'])?$_POST['wo']:"";
$cons=isset($_POST['const'])?$_POST['const']:"";
$today = date('mdY');
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

    $buscar = mysqli_query($con, "SELECT * FROM `registro` where `wo` = '$wo' ");
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
        if($np=='300-1570-00-R01' or $np == '300-1922-00-R01' or $np=='300-157000R01-R'){
            header("Location:qrEtiquetas/etiquetaProterra.php?wo=$wo&cons=$cons");
            exit;
        }
      /*  if($np=='190-3559'){
            header("Location:qrEtiquetas/etiquetaConLogo.php?wo=$wo&cons=$cons");
            exit;
        }*/
        if ($np == '1003647380' or $np == '1003617118' or $np == '1003622360') {
            header("Location:label.php?rev=$rev&np=$np&desc=$desc");
            exit;
        }
        $regstroCuenta=mysqli_query($con,"SELECT cuenta FROM `consterm` where `dias` = '$today' order by id desc limit 1");
        if(mysqli_num_rows($regstroCuenta)>0){
            $rowsCuenta = mysqli_fetch_array($regstroCuenta);
            $inicio=$rowsCuenta['cuenta']+1;
            $cuentas=$rowsCuenta['cuenta']+$cons;
           
        }else{
            $inicio=1;
            $cuentas=$cons;
        }
         for($i=$inicio;$i<=$cuentas;$i++){
            if($i<10){
                $i="00".$i;
            }else if($i>9 and  $i<100){
                $i="0".$i;
            }
            $data = '5703|'.$np.'|'.$rev.'|'.$today.'|'.$i;
            $todayDate = date('Y-m-d');
                $insertarregistros=mysqli_query($con,"INSERT INTO `registroqrs`( `infoQr`, `CodigoIdentificaicon`, `fecha`) VALUES ('$info','$data','$todayDate')");
            }
        
        $buscarCuenta = mysqli_query($con, "SELECT * FROM `consterm` where `codigo` = '$info' ");
        if (mysqli_num_rows($buscarCuenta) > 0) {
            $rowsCuenta = mysqli_fetch_array($buscarCuenta);
            $total=$rowsCuenta['totalWo']-$cons;
                mysqli_query($con, "UPDATE `consterm` SET `dias` = '$today', `totalWo`= '$total' WHERE `consterm`.`codigo` = '$info' ");     
                mysqli_query($con, "UPDATE `consterm` SET `cuenta` = '$cuentas' WHERE `dias` = '$today' ");
        }else{
            $rest=$qty-$cons;
            mysqli_query($con, "INSERT INTO `consterm`( `pn`, `cuenta`, `rev`, `descri`, `dias`, `codigo`, `totalWo`) VALUES ('$np','$cuentas','$rev','$desc','$today','$info','$rest')");
            mysqli_query($con, "UPDATE `consterm` SET `cuenta` = '$cuentas' WHERE `dias` = '$today' ");
        }



if($cuentas<10){
    $cuenta="00".$cuentas;
}else if($cuentas>9 and  $cuentas<100){
    $cuenta="0".$cuentas;
}else{
    $cuenta=$cuentas;
}
}else{
    header("Location:qrs.php");
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>QR Codes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    #label {
        width: 38mm;
        height: 24mm;
        border-radius: 1px;
        box-sizing: border-box;
        display: inline-flex;
        margin-top: 75px;
        margin-left: 15px;



    }

    .row {
        display: block;
        align-items: flex-start;
        margin-bottom: 1px;
        margin-right: 2px;
    }

    .row1 {
        display: block;
        align-items: flex-start;
        margin-bottom: 1px;
    }

    .qrs {
        margin-top: 2mm;
        width: 20mm;
        height: 19mm;
    }

    .qrss {
        margin-top: 1mm;
        width: 20mm;
        height: 12mm;
    }

    .data-container {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding-top: 15px;
        margin-left: 2px;
    }

    .textarea-container {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;

        margin-left: 2px;
    }

    .datos {
        width: 30mm;
        border-radius: 1px;
        border: solid 1px #000;
        text-align: center;
        margin: 1px 0;
        padding-left: 1px;


        box-sizing: border-box;
    }

    textarea {
        width: 30mm;
        height: 16mm;
        border-radius: 1px;
        border: solid 1px #000;
        box-sizing: border-box;
        resize: none;
        font-size: xx-small;
        overflow: hidden;
        margin: 0;
    }

    #qrs_img {
        width: 18mm;
        height: 11mm;
        margin-left: 3-2px;
    }

    .textarea-container {
        margin-top: 0;
    }
    </style>
</head>

<body>
    <?php for ($inicio; $inicio <= $cuentas; $inicio++) { ?>
    <div style="display:flex; width: 38mm; height: 162mm">
        <div id="label" class="container">
            <div class="row">
                <div class="qrs">
                    <?php
                    if($inicio<10){
                        $datosRegistro="00".$inicio;
                    }else if($inicio>9 and  $inicio<100){
                        $datosRegistro="0".$inicio;
                    }else{
                        $datosRegistro=$inicio;
                    }
                $data = '5703|'.$np.'|'.$rev.'|'.$today.'|'.$datosRegistro;
                $qrcode = generarDataMatrixHTML($data, 7);
                ?>
                    <?php printf('<img src="%s" alt="QR Code" class="img-fluid" />', $qrcode);?>
                </div>
                <div class="qrss">
                    <img id="qrs_img" src="proterra_dark.png" alt="codigo" class="img-fluid">
                </div>

            </div>
            <div class="row1">
                <div class="data-container">
                    <h6 class="datos"><?php echo $np; ?>|<?php echo $rev; ?></h6>
                    <h6 class="datos"><?php echo $datosRegistro; ?></h6>
                </div>

                <div class="textarea-container">
                    <textarea name="datost" id="datost"><?php echo $desc; ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <script>
    window.onload = function() {
        print();
    }

    function returnqr() {
        setTimeout(function() {
            window.location.href = "qrs.php";
        }, 1000);
    }

    returnqr();
    </script>
</body>

</html>



<?php
}catch(Exception $e){
    echo "Error: " . $e->getMessage();
}
?>