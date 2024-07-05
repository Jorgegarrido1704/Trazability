<?php

    include "../app/conection.php";

$qry=mysqli_query($con,"SELECT * FROM mant_golpes_diarios");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tabla.css">
    <title>tabla diaria</title>
</head>
<body>
<div align="left"><small><a href="../../main/principal.php" id="principal"><button>Home</button></a></small></div>
    <table>
        <thead>
            <th>Herramental</th>
            <th>Fecha</th>
            <th>Ultimo registro de golpes</th>
            <th>Total acumulado</th>
        </thead>
        <tbody>
        <?php
        $totalgolpes=0;
while($row=mysqli_fetch_array($qry)){
    $totalgolpes+=$row['golpesDiarios'];

?>
            <tr>
                <td><?php echo $row['herramental']; ?> </td>
                <td><?php echo $row['fecha_reg']; ?></td>
                <td><?php echo $row['golpesDiarios']; ?></td>
                <td><?php echo $row['golpesTotales']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>

