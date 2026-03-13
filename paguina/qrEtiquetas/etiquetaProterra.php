<?php
require '../conector.php';
require '../vendor/autoload.php';
session_start();
date_default_timezone_set("America/Mexico_City");

$today = date('Ymd');

use chillerlan\QRCode\QRCode;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<style>




.sheet{
    width:38.5mm; height:104.5mm;
   
    padding-top:25mm;
    padding-left:5mm;

}

.label{
    width:31.4mm;
    height:14.6mm;
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
    padding-left:3px;
    width:13mm;
    height:14.5mm;
}

.bloque2{
    width:16mm;
    height:14.5mm;
    padding-top:6px;
}

.qr img{
    width:10.5mm;
    height:10.5mm;
}

.smallbox{
    border:1px solid black;
    text-align:center;
    font-size:6px;
    margin-bottom:3px;
}

.smallbox1{
    border:1px solid black;
    text-align:center;
    font-size:7px;
    margin-bottom:3px;
}


#logo{
    padding-left:2px;
    width: 10mm;
    height: 2mm;
}

</style>

</head>

<body>
<?php for($i = 0; $i < 5; $i++){ 
    $consecutivoSerial=$i+1;
    if($i<10){
        $consecutivoSerial="00".$consecutivoSerial;
    }elseif($i<100){
        $consecutivoSerial="0".$consecutivoSerial;
    }
    $partNumber='300-1570-00-R01';
    $revision='A01';
    ?>
<div class="sheet">

<div class="label">

    <div class="row">

        <div class="bloque1">

            <div class="qr">
            <?php
            $data = '5703|'.$partNumber.'|'.$revision.'|'.$today.'|'.$consecutivoSerial;
            $qrcode = (new QRCode)->render($data);
            ?>
            <img src="<?php echo $qrcode; ?>">
            </div>

            <div class="logo"><img src="logo.jpg" alt="logo" id="logo"></div>

        </div>

        <div class="bloque2">

            <div class="smallbox">5703</div>

            <div class="smallbox1"><?php echo $partNumber."|".$revision;?></div>

            <div class="smallbox"><?php echo $today."|".$consecutivoSerial; ?></div>

        </div>

    </div>

</div>

</div>
<?php } ?>
</body>
</html>