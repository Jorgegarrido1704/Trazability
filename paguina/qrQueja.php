<?php

require 'conector.php';
require 'vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};
try{
$valores =[
"5703|199-4942|A.2|12102025|002|HARNESS, ASY, RESSISTOR, PRECHARGE, TPM4",
"5703|199-4942|A.2|12102025|008|HARNESS, ASY, RESSISTOR, PRECHARGE, TPM4",           
"5703|199-4942|A.2|12102025|004|HARNESS, ASY, RESSISTOR, PRECHARGE, TPM4",           
"5703|199-4942|A.2|12102025|001|HARNESS, ASY, RESSISTOR, PRECHARGE, TPM4",           
"5703|199-4942|A.2|12102025|005|HARNESS, ASY, RESSISTOR, PRECHARGE, TPM4",           
"5703|199-4942|A.2|12102025|007|HARNESS, ASY, RESSISTOR, PRECHARGE, TPM4",           
"5703|199-4942|A.2|12102025|003|HARNESS, ASY, RESSISTOR, PRECHARGE, TPM4",           
"5703|199-4942|A.2|12102025|010|HARNESS, ASY, RESSISTOR, PRECHARGE, TPM4",         
"5703|199-4942|A.2|12102025|009|HARNESS, ASY, RESSISTOR, PRECHARGE, TPM4",           
"5703|199-4942|A.2|12102025|006|HARNESS, ASY, RESSISTOR, PRECHARGE, TPM4"



]
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
    <?php foreach ($valores as $valor) {?>
    <div style="display:flex; width: 38mm; height: 162mm">
        <div id="label" class="container">
            <div class="row">
                <div class="qrs">
                    <?php
                   
                $data = $valor;
                $registros=explode("|",$data);
                $np=$registros[1]."|".$registros[2];
                $qrcode = (new QRCode)->render($data);
                ?>
                    <?php printf('<img src="%s" alt="QR Code" class="img-fluid" />', $qrcode);?>
                </div>
                <div class="qrss">
                    <img id="qrs_img" src="proterra_dark.png" alt="codigo" class="img-fluid">
                </div>

            </div>
            <div class="row1">
                <div class="data-container">
                    <h6 class="datos"><?php echo $np; ?></h6>
                    <h6 class="datos"><?php echo $registros[4]; ?></h6>
                </div>

                <div class="textarea-container">
                    <textarea name="datost" id="datost"><?php echo $registros[5]; ?></textarea>
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
        }, 10000);
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