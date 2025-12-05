<?php
require '../conector.php';
require '../vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};

if(isset($_POST['pn'])){
    $data = $_POST['pn'];
    $datos=mysqli_query($con,"SELECT client,pn FROM po  WHERE pn = '$data' order by id desc limit 1 ");
    if($row=mysqli_fetch_array($datos)){
        $pn = $row['pn'];
        $cliente = $row['client'];
    }else{
     $datos=mysqli_query($con,"SELECT pn,customer FROM workschedule WHERE pn = '$data' order by id desc limit 1 ");
    if($row=mysqli_fetch_array($datos)){
        $pn = $row['pn'];
        $cliente = $row['customer'];
    }   
    }
    
}else{
    $data = $pn = $cliente = '';}
    
    


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
        <form action="qrPao.php" method="POST">
            <div class="form-group">
                <label for="pn">Part Number:</label>
                <input type="text" class="form-control" id="pn" name="pn" required onchange="customer();">
            </div>

        </form>
        <div class=" d-flex flex-wrap">

            <div class="p-2 text-center">
                <?php
               
                $fill = $cliente.' | '.$pn;
                $qrcode = (new QRCode)->render($fill);
                if(!$data){
                    echo "<h3>Please enter a Part Number to generate QR Code.</h3>";
                }else{
                    echo "<h3>QR Code for Part Number: $pn</h3>";
                ?>

                <img src="<?php echo $qrcode; ?>" alt="QR Code" style="width: 125mm; height: 125mm;"
                    class="img-fluid" />
                <?php } ?>


            </div>
        </div>

    </div>
    </div>

</body>

</html>