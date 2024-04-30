<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("location:../main/index.html");
    exit(); }
$usuario = $_SESSION['user'];
$admin = $_SESSION['admin'];
if ($usuario === "cali" || $admin === "Admin") {
    require "../app/conection.php";
    $num = isset($_GET['num']) ? $_GET['num'] : "";
    $po = isset($_GET['po']) ? $_GET['po'] : "";
    $wo = isset($_GET['wo']) ? $_GET['wo'] : "";
    $info = isset($_GET['info']) ? $_GET['info'] : "";
    $qty = isset($_GET['qty']) ? $_GET['qty'] : "";
    $client = isset($_GET['client']) ? $_GET['client'] : "";
    ?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="calidad.css">
        <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
        <title>CVTS By Jorge Garrido</title>
    </head>
    <body>
    <div>
        <h1>Numero de parte: <?php echo $num . "   PO: " . $po . "  Work Order: " . $wo . "   Cantidad:" . $qty ?></h1>
    </div>
    <form action="guardar.php" method="POST" id="guardar" name="guardar">
                <div id="pruebanum" name="pruebanum" >
               
                    <h2>    <label for="ok">OK</label>
                <input type="number" id="ok" name="ok" value='0' min='0' required  onchange="return cuenta();">
                <label for="nok">NOK</label>
                <input type="number" id="nok" name="nok" value='0' min='0'  require onchange="return cuenta();"> </h2>
                <div id="container" name="container">
                <h2><label for="1">Codigo #1</label><input type="text" width="10px" name="1" id="1" value='0'min='0' required onchange="return datacheck()"><label for="c1">Cantidad</label><input type="number" name="c1" id="c1" value='0'></h2>  
                <h2><label for="2">Codigo #2</label><input type="text" width="10px" name="2" id="2" value='0' min='0' required onchange="return datacheck()"><label for="c2">Cantidad</label><input type="number" name="c2" id="c2" value='0'></h2>  
                <h2><label for="3">Codigo #3</label><input type="text" width="10px" name="3" id="3" value='0' min='0' required onchange="return datacheck()"><label for="c3">Cantidad</label><input type="number" name="c3" id="c3" value='0'></h2>  
                <h2><label for="4">Codigo #4</label><input type="text" width="10px" name="4" id="4" value='0' min='0' required onchange="return datacheck()"><label for="c4">Cantidad</label><input type="number" name="c4" id="c4" value='0'></h2>  
                <h2><label for="5">Codigo #5</label><input type="text" width="10px" name="5" id="5" value='0' min='0' required onchange="return datacheck()"><label for="c5">Cantidad</label><input type="number" name="c5" id="c5" value='0'></h2>  
            </div>
            </div> 
            <div class="btn">
                <h2><label for="prueba">Prueba</label><input type="text" name="prueba" id="prueba"></h2>
            <input type="hidden" id="pn" name="pn" value="<?php echo $num;?>">
            <input type="hidden" id="po" name="po" value="<?php echo $po;?>">
            <input type="hidden" id="wo" name="wo" value="<?php echo $wo;?>">
            <input type="hidden" id="qty" name="qty" value="<?php echo $qty;?>">
            <input type="hidden" id="info" name="info" value="<?php echo $info;?>">
            <input type="hidden" id="client" name="client" value="<?php echo $client;?>">
            <input type="submit" name="enviar" id="enviar" value="guardar" onclick="return Validar()" >      
</form>
</div>
<footer>    <style>
        footer{            background-color: lightslategray;
            color: aliceblue;
          
           width: 100%;                                }
            p{                font: bold italic            } 
           .btn{
            padding-bottom: 8.5%;
           }              
    </style>    <p>Created by Jorge Garrido</p></footer>
    </body>
    </html>

    <?php
    $options = "SELECT * FROM clavecali";
    $caliqry = mysqli_query($con, $options);
    $i = 0;
    $cali=[];
    while ($row = mysqli_fetch_array($caliqry)) {
        $rowcount = mysqli_num_rows($caliqry);
        $cali[$i] = $row['clave'];
        $i++;
    }
    $codigo = "SELECT * FROM registro";
    $codigoqry = mysqli_query($con, $codigo);
    $i = 0;
    $code=[];
    while ($row = mysqli_fetch_array($codigoqry)) {
        $rowcount = mysqli_num_rows($codigoqry);
        $code[$i] = $row['info'];
        $i++;
    }
    ?>
    
<script>
    
    var caliData = <?php echo json_encode($cali); ?>;
    var BarCode = <?php echo json_encode($code); ?>;
    function cuenta(){
    var cantidadMax=<?php echo $qty; ?>;
    var ok=parseInt( document.getElementById('ok').value);
    var nok=parseInt(document.getElementById('nok').value);
    console.log(cantidadMax);
    console.log(ok);
    console.log(nok);
    var sum=ok+nok;
    console.log(sum);
    var rest=(cantidadMax-sum);
    console.log(rest);

    if(rest<0){
        document.getElementById('ok').value=0;
        document.getElementById('nok').value=0;

    }
  }  
    
    
    

 function alert(){
            var numero=document.getElementById('codigos').value;
            var codigos;
        }
        function datacheck(){
            var c1=document.getElementById('1').value;
            var c2=document.getElementById('2').value;
            var c3=document.getElementById('3').value;
            var c4=document.getElementById('4').value;
            var c5=document.getElementById('5').value;
            var con1=0;
            var con2=0;
            var con3=0;
            var con4=0;
            var con5=0;

            for (var i = 0; i < 54; i++) {
            if (caliData[i] === c1) {
                con1 = c1;
                console.log(con1);
            } 
            if (caliData[i] === c2) {
                con2 = c2;
                console.log(con2);
            }
            if (caliData[i] === c3) {
                con3 = c3;
                console.log(con3);
            }
            if (caliData[i] === c4) {
                con4 = c4;
                console.log(con4);
 
            }
            if (caliData[i] === c5) {
                con5 = c5;
                console.log(con5);
            }
        }
        console.log(con1 + con2 + con3 + con4 + con5);
        document.getElementById('1').value=con1;
        document.getElementById('2').value=con2;
        document.getElementById('3').value=con3;
        document.getElementById('4').value=con4;
        document.getElementById('5').value=con5;
    }

    function Validar() {
        var cerial=document.getElementById('prueba').value;
        var numnok=parseInt(document.getElementById('nok').value);
   
        var n1=parseInt(document.getElementById('c1').value);
            var n2=parseInt(document.getElementById('c2').value);
            var n3=parseInt(document.getElementById('c3').value);
            var n4=parseInt(document.getElementById('c4').value);
            var n5=parseInt(document.getElementById('c5').value);
            var total=n1+n2+n3+n4+n5;
            var rest=total-numnok;

        var info=document.getElementById('info').value;
        console.log(info);
        var vali = prompt('Ingrese el codigo de barras',);
        if(vali===""){
            return false;
        }if(vali!=info){
            return false;
        }
        if(vali===info){
        for(x=0;x<BarCode.length;x++){
            
        if (vali===BarCode[x] & numnok>=0 & rest>=0 & cerial!==""){             
            return true;   }} 
            return false;
        
    
    }
}
     
document.forms['guardar'].onsubmit=function(){
    var numnok=document.getElementById('nok').value;
   
        var n1=document.getElementById('c1').value;
            var n2=document.getElementById('c2').value;
            var n3=document.getElementById('c3').value;
            var n4=document.getElementById('c4').value;
            var n5=document.getElementById('c5').value;
            var total=n1+n2+n3+n4+n5;
            console.log(total);
            
}



    </script>
    <?php
} else {
    header("location:../main/principal.php");
    exit(); 
}
?>
