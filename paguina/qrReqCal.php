<?php
require 'conector.php';
require 'vendor/autoload.php';
session_start();
date_default_timezone_set("america/Mexico_City");
$wo = isset($_POST['wo']) ? $_POST['wo'] : "";
$cons = isset($_POST['const']) ? $_POST['const'] : "";
$today = date('mdY');
if ($wo == "") {
    header("Location:qrs.php");
} elseif ($wo == '111') {
    header("Location:label.php?wo=2&np=1003622360&desc=WIRE HARNESS, I.P. CAB");
} elseif ($wo == '12') {
    header("Location:labelnew.php");
} else {
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
        if ($np == '1003647380' or $np == '1003617118' or $np == '1003622360') {
            header("Location:label.php?rev=$rev&np=$np&desc=$desc");
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




        if ($cuentas < 10) {
            $cuenta = "00" . $cuentas;
        } else if ($cuentas > 9 and  $cuentas < 100) {
            $cuenta = "0" . $cuentas;
        } else {
            $cuenta = $cuentas;
        }
    } else {
        header("Location:qrs.php");
    }
}

use chillerlan\QRCode\{QRCode, QROptions};

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
            display: flex;
            flex-direction: column;
            justify-content: space-between;

        }

        .row {
            margin-left: 35px;
            align-items: center;
            margin-top: 90px;
        }

        .qrs {
            width: 25mm;
            height: 24mm;
        }
    </style>
</head>

<body>
    <?php for ($inicio; $inicio < $cuentas; $inicio++) { ?>
        <div style="display:flex; width: 38mm; height: 162mm">
            <div id="label" class="container">
                <div class="row">
                    <div class="qrs">
                        <?php
                        $data = '5703|' . $np . '|' . $rev . '|' . $today . '|' . $inicio;
                        $qrcode = (new QRCode)->render($data);
                        ?>
                        <?php printf('<img src="%s" alt="QR Code" class="img-fluid" />', $qrcode); ?>
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