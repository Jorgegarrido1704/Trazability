<?php
require '../conector.php';
require '../vendor/autoload.php';
session_start();
date_default_timezone_set("America/Mexico_City");

use chillerlan\QRCode\{QRCode, QROptions};

try{
   
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<style>
    .sheet{
        width:85mm; height:104.5mm;
    
        padding-top:1mm;
        padding-left:2mm;

    }

    .label{
        width:24.5mm;
        height:24.5mm;
        border:1px solid black;
        border-radius:1mm;
        display:inline-block;
        padding-left:10px;
        padding-right:15px;
        font-family:Arial;
        font-size:7px;
        box-sizing:border-box;
        margin:0.3mm;
    }

    .row{
        display:flex;
    }

   

    .qr img{
        width:12mm;
        height:12mm;
        align:center;
        text-align:center;
        padding-left:16px;
    }

    .smallbox{
        
        text-align:center;
        font-size:7px;
        margin-bottom:1px;
        padding-top:5px;
    }

    .smallbox1{
        border:1px solid black;
        text-align:center;
        font-size:7px;
        margin-bottom:1px;
    }

    #etiqueta{
        padding-left:10px;
        padding-right:8px;
    }
    #logo{
        padding-left:2px;
        width: 11mm;
        height: 2.5mm;
    }

</style>

</head>

<body>

<div class="sheet">



    <div class="row">

        <div class="col-4 " id="etiqueta">
            <div class ="label">
                <div class="smallbox">KB030-POS</div>
                <div class="qr">
                <?php
                $data = 'KB030-POS';
                $qrcode = (new QRCode)->render($data);
                ?>
                <img src="<?php echo $qrcode; ?>">
            </div>
            </div>            
        </div>
        <div class="col-4 " id="etiqueta">
            <div class ="label">
                <div class="smallbox">KB030-POS</div>
                <div class="qr">
                <?php
                $data = 'KB030-POS';
                $qrcode = (new QRCode)->render($data);
                ?>
                <img src="<?php echo $qrcode; ?>">
            </div>
            </div>            
        </div>
        <div class="col-4 " id="etiqueta">
            <div class ="label">
                <div class="smallbox">KB030-POS</div>
                <div class="qr">
                <?php
                $data = 'KB030-POS';
                $qrcode = (new QRCode)->render($data);
                ?>
                <img src="<?php echo $qrcode; ?>">
            </div>
            </div>            
        </div>
       
       

    

    </div>

</div>
    <script>
    window.onload = function() {
        print();
    }

    function returnqr() {
        setTimeout(function() {
            window.location.href = "nuevaEtiqueta.php";
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