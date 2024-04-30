<?php require "../app/conection.php";
$parte=isset($_POST["parte"]) ? $_POST["parte"] :"";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="table.css">
    <title>Tabla de revisiones</title>
</head>
<body>
    <div><small><a href="../main/principal.php"><button>Home</button></a></small></div>
    <div align="center">
        <form action="precios.php" method="POST">
    <label for="parte"><h2>Buscar por numero de parte</label>
    <input type="text" name="parte" id="parte" autofocus required></h2></form>


    </div>
    <table>
        <Thead>
            <th><h1>Client</h1></th>
            <th><h1>Part number</h1></th>
            <th><h1>Rev</h1></th>
            <th><h1>Description</h1></th>
            <th><h1>Price</h1></th>
            
        </Thead>
        <tbody>
            <?php
            if($parte!=''){
$precios="SELECT * FROM precios WHERE pn LIKE '%%$parte%%'" ;}else{$precios="SELECT * FROM precios"; }
$sqy=mysqli_query($con,$precios);
while($row=mysqli_fetch_array($sqy)){
    $client=$row["client"];
    $pn=$row['pn'];
    $rev=$row['rev'];
    $desc=$row['desc'];
    $price=$row['price'];
    $send=$row['send'];
?> <tr>
            <td><h2><?php echo $client;?></h2></td>
            <td><h2><?php echo $pn;?></h2></td>
            <td><h2><?php echo $rev;?></h2></td>
            <td><h2><?php echo $desc;?></h2></td>
            <td><h2><?php echo $price;?></h2></td>
           
            </tr>
            <?php } ?>
        </tbody>
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
