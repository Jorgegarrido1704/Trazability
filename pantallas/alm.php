<?php 
require "../app/conection.php";
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body{      background-color: lightblue;          }
        #title{   font-size: xxx-large; text-align: center;           }
        table{    width: 100%;   border-radius: 5px;    }
        th{       text-align: center;   font-size: xx-large; border-radius: 5px; }
        thead{    background-color: lightcyan; border-radius: 15%; }
        td{       text-align: center;   font-size: xx-large; border-radius: 5px; }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CVTS By Jorge Garrido</title>
</head>
<body>
    <div id="title"><h1>Kits status</h1></div>
    <table>
        <thead>
            <th>Numero de Parte</th>
            <th>WO</th>
            <th>Status</th>
            <th>Fecha de Creacion</th>
        </thead>
        <tbody>
            <?php
$busqueda=mysqli_query($con,"SELECT * FROM kitenespera  WHERE status!='Completo' ");
            while($row=mysqli_fetch_array($busqueda)){
                $np=$row['np'];
                $wo=$row['wo'];
                $status=$row['status'];
                $fechaCreation=$row['fechaCreation'];
                $quien=$row['Quien'];
                $fechaSalida=$row['fechaSalida'];
            ?>
            <tr>
                <td><?php echo $np; ?></td>
                <td><?php echo $wo; ?></td>
                <td><?php echo $status; ?></td>
                <td><?php echo $fechaCreation; ?></td>
            </tr>
            <?php }
            ?>
        </tbody>
    </table>
</body>
</html>