<?php
session_start();
require "../app/conection.php";

$qry=mysqli_query($con,"SELECT * FROM mant_golpes_diarios");
$fecha=strtotime('d-m-Y 00:00');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tabla.css">
    <title>tabla</title>
</head>
<body>
    <div><small><a href="../../main/principal.php" id="principal"><button>Home</button></a></small></div>
    <table>
        <thead>
            <th>Herramental</th>
            <th> Ultima Fecha Registrada</th>
            <th>Ultimo registro de golpes</th>
            
        </thead>
        <tbody>
        <?php
while($row=mysqli_fetch_array($qry)){
    if(strtotime($row['fecha_reg'])>=$fecha and $row['fecha_reg']!=""){
        
    
?>
            <tr>
                <td><?php echo $row['herramental']; ?> </td>
                <td><?php echo $row['fecha_reg']; ?></td>
                <td><?php echo $row['golpesDiarios']; ?></td>
                
            </tr>
            <?php }}?>
        </tbody>
    </table>

</body>
</html>






