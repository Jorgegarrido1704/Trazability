<?php
require 'conector.php';
require 'vendor/autoload.php';
session_start();
$wo=isset($_POST['wo'])?$_POST['wo']:"";
$today = date('mdY');
if($wo==""){
    header("Location:qrs.php");
}else{
    $buscar=mysqli_query($con,"SELECT * FROM `registro` where `wo` = '$wo' ");
    if(mysqli_num_rows($buscar)>0){
        $rows = mysqli_fetch_array($buscar);
       $np=$rows['NumPart'];
       $rev=$rows['rev']; 
       $info=$rows['info'];
       $desc=$rows['description'];
       if(substr($rev,0,4)=="PPAP" or substr($rev,0,4)=="PRIM"){
           $rev=substr($rev,5);
       }
    
$buscarCuenta=mysqli_query($con,"SELECT * FROM `consterm` where `codigo` = '$info' ");
if(mysqli_num_rows($buscarCuenta)>0){
    $rowsCuenta = mysqli_fetch_array($buscarCuenta);
    $day=$rowsCuenta['dias'];
    if($day==$today){
        $dias=$today;
   $cuentas=$rowsCuenta['cuenta']+1;
   mysqli_query($con,"UPDATE `consterm` SET `cuenta` = '$cuentas' WHERE `consterm`.`codigo` = '$info' ");
}else{
    $dias=$today;
    $cuentas=1;
    mysqli_query($con,"UPDATE `consterm` SET `cuenta` = '$cuentas', `dias` = '$today' WHERE `consterm`.`codigo` = '$info' ");
}
}else{
    $cuentas=1;
    $dias=$today;
    mysqli_query($con,"INSERT INTO `consterm` (`pn`, `cuenta`, `rev`,`dias`, `codigo`) VALUES ('$np', '$cuentas', '$rev','$dias', '$info') ");
}

if($cuentas<10){
    $cuenta="00".$cuentas;
}else if($cuentas>9 and  $cuentas<100){
    $cuenta="0".$cuentas;
}else{
    $cuenta=$cuentas;
}}else{
    header("Location:qrs.php");
}
}
use chillerlan\QRCode\{QRCode, QROptions};

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta https-equiv="refresh" content=" url=qrs.php">
    <title>QR Codes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #label {
            width: 38mm;
            height: 26mm;
            border-radius: 1px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
           
        }
        .row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2px;
        }
        .qrs {
            width: 15mm;
            height: 15mm;
        }
        .data-container{
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-top: 4px; 
            margin-left: 2px;
        }
        .textarea-container {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            
            margin-left: 2px;
        }
        .datos {
            width: 33mm;
            border-radius: 1px;
            border: solid 1px #000;
            text-align: center;
            margin: 1px 0;
            padding-left: 1px;   
                    
            box-sizing: border-box;
        }
        textarea {
            width: 33mm;
            height: 13mm;
            border-radius: 1px;
            border: solid 1px #000;
            box-sizing: border-box;
            resize: none;
            font-size: xx-small;
            overflow: hidden;
            margin: 0;
        }
        #qrs_img {
            width: 15mm;
            height: 8mm;
        }
        .textarea-container {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div id="label" class="container">
        <div class="row">
            <div class="qrs">
                <?php
                $data = '5703|'.$np.'|'.$rev.'|'.$dias.'|'.$cuenta;
                $qrcode = (new QRCode)->render($data);
                ?>
                <?php printf('<img src="%s" alt="QR Code" class="img-fluid" />', $qrcode);?> 
            </div>
            <div class="data-container">
                <h6 class="datos"><?php echo $np; ?> | <?php echo $rev; ?></h6>
                <h6 class="datos"><?php echo $cuenta; ?></h6>
            </div>
        </div>
        <div class="row">
            <div class="qrss">
                <img id="qrs_img" src="proterra_dark.png" alt="codigo" class="img-fluid">
            </div>
            <div class="textarea-container">
                <textarea name="datost" id="datost"><?php echo $desc; ?></textarea>
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {
            print();
        }
    </script>
</body>
</html>


