<?php
require '../conector.php';
require '../vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};

$data=['PAUSA - INGENIERIA','PAUSA - MANTENINIENTO','PAUSE - CALIDAD','PAUSA - MATERIALES','PAUSA - BANO','PAUSA - COMIDA']

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Codes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">
        <div class=" d-flex flex-wrap">

            <div class="p-2 text-center">
                <div class="row">
                <?php
               
                foreach ($data as $d) {
                    $fill = $d;    
                        $qrcode = (new QRCode)->render($fill);
                        ?>
                    <div class="col-4">
                        <img src="<?php echo $qrcode; ?>" alt="QR Code" style="width: 125mm; height: 125mm;"
                            class="img-fluid" />
                    <h2><?php echo $fill; ?></h2>
                </div>
                <?php } ?>
                </div>

            </div>
        </div>

    </div>
    </div>

</body>

</html>