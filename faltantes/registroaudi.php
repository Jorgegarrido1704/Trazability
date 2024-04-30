<?php 
require "../app/conection.php";
session_start();
if (!isset($_SESSION['usuario'])) {
    header("location:../main/index.html");
    exit(); 
}
 $id=0;
if($_SESSION['user']=="Boss" or $_SESSION['admin']=="Admin"){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="audi.css">
    <title>Auditoria</title>
</head>
<body>
<small ><button><a href="../main/principal.php">Home</a></button></small>     


<?php

    
$busqueda = "SELECT * FROM auditoria ";
$result = mysqli_query($con, $busqueda);

$numero=mysqli_num_rows($result);


if($numero> 0){
?>
        <h1>Cantidad de registros: <?php echo $numero ;?> </h1>

<table>
    <thead>
    <th>Fecha de registro</th>
    <th>Número de parte</th>
    <th>Cliente</th>
    <th>WO</th>
    <th>QTY</th>
    <th>Proceso</th>
   
    <th>Status</th>
    </thead>
    <tbody>
      
<?php
 while($row=mysqli_fetch_array($result)){
    $fecha=$row['fecha'];
    $cliente=$row['client'];
    $np=$row['np'];
    $wo=$row['wo'];
    $qty=$row['qty'];
    $donde=$row['donde'];
    $status=$row['status'];
    ?>
    <tr>
    <td><?php echo $fecha ;?></td>
    <td><?php echo $cliente ;?></td>
    <td><?php echo $np ;?></td>
    <td><?php echo $wo ;?></td>
    <td><?php echo $qty ;?></td>
    <td><?php echo $donde ;?></td>
    <td><?php echo $status ;?></td>
    </tr>
    <?php
    
}}

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
<?php }else{
    header("location:../main/principal.php");
}?>