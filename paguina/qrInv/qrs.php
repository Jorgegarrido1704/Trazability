<?php
require '../conector.php';
require '../vendor/autoload.php';
$invoice = $_POST['invoice'];
$datos=[];
$inicio = 0;
$bsucada = mysqli_query($con, "SELECT id_importacion FROM `inv`  WHERE invoiceNum = '$invoice'");
while($row = mysqli_fetch_array($bsucada)){
    $data = $row['id_importacion'];
    $buscarInfo= mysqli_query($con, "SELECT codUnic FROM `controlalmacen` WHERE id_importacion = '$data' and MovType LIKE 'Entrada By%'");
    if(mysqli_num_rows($buscarInfo) > 0){
        while($row = mysqli_fetch_array($buscarInfo)){
            $datos[$data] = $row['codUnic'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta https-equiv="refresh" content="url=qrs.php">
    <title>QR Codes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
       
        #label {
            width: 70mm;
            height: 32mm;
            flex-direction: column;
            justify-content: space-between;
              }
        .row {            display: block;            align-items: center;            margin-bottom: none;        }
        .data-container1 {  display: flex;  align-items: center;  border: solid 1px #000;  width: 70mm;  height: 12mm;            padding-top: 1px;             margin-left: 1px;}
        .data-container {  display: flex;  align-items: center;  border: solid 1px #000;  width: 70mm;  height: 9mm;            padding-top: 1px;             margin-left: 1px;}
.img img {  width: 25mm;   height: 7mm;  margin-right: 5mm; margin-left: 1px; }
.supplier{  width: 25mm;   height: 11mm;  margin-right: 5mm; margin-left: 1px; }
.fecha-hecho,.rev {  display: center;  flex-direction: column;  width: 24mm; height: 7mm;  padding-buttom: 1mm;}
.fecha,.hecho {  font-size: 12px;   color: #333;  width: 24mm;   height: 3.5mm;}
.datos{    font-size: xx-small;  font-style: bold; margin-left: 1mm;  }
.datospn{    font-size: x-small; font-style: bold; margin-left: 1mm; padding-button: 3px; }
.datospn1{    font-size: small; font-style: bold; margin-left: 10mm;   }
.labelSupplier,.supplierPn {  display: center;  flex-direction: column;  width: 24mm; height: 1.5mm; }
.labelSupplier1{  display: center;  flex-direction: column;  width: 40mm; height: 2mm; }
.rev h6{margin-top: 5mm;    font-size: xx-small;    align-items: right; }
.custpn,.cust{display: center;  flex-direction: column;  width: 70mm; height: 5mm; margin-top: 1px;}
.custleb{width: 70mm;   height: 10mm;   }
    </style>
</head>
<body>
    
    

<?php

use chillerlan\QRCode\{QRCode, QROptions};

?>

    <?php foreach($datos as $data) { ?>
        <div style=" display:flex; width:42mm; height:26mm; rotate:90;  ">    
        <?php  
                $qrcode = (new QRCode)->render($data);
                ?>
               <?php printf('<img src="%s" alt="QR Code" class="img-fluid" />', $qrcode);?> 
  
    
    </div>
    <?php } ?>
    <script>
        window.onload = function() {
            print();
        }
    </script>
</body>
</html>



