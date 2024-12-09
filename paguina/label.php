<?php
$rev=isset($_GET['rev'])?$_GET['rev']:"";
$pn=isset($_GET['np'])?$_GET['np']:"";
$desc=isset($_GET['desc'])?$_GET['desc']:"";
$berglab=['1003647380','1003617118'];
       if(!in_array($pn,$berglab)){
        header("Location:qrs.php");
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
            width:70mm; height:78mm;
        }
        #label {
            width: 78mm;
            height: 35mm;
            flex-direction: column;
            justify-content: space-between;
              }
        .row {            display: block;            align-items: center;            margin-bottom: none;        }
        .data-container {  display: flex;  align-items: center;  border: solid 1px #000;
  width: 70mm;  height: 10mm;            padding-top: 2px;             margin-left: 1px;}
.img img ,.supplier{  width: 30mm;   height: 9mm;  margin-right: 15mm; margin-left: 1px; }
.fecha-hecho,.rev {  display: center;  flex-direction: column;  width: 24mm; 
  height: 10mm;  padding-buttom: 2mm;}
.fecha,.hecho {  font-size: 14px;   color: #333;  width: 24mm;   height: 5mm;}
.datos{    font-size: xx-small;  font-style: bold; margin-left: 1mm;  }
.datospn{    font-size: small; font-style: bold; margin-left: 1mm;  }
.datospn1{    font-size: small; font-style: bold; margin-left: 10mm;   }
.labelSupplier,.supplierPn {  display: center;  flex-direction: column;  width: 24mm; height: 3mm; }
.labelSupplier1{  display: center;  flex-direction: column;  width: 40mm; height: 2mm; }
.rev h6{margin-top: 6mm;    font-size: xx-small;    align-items: buttom; }
.custpn,.cust{display: center;  flex-direction: column;  width: 70mm; height: 5mm; margin-top: 1px;}
.custleb{width: 70mm;   height: 10mm;   }
    </style>
</head>
<body>
    
    <div style=" display:flex; width:70mm; height:78mm; rotate:90deg;  ">    
    <div id="label" style="padding-top:30mm; padding-left:3mm;" >
        <div class="row">
            <div class="data-container">
                <div class="img">
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
                        <div class="supplierPn">
                            <h5 class="datospn"><?php echo $pn; ?></h5>   
                        </div>
                        <div class="labelSupplier1">
                            <h6 class="datos"><?php echo $desc; ?></h6>
                        </div>
                </div>
                <div class="rev"><H6>REVISION LEVEL: <?php echo $rev; ?></H6></div>
            </div>
            </div>
            <div class="row">
            <div class="data-container">
                <div class="custleb">
                <div class="custpn">
                    <h6 class="datos">CUSTOMER P/N</h6> 
                </div>
                <div class="cust">
                    <h4 class="datospn1"><b>24763470</b></h4>
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


