<?php
require '../conector.php';
require '../vendor/autoload.php';


use chillerlan\QRCode\{QRCode, QROptions};

$datos=mysqli_query($con,"SELECT employeeNumber,employeeName,employeeArea,employeeLider FROM personalberg WHERE `status` !='Baja' ORDER BY employeeNumber ASC");

?><!DOCTYPE html>
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
                $num = $data['employeeNumber'];
                $name = $data['employeeName'];
                $area = $data['employeeArea'];
                $lider = $data['employeeLider'];
                $fill = $num;
                $qrcode = (new QRCode)->render($fill);
                ?>

                <img src="<?php echo $qrcode; ?>" alt="QR Code"
                     style="width: 25mm; height: 24mm;" class="img-fluid" />

                <div><?php echo $num . ' - ' . $name; ?></div>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>