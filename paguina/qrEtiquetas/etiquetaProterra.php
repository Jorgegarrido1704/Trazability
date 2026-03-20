<?php
require '../conector.php';
require '../vendor/autoload.php';
session_start();
date_default_timezone_set("America/Mexico_City");

use chillerlan\QRCode\{QRCode, QROptions};

try{
    $wo = isset($_GET['wo']) ? $_GET['wo'] : "";
    $cons = isset($_GET['cons']) ? $_GET['cons'] : "";
    
    $today_db = date('mdY'); 
    $today_qr = date('Ymd'); 
    $todayDate = date('Y-m-d'); 

    $buscar = mysqli_query($con, "SELECT * FROM `registro` where `wo` = '$wo' limit 1");
    if (mysqli_num_rows($buscar) > 0) {
        $rows = mysqli_fetch_array($buscar);
        $np = $rows['NumPart'];
        $rev = $rows['rev'];
        $info = $rows['info'];
        $desc = $rows['description'];
        $qty = $rows['Qty'];

        if (substr($rev, 0, 4) == "PPAP" or substr($rev, 0, 4) == "PRIM") {
            $rev = substr($rev, 5);
        }

        $regstroCuenta = mysqli_query($con, "SELECT cuenta FROM `consterm` where `dias` = '$today_db' order by id desc limit 1");
        if(mysqli_num_rows($regstroCuenta) > 0){
            $rowsCuenta = mysqli_fetch_array($regstroCuenta);
            $inicio = $rowsCuenta['cuenta'] + 1;
            $cuentas = $rowsCuenta['cuenta'] + $cons;
        } else {
            $inicio = 1;
            $cuentas = $cons;
        }
        $i=$j=$inicio;
        

        for($i; $i <= $cuentas; $i++){
            $consecutivo = $i;
            if($consecutivo < 10){
                $consecutivo = "00".$consecutivo;
            } else if($consecutivo > 9 and $consecutivo < 100){
                $consecutivo = "0".$consecutivo;
            }
            $dataDB = '5703|'.$np.'|'.$rev.'|'.$today_qr.'|'.$consecutivo;
            mysqli_query($con, "INSERT INTO `registroqrs`( `infoQr`, `CodigoIdentificaicon`, `fecha`) VALUES ('$info','$dataDB','$todayDate')");
        }
        
        $buscarCuenta = mysqli_query($con, "SELECT * FROM `consterm` where `codigo` = '$info' ");
        if (mysqli_num_rows($buscarCuenta) > 0) {
            $rowsCuenta = mysqli_fetch_array($buscarCuenta);
            $total = $rowsCuenta['totalWo'] - $cons;
            mysqli_query($con, "UPDATE `consterm` SET `dias` = '$today_db', `totalWo`= '$total' WHERE `consterm`.`codigo` = '$info' ");    
            mysqli_query($con, "UPDATE `consterm` SET `cuenta` = '$cuentas' WHERE `dias` = '$today_db' ");
        } else {
            $rest = $qty - $cons;
            mysqli_query($con, "INSERT INTO `consterm`( `pn`, `cuenta`, `rev`, `descri`, `dias`, `codigo`, `totalWo`) VALUES ('$np','$cuentas','$rev','$desc','$today_db','$info','$rest')");
            mysqli_query($con, "UPDATE `consterm` SET `cuenta` = '$cuentas' WHERE `dias` = '$today_db' ");
        }

    } 
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<style>
    .sheet{
        width:38.5mm; height:104.5mm;
    
        padding-top:23mm;
        padding-left:5mm;

    }

    .label{
        width:31.4mm;
        height:15.3mm;
        border:1px solid black;
        border-radius:2mm;
        display:inline-block;
        padding:0.3mm;
        font-family:Arial;
        font-size:5px;
        box-sizing:border-box;
        margin:0.3mm;
    }

    .row{
        display:flex;
    }

    .bloque1{
        padding-left:3px;
        width:14mm;
        height:15mm;
    }

    .bloque2{
        width:16mm;
        height:14.9mm;
        padding-top:6px;
    }

    .qr img{
        width:11mm;
        height:10.5mm;
    }

    .smallbox{
        border:1px solid black;
        text-align:center;
        font-size:7px;
        margin-bottom:1px;
    }

    .smallbox1{
        border:1px solid black;
        text-align:center;
        font-size:7px;
        margin-bottom:1px;
    }


    #logo{
        padding-left:2px;
        width: 11mm;
        height: 3.5mm;
    }

</style>

</head>

<body>
<?php for($j; $j <= $cuentas; $j++){ 
    $consecutivoSerial = $j;
    if($consecutivoSerial < 10){
        $consecutivoSerial = "00".$consecutivoSerial;
    }elseif($consecutivoSerial < 100){
        $consecutivoSerial = "0".$consecutivoSerial;
    }
    ?>
<div class="sheet">

<div class="label">

    <div class="row">

        <div class="bloque1">

            <div class="qr">
            <?php
            $data = '5703|'.$np.'|'.$rev.'|'.$today_qr.'|'.$consecutivoSerial;
            $qrcode = (new QRCode)->render($data);
            ?>
            <img src="<?php echo $qrcode; ?>">
            <img src="logo.jpg" alt="logo" id="logo">
            </div>

            
        </div>

        <div class="bloque2">

            <div class="smallbox">5703</div>

            <div class="smallbox1"><?php echo $np."|".$rev;?></div>

            <div class="smallbox"><?php echo $today_qr."|".$consecutivoSerial; ?></div>

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
            window.location.href = "../qrs.php";
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