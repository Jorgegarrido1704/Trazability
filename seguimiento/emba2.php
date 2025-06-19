<?php 
session_start();
if($_SESSION["user"]=='emba' or $_SESSION["user"]=='Admi'){
    
 
require "../app/conection.php";
date_default_timezone_set('America/Mexico_City');
$fecha= date('d-m-Y H:i');
$total=0;
$parcial=isset($_POST['parcial'])?$_POST['parcial']:0;
$code=isset($_POST['escan'])?$_POST['escan']:"";


if( $parcial!='0' and $code!=""){
$parciales = mysqli_query($con, "SELECT * FROM registroparcial WHERE codeBar= '$code' limit 1");
while ($row = mysqli_fetch_array($parciales)) {
   $cortPar = $row['cortPar'];
   $libePar = $row['libePar'];
   $ensaPar = $row['ensaPar'];
   $preCalidad = $row['preCalidad'];
   $loomPar = $row['loomPar'];
   $testPar = $row['testPar'];
   $embPar = $row['embPar'];
   $eng = $row['eng'];
   $fallasCalidad = $row['fallasCalidad'];
   $total=$cortPar+$libePar+$ensaPar+$preCalidad+$loomPar+$testPar+$embPar+$eng+$fallasCalidad;
   $datosTotales=$total-$parcial;
   $introducirMov= mysqli_query($con, "INSERT INTO `registroparcialtiempo`(`codeBar`, `qtyPar`, `area`, `fechaReg`) VALUES ('$code','$parcial','embarque','$fecha')");
   if($datosTotales<=0){
    $update=mysqli_query($con,"UPDATE `registroparcial` SET `embPar` = '0' WHERE `codeBar` = '$code'");
    $update=mysqli_query($con,"UPDATE `tiempos` SET `embarque` = '$fecha' WHERE `info` = '$code'");
    $update=mysqli_query($con,"UPDATE `registro` SET `Qty`='$datosTotales',count='20',paro='',donde='Embarcado' WHERE info='$code'");
       header("location: ./emba2.php");
   }elseif ($datosTotales>0){
    $embPar=$embPar-$parcial;
    $update=mysqli_query($con,"UPDATE `registroparcial` SET `embPar` = '$embPar' WHERE `codeBar` = '$code'");
    $update=mysqli_query($con,"UPDATE `registro` SET `Qty`='$datosTotales',paro='',donde='parecial embarcado' WHERE info='$code'");
    header("location: ./emba2.php");
   }
}
}

}else{
    header("location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="checker.css">
    <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
    <meta http-equiv="refresh" content="60; url=../main/principal.php">
    <title>seguimiento de arneses</title>
</head>
<body>
    
<small><button><a href="../main/principal.php" id="principal">home</a></button> </small>

    <br><br><br>
    <div>
        
    <table id="table">
            <thead>
                <th><h2>Número de parte</h2></th>
                <th><h2>Work order</h2></th>
               
                <th><h2>Cantidad en orden</h2></th>
                <th><h2>Cantidad en embarque</h2></th>
                <th><h2>Escaneo</h2></th>
            </thead>
          <h2> <tbody>
                <tr>
                    <?php 
                    $searchporcess="SELECT * FROM registroparcial WHERE embPar>0  ";
                    $qryprocess=mysqli_query($con,$searchporcess);
                    while($process=mysqli_fetch_array($qryprocess)){
                        $np=$process['pn'];
                        $wo=$process['wo'];                       
                        $qtyp=$process['orgQty'];
                        $embPar=$process['embPar'];
                        $code=$process['codeBar'];
                        ?>
                        <tr>
                        <td><h2><?php echo $np;?></h2></td>
                        <td><h2><?php echo $wo;?></h2></td>
                        <td><h2><?php echo $qtyp;?></h2></td>
                        <td><form action="emba2.php" method="POST">
                            <h2><input type="number" name="parcial" id="parcial" value='<?php echo $embPar;?>' min='0' max='<?php echo $embPar;?>' require></h2></td>
                        <td><h2>
                            <input type="text" name="escan" id="escan">
                            <input type="hidden" name="code" id="code" value="<?php echo $code;?>">
                            <input type="hidden" name="id" id="id" value="<?php echo $id;?>">
                        <input type="submit" name="enviar" id="enviar" value="Registrar">
                        </form></h2></td>
                        

                        </tr>
                        <?php
                    }
                    
                    ?>
              
            </tbody>
        </table>

         
    </div>
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
  
<script>
    $code=document.getElementById('code').value;
    $escan=document.getElementById('escan').value;

    document.getElementById('enviar').onclick=function(){
        if($escan==$code){
            return true;
        }else{
            alert('Escaneo incorrecto');
            return false;
        }
    }

</script>
