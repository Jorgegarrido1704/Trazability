<?php 
session_start();
require "../app/conection.php";
 $_SESSION['imp'];
 $info=$_SESSION['imp'];
 $buscar="SELECT * FROM registro WHERE info='$info'";
 $qry=mysqli_query($con,$buscar);

 while($row=mysqli_fetch_array($qry)){
$client=$row['cliente'];
    $wo=$row['wo'];
$orday=$row['orday'];
$reqday=$row['reqday'];
$send=$row['sento'];
$po=$row['po'];
$pn=$row['NumPart'];
$desc=$row['description'];
$qty=$row['Qty'];
$po=$row['po'];
$img=$row['Barcode'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="imp.css">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
    <meta http-equiv="REFRESH" content="3;url=checker_estacion.php">
    <title>Imprimir</title>
</head>
<body>
    <div class="Fast">
        <p><h4>Fast Turn Harness<br>1560 Sawrgrass Corporate Parkway<br>Suite 479<br>Sunrise, Florida 33323<br>USA <br><br>PHONE: 970-473-5464 </h4> </p>
    </div>
    <div class="wo"><h3>WORK ORDER# : <?php echo $wo; ?> </h3> </div>
    <br><br><br><br>
    <div class="date"><p><h3>DATE ORDERED: <?php echo $orday; ?><br>DATE REQUIRED: <?php echo $reqday; ?></h3> </p></div>
    <br><br><br><br><br><br>
    <div class="ship"><h2>SHIP TO:</h2><p><h3><?php echo $send; ?></h3></p></div>
    <br><br><br><br><br>
        <div class="client"><h4> <?php echo $client?></h4></p></div>
        <div class="table"><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <table >
            <Th><h3>PO</h3></Th>
            <Th><h3>Número de parte</h3></Th>
            <Th><h3>Descripción</h3></Th>
            <Th><h3>Cantidad</h3></Th>
            <tr>
                <td><?php echo $po;?></td>
                <td><?php echo $pn;?></td>
                <td><?php echo $desc;?></td>
                <td><?php echo $qty;?></td>
            </tr>
        </table>
        <p class="codigo"><?php  echo "<img src='data:image/jpeg;base64," . base64_encode(file_get_contents($img)) . "' />";?>
            
        </div>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <div class="lista"><h1>Lista de corte</h1></div>
        <table>
        <thead>
            <th>Revición</th>
            <th>Consecutivo</th>
            <th>Color</th>
            <th>Tipo</th>
            <th>Calibre</th>
            <th>Longitud</th>
            <th>Destino 1</th>
            <th>Destino 2</th>
            <th>Estampado</th>
            <th>Longitud total</th>
            <th>Cantidad a cortar</th>
            
        </thead>
        <tbody>
            <?php
            $sel = "SELECT * FROM corte WHERE num_part='$pn'";
            $mys = mysqli_query($con, $sel);
            while ($row = mysqli_fetch_array($mys)) {
                $cantidadACortar = $qty; 
        $cortados = 0;
                ?>
                <tr>
                    <td><?php echo $row['rev']; ?></td>
                    <td><?php echo $row['consec']; ?></td>
                    <td><?php echo $row['color']; ?></td>
                    <td><?php echo $row['tip']; ?></td>
                    <td><?php echo $row['calibre']; ?></td>
                    <td><?php echo $row['long']; ?></td>
                    <td><?php echo $row['Terminal1']; ?></td>
                    <td><?php echo $row['Terminal2']; ?></td>
                    <td><?php echo $row['est']; ?></td>
                    <?php $qtylong = $qty * $row['long']; ?>
                    <td><?php echo $qtylong; ?> </td>
                    <td ><input type="number" class="qty" name="qty" id="qty" value="<?php echo $qty; ?>"></td>
                    </tr>
                    <?php } ?>
        </table>
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
        
        window.onload = function() {
            window.print(); 
        };
    </script>

    <?php 
    }
  ?>