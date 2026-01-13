<?php

require 'conector.php';
require 'vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};
try{
   $valores =[
    "10JN097-119PM003",
    "10JN097-118PM003",
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
       width: 38mm;
        height: 12mm;
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
       
        text-align: center;
        margin: 1px 0;
        padding-left: 1px;


        box-sizing: border-box;
    }

    textarea {
        width: 37mm;
        height: 11mm;
        border-radius: 1px;
       
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
               

            </div>
            <div class="row">

                <div class="textarea-container">
                    <textarea name="datost" id="datost"><?php echo $valor; ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <script>
    window.onload = function() {
        print();
    }

   
    </script>
</body>

</html>



<?php
}catch(Exception $e){
    echo "Error: " . $e->getMessage();
}
?>