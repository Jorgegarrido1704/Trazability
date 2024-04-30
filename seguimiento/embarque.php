<?php 
session_start();
if($_SESSION["user"]=='emba' or $_SESSION["user"]=='Admi'){
    
 
require "../app/conection.php";
date_default_timezone_set('America/Mexico_City');
$fecha= date('d-m-Y H:i');
$total=0;
$escaneo=isset($_POST['id'])?$_POST['id']:"";
$parcial=isset($_POST['parcial'])?$_POST['parcial']:0;
$code=isset($_POST['escan'])?$_POST['escan']:"";

if($escaneo!="" and $parcial!='0' and $code!=""){
      $buscode=mysqli_query($con,"SELECT * FROM registro WHERE id='$escaneo'");
    while($row=mysqli_fetch_array($buscode)){
        $cantidad=$row['Qty'];
        $codebar=$row['info'];
        $sono=$row['po'];
    
        $total=$cantidad-$parcial;}
        $buscarcalidad=mysqli_query($con,"SELECT * from calidad WHERE info='$code'  ");
        $numcali=mysqli_num_rows($buscarcalidad);
        if($numcali>0 and $numcali==1){
        while($buscal=mysqli_fetch_array($buscarcalidad)){
            $qtycali=$buscal['qty'];
        }
        if($total<$qtycali){
            $resto=$cantidad-$qtycali;
           ?> <h1><b>Solo puedes dar de baja <?php echo $resto; ?> </b></h1><?php
        }else{
    
        if($total<0){
            ?> <div align="center"> <h1>Ingreso mas de los que puede dar de baja</h1></div><?php
        } else if($total==0){
            if($codebar===$code){
      
                $updatep=mysqli_query($con,"UPDATE registro SET Qty='$total',donde='Embarcado' ,paro='',count='20' WHERE id='$escaneo' and info='$code'");
    
            }else{
                ?> <div align="center"> <h1>El codigo es incorrecto</h1></div><?php
            } 
        }else if($total>0){
        if($codebar===$code){
        
            $selectpo=mysqli_query($con,"SELECT * FROM po WHERE po='$sono'");
            While($rowpo=mysqli_fetch_array($selectpo)){
                $countpo=$rowpo['qty'];
            }

            $updatep=mysqli_query($con,"UPDATE registro SET Qty='$total',paro='Parcial en embarque $countpo / $total' WHERE id='$escaneo' and info='$code'");

        }else{
            ?> <div align="center"> <h1>El codigo es incorrecto</h1></div><?php
        }}
    }

    }else if(!$numcali){

if($total<0){
            ?> <div align="center"> <h1>Ingreso mas de los que puede dar de baja</h1></div><?php
        } else if($total==0){
            if($codebar===$code){
      
                $updatep=mysqli_query($con,"UPDATE registro SET Qty='$total',donde='Embarcado' ,paro='',count='20' WHERE id='$escaneo' and info='$code'");
    
            }else{
                ?> <div align="center"> <h1>El codigo es incorrecto</h1></div><?php
            } 
        }else if($total>0){
        if($codebar===$code){
        
            $selectpo=mysqli_query($con,"SELECT * FROM po WHERE po='$sono'");
            While($rowpo=mysqli_fetch_array($selectpo)){
                $countpo=$rowpo['qty'];
            }

            $updatep=mysqli_query($con,"UPDATE registro SET Qty='$total',paro='Parcial en embarque $countpo / $total' WHERE id='$escaneo' and info='$code'");

        }else{
            ?> <div align="center"> <h1>El codigo es incorrecto</h1></div><?php
        }}
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="checker.css">
    <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
    <meta http-equiv="refresh" content="30; url=../main/principal.php">
    <title>seguimiento de arneses</title>
</head>
<body>
    
<small><button><a href="../main/principal.php" id="principal">home</a></button> </small>

    <br><br><br>
    <div>
         <?php if($escaneo!=""){ ?>
    <table id="table">
            <thead>
                <th><h2>Cliente</h2></th>
                <th><h2>Número de parte</h2></th>
                <th><h2>Work order</h2></th>
                <th><h2>PO</h2></th>
                <th><h2>Cantidad en orden</h2></th>
                <th><h2>Cantidad en embarque</h2></th>
                <th><h2>Escaneo</h2></th>
            </thead>
          <h2> <tbody>
                <tr>
                    <?php 
                    $searchporcess="SELECT * FROM registro WHERE id='$escaneo'  ";
                    $qryprocess=mysqli_query($con,$searchporcess);
                    while($process=mysqli_fetch_array($qryprocess)){
                        $np=$process['NumPart'];
                        $wo=$process['wo'];
                        $po=$process['po'];
                        $qtyp=$process['Qty'];
                        $donde=$process['donde'];
                        $client=$process['cliente'];
                        $id=$process['id'];
                        ?>
                        <tr>
                        <td><h2><?php echo $client;?></h2></td>
                        <td><h2><?php echo $np;?></h2></td>
                        <td><h2><?php echo $wo;?></h2></td>
                        <td><h2><?php echo $po;?></h2></td>
                        <td><h2><?php echo $qtyp;?></h2></td>
                        <td><form action="embarque.php" method="POST">
                            <h2><input type="number" name="parcial" id="parcial" value='0' min='0' require></h2></td>
                        <td><h2>
                            <input type="text" name="escan" id="escan">
                            <input type="hidden" name="id" id="id" value="<?php echo $id;?>">
                        <input type="submit" name="enviar" id="enviar" value="Registrar">
                        </form></h2></td>
                        

                        </tr>
                        <?php
                    }
                    
                    ?>
              
            </tbody>
        </table>

        <?php } else if($escaneo==""){
            ?>
        <table id="table">
            <thead>
                <th><h2>Cliente</h2></th>
                <th><h2>Número de parte</h2></th>
                <th><h2>Work order</h2></th>
                <th><h2>PO</h2></th>
                <th><h2>Cantidad</h2></th>
                <th><h2>Proceso</h2></th>
                <th><h2>Escaneo</h2></th>
            </thead>
          <h2> <tbody>
                <tr>
                    <?php 
                    $searchporcess="SELECT * FROM registro WHERE count='12' or (paro Like 'parcial%%' or paro Like 'Parcial%%')";
                    $qryprocess=mysqli_query($con,$searchporcess);
                    while($process=mysqli_fetch_array($qryprocess)){
                        $np=$process['NumPart'];
                        $wo=$process['wo'];
                        $po=$process['po'];
                        $qtyp=$process['Qty'];
                        $donde=$process['donde'];
                        $client=$process['cliente'];
                        $id=$process['id'];
                        ?>
                        <tr>
                        <td><h2><?php echo $client;?></h2></td>
                        <td><h2><?php echo $np;?></h2></td>
                        <td><h2><?php echo $wo;?></h2></td>
                        <td><h2><?php echo $po;?></h2></td>
                        <td><h2><?php echo $qtyp;?></h2></td>
                        <td><h2><?php echo $donde;?></h2></td>
                        <td><h2><form action="embarque.php" method="POST">
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
    
    <?php
    mysqli_close($con);
}}
    ?>
<script>
if(prom=== '3'){
    return true;
}else false;

</script>
