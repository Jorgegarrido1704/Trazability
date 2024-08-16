<?php
require "conection.php";
$buscar=mysqli_query($con,"SELECT * FROM registro_mant order by id desc");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Pendientes de ingenieria</title>
</head>
<body>
    <div class="container">
        <div>
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th scope="col">FOLIO</th>
                        <th scope="col">FECHA</th>
                        <th scope="col">NOMBRE EQUIPO</th>
                        <th scope="col">QUIEN SOLICITO</th>
                        <th scope="col">AREA</th>
                        <th scope="col">ATENDIO POR</th>
                        <th scope="col">COMPLETAR FORMATO</th>
                        <th scope="col">Excel</th>
                    </tr> 
                </thead>
                <tbody>
                    <?php
                        while($row = mysqli_fetch_array($buscar)){
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['fechReq']; ?></td>
                        <td><?php echo $row['equipo']; ?></td>
                        <td><?php echo $row['solPor']; ?></td>
                        <td><?php echo $row['area']; ?></td>
                        <td><?php echo $row['tecMant']; ?></td>    
                        <td><?php if($row['descTrab']==''){ ?>
                            <form action="completar.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>"/>
                            <button type="submit" class="btn btn-primary">Completar</button></form>
                            <?php } ?></td>                                       
                            <td><?php if($row['descTrab']!=''){ ?>
                            <form action="excel.php" method="POST">
                            <input type="hidden" name="ides" value="<?php echo $row['id']; ?>"/>
                            <button type="submit" class="btn btn-primary">excel</button></form>
                            <?php } ?></td>     
                        </tr>
                    <?php } ?>
                </tbody>       
            </table>
    </div>
    </div>
</body>
</html>