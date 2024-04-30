<?php 
require_once("../app/conection.php");
date_default_timezone_set("America/Mexico_City");
$date=date("d-m-Y");
$hour=date("H");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="table.css">
    <title>HARNESS SALE</title>
</head>
<body>
    <div><small><a href="../main/principal.php"><button>Home</button></a></small></div>
    <div align="center"></div>
<div align="center"><h1>HARNESS SALE FOR <?php echo $date;?></h1></div>    
<div>
    <table>
        <thead>
            <th><h2>Client</h2></th>
            <th><h2>Part Number</h2></th>
            <th><h2>REV</h2></th>
            <th><h2>Time</h2></th>
            <th><h2>Qty</h2></th>
            <th><h2>Price</h2></th>
            <th><h2>Partial</h2></th>
            <th><h2>Total Amount    </h2></th>
        </thead>
        <tbody>
            <?php
            $SearchT="SELECT * FROM tiempos  WHERE calidad like '$date%%'";
            $qryt=mysqli_query($con, $SearchT);
            while($rowt=mysqli_fetch_array($qryt)) {
                $parcial=0;
            $info=$rowt['info'];
            $time=$rowt['calidad'];
            $times=substr($time,0,10);
            $h=substr($time,11,5);
            
            
            $lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%%%'";
            $qrypar=mysqli_query($con, $lookinreg);
            $numrowsparcial=mysqli_num_rows($qrypar);
                $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
                $qryreg=mysqli_query($con, $SearchParcial);
                while($rowreg=mysqli_fetch_array($qryreg)) {
                    $pn=$rowreg['NumPart'];
                    $price=$rowreg['price'];
                    $client=$rowreg['cliente'];
                    $paro=$rowreg['paro'];
                    $rev=$rowreg['rev'];
                    $total=$numrowsparcial*$price;
                  
                }?>
        <tr>
            <td><h3><?php echo$client ;?></h3></td>
            <td><h3><?php echo$pn ;?></h3></td>
            <td><h3><?php echo$rev ;?></h3></td>
            <td><h3><?php echo$h ;?></h3></td>
            <td><h3><?php echo$numrowsparcial ;?></h3></td>
            <td><h3><?php echo$price ;?></h3></td>
            <?php if($paro=='parcial'){
            echo '<td><h3>Yes </h3></td>';}else{ echo '<td><h3>No </h3></td>';}?>
            <td><h3><?php echo$total ;?></h3></td>
        </tr>
    
    <?php } ?>
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