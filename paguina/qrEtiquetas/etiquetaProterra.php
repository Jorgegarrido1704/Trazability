<?php
require '../conector.php';
require '../vendor/autoload.php';
session_start();
date_default_timezone_set("America/Mexico_City");

$today = date('mdY');

use chillerlan\QRCode\QRCode;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<style>




.sheet{
    width:38.5mm; height:162mm;
    border:1px solid black;

}

.label{
    width:35.5mm;
    height:17.5mm;
    border:1px solid black;
    border-radius:3mm;
    display:inline-block;
    padding:0.5mm;
    font-family:Arial;
    font-size:5px;
    box-sizing:border-box;
    margin:1mm;
}

.row{
    display:flex;
}

.bloque1{
    width:12mm;
    height:17.5mm;
}

.bloque2{
    width:14mm;
    height:17.5mm;
}

.qr img{
    width:12mm;
    height:12mm;
}

.smallbox{
    border:1px solid black;
    text-align:center;
    font-size:5px;
    margin-bottom:3px;
}

.smallbox1{
    border:1px solid black;
    text-align:center;
    font-size:6px;
    margin-bottom:3px;
}


img{
    width: 12mm;
    height: 5mm;
}

</style>

</head>

<body>
<?php for($i = 0; $i < 5; $i++){ ?>
<div class="sheet">

<div class="label">

    <div class="row">

        <div class="bloque1">

            <div class="qr">
            <?php
            $data = '5703|0000000|01|'.$today.'|001';
            $qrcode = (new QRCode)->render($data);
            ?>
            <img src="<?php echo $qrcode; ?>">
            </div>

            <div class="logo"><img src="logo.jpg" alt="logo"></div>

        </div>

        <div class="bloque2">

            <div class="smallbox">1234</div>

            <div class="smallbox1">300-1570-00-R01 | A01</div>

            <div class="smallbox">20250101 | 001</div>

        </div>

    </div>

</div>

</div>
<?php } ?>
</body>
</html>