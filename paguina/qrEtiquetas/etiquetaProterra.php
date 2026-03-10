<?php
require '../conector.php';
require '../vendor/autoload.php';
session_start();
date_default_timezone_set("america/Mexico_City");
//$wo=isset($_POST['wo'])?$_POST['wo']:"";
$today = date('mdY');

use chillerlan\QRCode\{QRCode, QROptions};
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<style>

    @page{
        size: letter;
        margin: 10mm;
    }

    .sheet{
        width: 216mm;
    }

    .label{
        width:25.4mm;
        height:12.7mm;
        border:1px solid black;
        border-radius:3mm;
        display:inline-block;
        margin:5mm;
        padding:1mm;
        font-family: Arial;
        font-size:6px;
        box-sizing:border-box;
    }

    .row{
        display:flex;
        justify-content:space-between;
    }

    .qr{
        width:8.5mm;
        height:8.5mm;
        background:#ddd;
    }

    .smallbox{
        border:1px solid black;
        padding:0.4mm;
        text-align:center;
        font-size:5px;
    }
    .smallbox1{
        border:1px solid black;
        padding:0.4mm;
        text-align:center;
        font-size:4px;
    }


    .logo{
        font-size:5px;
        color:#555;
    }

</style>
</head>

<body>

<div class="sheet">

    <div class="label">

    <div class="row">
    <div class="qr">
        <?php
                    $data = '5703|0000000|01|'.$today.'|001';
                    $qrcode = (new QRCode)->render($data);
                    ?>
                    <?php printf('<img src="%s" alt="QR Code" class="img-fluid" />', $qrcode);?> 
    </div>

    <div>
    <div class="smallbox">1234</div>
    <div class="smallbox1">300-1570-00-R01|A01</div>
    <div class="smallbox">20250101|001</div>
    </div>

    </div>
    <div class="logo">PROTERRA</div>
    </div>


</div>

</body>
</html>