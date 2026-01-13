<?php

require 'conector.php';
require 'vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};
try{
    $valores =[
"5703|185-4147|A.2|11212025|022",
"5703|185-4147|A.2|11212025|023",
"5703|185-4147|A.2|11212025|024",
"5703|185-4147|A.2|11212025|025",
"5703|185-4147|A.2|11212025|026",
"5703|185-4147|A.2|11212025|027",
"5703|185-4147|A.2|11212025|028",
"5703|185-4147|A.2|11212025|029",
"5703|185-4147|A.2|11212025|030",
"5703|185-4147|A.2|11212025|031",
"5703|185-4147|A.2|11212025|032",
"5703|185-4147|A.2|11212025|033",
"5703|185-4147|A.2|11212025|034",
"5703|185-4147|A.2|11212025|035",
"5703|185-4147|A.2|11212025|036"

];
$desc="HARNESS PRECHARGE; JUMPER TYPE M4(";
/*
$valores =[
'5703|199-6660|A01|11242025|001',
'5703|199-6660|A01|11242025|002',
'5703|199-6660|A01|11242025|003',
'5703|199-6660|A01|11242025|004',
'5703|199-6660|A01|11242025|005',
'5703|199-6660|A01|11242025|006',
'5703|199-6660|A01|11242025|007',
'5703|199-6660|A01|11242025|008',
'5703|199-6660|A01|11242025|009',
'5703|199-6660|A01|11242025|010',
'5703|199-6660|A01|11242025|011',
'5703|199-6660|A01|11242025|012',
'5703|199-6660|A01|11242025|013',
'5703|199-6660|A01|11242025|014',
'5703|199-6660|A01|11242025|015',
'5703|199-6660|A01|11242025|016',
'5703|199-6660|A01|11242025|053',
'5703|199-6660|A01|11242025|060',
'5703|199-6660|A01|11242025|077'

];
$desc="HARNESS; ASY; RESSISTOR; PRECHARGE; TYPE M4 (MASTER)";*/
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