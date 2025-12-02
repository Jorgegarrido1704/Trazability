<?php
require '../conector.php';
require '../vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};
$datos=mysqli_query($con,"SELECT * FROM assets ");

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
        <div class="d-flex flex-wrap">
            <?php foreach ($datos as $data) { ?>
            <div class="p-2 text-center">
                <?php
                $name=$data['NameAsset'];
                $routing=$data['routingNumer'];
                $type=$data['typeAsset'];
                $fill = 'http://mxloficina.corp.internal.bergstrominc.com/Trazability/mantenimiento/mantenimiento/registros.php?name='.$name.'&routing='.$routing.'&type='.$type;
                $qrcode = (new QRCode)->render($fill);
                ?>

                <img src="<?php echo $qrcode; ?>" alt="QR Code" style="width: 25mm; height: 24mm;" class="img-fluid" />

                <div><?php echo $routing . ' - ' . $name; ?></div>
            </div>
            <?php } ?>
        </div>
    </div>

</body>

</html>