<?php 
session_start();
require "../app/conection.php";
if(!$_SESSION['usuario']){
    header("location:../main/index.html");
}
if($_SESSION['user']=="plan" or $_SESSION['admin']=="Admin"){
   
    if(isset($_POST['wo'])) {
        list($pn, $po) = explode('|', $_POST['wo']);
    }

?>
<!DOCTYPE html>

<div>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>
        </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
        <link rel="stylesheet" href="alta.css">
    </head>
<body>
    
<small><button><a href="../main/principal.php" id="principal">home</a></button></small>
       
        <br><br><br>
        <div align="center">
            <h1>Alta de arness</h1>
            <div>       
            <form action="registro.php" method="POST" name="registro" id="registro" enctype="multipart/form-data" onsubmit="return validation()">
            <?php 
$buscar = "SELECT * FROM po WHERE pn='$pn' AND po='$po'";
$qrybusc = mysqli_query($con, $buscar);
while($buscarrow = mysqli_fetch_array($qrybusc)) {
    $client = $buscarrow['client'];
    $PO = $buscarrow['po'];
    $part = $buscarrow['pn'];
    $qty = $buscarrow['qty'];
    $rev = $buscarrow['rev'];
    $desc = $buscarrow['description'];
    $send = $buscarrow['send'];
    $price = $buscarrow['price'];
    $orday = $buscarrow['orday'];
    $reqday = $buscarrow['reqday'];
    $rev1="";
    if(substr($rev,0,4)=='PPAP' or substr($rev,0,4)=='PRIM' ){
        $rev1=substr($rev,5);
    }
    
    echo "<input type='hidden' name='Cliente' id='Cliente' value='$client'>";
       echo "<input type='hidden' name='parte' id='parte' value='$part'>";
       echo "<input type='hidden' name='po' id='po' value='$PO'>";
    echo "<input type='hidden' name='cantidad' id='cantidad' value='$qty'>";
    echo "<input type='hidden' name='rev' id='rev' value='$rev'>";
    echo "<input type='hidden' name='rev1' id='rev1' value='$rev1'>";
    echo "<input type='hidden' name='desc' id='desc' value='$desc'>";
    echo "<input type='hidden' step='0.01' name='price' id='price' value='$price'>";
    echo "<input type='hidden' name='sento' id='sento' value='$send'>";
    echo "<input type='hidden' name='orday' id='orday' value='$orday'>";
    echo "<input type='hidden' name='reqday' id='reqday' value='$reqday'>";
}
?>
        <p><h2>Numero de parte: <?php echo $part;?>    Cliente: <?php echo $client; ?></h2></p>
        <p><h2>Cantidad: <?php echo $qty;?>    PO: <?php echo $PO; ?></h2></p>


    <label for="workOrder">Ingrese sus Work Order</label>
    <input type="text" name="workOrder" id="workOrder" required autofocus >
    
    <br><br><br>
    <label for="Generacion de codigo">Generador codigo de barras</label>
    <input type="button" value="Generar" onclick="generateBarcode()">
    <br><br>
    <div align="center" width="140" height="100">
        <canvas width="40" height="10" name="barcodeCanvas" id="barcodeCanvas"></canvas>
       
    </div>
    <input type="hidden" id="barcodeValue" name="barcodeValue" value="">
    <input type="hidden" id="barcodeContent" name="barcodeContent" value="">
    
    <br><br><br>
    
    <input type="submit" value="Guardar">
</form>

</div></div></div></div>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script>
       function generateBarcode() {
    
    var cliente = document.getElementById('Cliente').value;
    var parte = document.getElementById('parte').value;
    var workOrder = document.getElementById('workOrder').value;
    var po = document.getElementById('po').value;
    var qty = document.getElementById('cantidad').value;
    var rev = document.getElementById('rev1').value;
    if(rev===""){
        var rev = document.getElementById('rev').value;   
    }
rev='R'+rev;
    
    cliente = cliente.substring(0, 2);
    parte = parte.substring(5,6);
    po = po.substring(2,6);
    workOrder=workOrder.substring(2,6);

    
    const barcodeContent = `${parte}${cliente}${qty}${workOrder}${po}${rev}`;
    document.getElementById('barcodeContent').value = barcodeContent;

    
    const barcodeCanvas = document.getElementById('barcodeCanvas');

    
    const context = barcodeCanvas.getContext('2d');
    context.clearRect(0, 0, barcodeCanvas.width, barcodeCanvas.height);

    
    JsBarcode(barcodeCanvas, barcodeContent, {
        format: 'CODE128', 
        displayValue: true, 
    });

    
    const imageDataURL = barcodeCanvas.toDataURL("image/jpeg");
    document.getElementById('barcodeValue').value = imageDataURL;
    console.log(imageDataURL);
}

            
                function validation() {
            var numpart = document.getElementById('parte').value;
            var cliente = document.getElementById('Cliente').value;
            var po = document.getElementById('po').value;
            var wo = document.getElementById('workOrder').value;
            var qty = document.getElementById('cantidad').value;
            var bvalue=document.getElementById('barcodeValue').value;
            var bc=document.getElementById('barcodeContent').value;  
            if (numpart === "" || cliente === "" || wo === "" || po === "" || qty === "" || bvalue ==="" || bc==="") {
                alert("Algun campo esta vacío");
                return false;  
                }  }
    
    </script>
    
    <footer>    <style>
        footer{            background-color: lightslategray;
            color: aliceblue;
            height: 20px;  
            align-items: center;
           width: 100%;                                }
            p{                font: bold italic            } 
            
    </style>    <p>Created by Jorge Garrido</p></footer>
</body>
</html>
 <?php }else{
    header("location:../main/principal.php");
}?>