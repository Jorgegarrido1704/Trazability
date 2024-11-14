<?php
//require 'conector.php';
//require 'vendor/autoload.php';
//session_start();
/*date_default_timezone_set("america/Mexico_City");
$wo=isset($_POST['wo'])?$_POST['wo']:"";
$cons=isset($_POST['const'])?$_POST['const']:"";
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
        $inicio=$rowsCuenta['cuenta'];
           $cuentas=$rowsCuenta['cuenta']+$cons;
   mysqli_query($con,"UPDATE `consterm` SET `cuenta` = '$cuentas' WHERE `consterm`.`codigo` = '$info' ");
}else{
    $dias=$today;
    $inicio=0;
    $cuentas=$cons;
    mysqli_query($con,"UPDATE `consterm` SET `cuenta` = '$cuentas', `dias` = '$today' WHERE `consterm`.`codigo` = '$info' ");
}
}else{
    $inicio=0;
    $cuentas=$cons;
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
*/
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
            width: 70mm;
            height: 30mm;
            border-radius: solid 1px #000;
            box-sizing: border-box;
            display: block;
            flex-direction: column;
            justify-content: space-between;            }
        .row {            display: block;            align-items: center;            margin-bottom: none;        }
        .data-container {  display: flex;  align-items: center;  border: solid 1px #000;
  width: 70mm;  height: 10mm;            padding-top: 4px;             margin-left: 2px;}
.img img ,.supplier{  width: 30mm;   height: 8mm;  margin-right: 15mm; margin-left: 2px; }
.fecha-hecho,.rev {  display: center;  flex-direction: column;  width: 24mm; 
  height: 9mm;  padding-buttom: 1mm;}
.fecha,.hecho {  font-size: 14px;   color: #333;  width: 24mm;   height: 4mm;}
.datos{    font-size: xx-small;  font-style: bold; margin-left: 1mm;  }
.datospn{    font-size: small; font-style: bold; margin-left: 1mm;  }
.datospn1{    font-size: small; font-style: bold; margin-left: 10mm; }
.labelSupplier,.supplierPn {  display: center;  flex-direction: column;  width: 24mm; height: 4mm;}
.rev h6{margin-top: 6mm;    font-size: xx-small;    align-items: buttom; }
.custpn,.cust{display: center;  flex-direction: column;  width: 68mm; height: 4mm; }
.custleb{width: 68mm;   height: 8mm;   }
    </style>
</head>
<body>
    
    <div style="display:flex; width: 70mm; height: 30mm">    
    <div id="label" class="container">
        <div class="row">
            <div class="data-container">
                <div class="img">
              <img src="bergs.jpg" alt="responsive image"/>  
              </div>
              <div class="fecha-hecho">
                <div class="fecha">13/11/2024</div>
                <div class="hecho">Made in MEX</div>
              </div>
            </div>
        </div>
            <div class="row">    
            <div class="data-container">
                <div class="supplier">
                        <div class="labelSupplier">
                            <h6 class="datos">SUPPLIER P/N</h6>
                        </div>
                        <div class="supplierPn">
                            <h5 class="datospn">1201301203020x</h5>   
                        </div>
                </div>
                <div class="rev"><H6>REVISION LEVEL: XX</H6></div>
            </div>
            </div>
            <div class="row">
            <div class="data-container">
                <div class="custleb">
                <div class="custpn">
                    <h6 class="datos">CUSTOMER P/N</h6> 
                </div>
                <div class="cust">
                    <h5 class="datospn1">1201301203020x</h5>
                </div> 
                </div>   
            </div>
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


