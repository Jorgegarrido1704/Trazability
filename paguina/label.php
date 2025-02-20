<?php
$rev=isset($_GET['rev'])?$_GET['rev']:"";
$pn=isset($_GET['np'])?$_GET['np']:"";
$desc=isset($_GET['desc'])?$_GET['desc']:"";

if($pn=='1003647380'){
    $cust='24763470';
}else if($pn=='1003617118'){
    $cust='24763468';
}else if($pn=='100362360'){
    $cust='24763469';
}else{
    $cust='24763469';
}        
        

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
        body{
            width:73mm; height:92mm;
        }
        #label {
            width: 70mm;
            height: 32mm;
            flex-direction: column;
            justify-content: space-between;
              }
        .row {            display: block;            align-items: center;            margin-bottom: none;        }
        .data-container {  display: flex;  align-items: center;  border: solid 1px #000;  width: 70mm;  height: 10mm;            padding-top: 2px;             margin-left: 1px;}
.img img ,.supplier{  width: 25mm;   height: 7mm;  margin-right: 5mm; margin-left: 1px; }
.fecha-hecho,.rev {  display: center;  flex-direction: column;  width: 24mm; height: 7mm;  padding-buttom: 1mm;}
.fecha,.hecho {  font-size: 12px;   color: #333;  width: 24mm;   height: 3.5mm;}
.datos{    font-size: xx-small;  font-style: bold; margin-left: 1mm;  }
.datospn{    font-size: x-small; font-style: bold; margin-left: 1mm;  }
.datospn1{    font-size: small; font-style: bold; margin-left: 10mm;   }
.labelSupplier,.supplierPn {  display: center;  flex-direction: column;  width: 24mm; height: 2mm; }
.labelSupplier1{  display: center;  flex-direction: column;  width: 40mm; height: 3mm; }
.rev h6{margin-top: 5mm;    font-size: xx-small;    align-items: right; }
.custpn,.cust{display: center;  flex-direction: column;  width: 70mm; height: 5mm; margin-top: 1px;}
.custleb{width: 70mm;   height: 10mm;   }
    </style>
</head>
<body>
    
    <div style=" display:flex; width:42mm; height:26mm; rotate:90deg;  ">    
    <div id="label" style="padding-top:3mm; padding-left:12.8mm;" >
        <div class="row">
            <div class="data-container">
                <div class="img" style=" padding-right:10mm;">
              <img src="bergs.jpg" alt="responsive image"/>  
              </div>
              <div class="fecha-hecho">
                <div class="fecha" align="center"><?php echo date("d/m/y"); ?></div>
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
                        <div class="supplierPn" style="padding-top:0.3mm; ">
                            <h5 class="datospn"><?php echo $pn; ?></h5>   
                        </div>
                        <div class="labelSupplier1" style="padding-top:0.7mm; ">
                            <h6 class="datos"><?php echo $desc; ?></h6>
                        </div>
                </div>
                <div class="rev" style="padding-left:15mm; width: 40mm; ">
                    <H6>REVISION LEVEL: <?php echo $rev; ?></H6></div>
            </div>
            </div>
            <div class="row">
            <div class="data-container">
                <div class="custleb">
                <div class="custpn">
                    <h6 class="datos">CUSTOMER P/N</h6> 
                </div>
                <div class="cust">
                    <h4 class="datospn1"><b><?php echo $cust;?></b></h4>
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


