<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("location:../main/index.html");
    exit();
}

$usuario = $_SESSION['user'];

    $admin = $_SESSION['admin'];
if ($usuario === "cali" || $admin === "Admin") {
    require "../app/conection.php";
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="fallas.css">
        <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
        <title>CVTS By Jorge Garrido</title>
    </head>
    <body>
        <small ><button><a href="../main/principal.php">Home</a></button></small>
        <h1>Registro prueba calidad</h1>
        <div class="table">
        <table>
            <thead>
                <th>Número de parte</th>
                <th>PO</th>
                <th>SONO</th>
                <th>WO</th>
                <th>Cantidad</th>
                <th>Iniciar test</th>
            </thead>
            <tbody>
                <?php 
                $buscar = "SELECT * FROM calidad ";
                $result = mysqli_query($con, $buscar);
                while ($row = mysqli_fetch_array($result)) {
                    $qty=$row['qty'];
                  
                    
                ?>
                    <tr>
                    <form action="calidad2.php" method="POST" id="forma">
    <td><?php echo $row['np']; ?></td>
    <td><?php echo $row['client']; ?></td>
    <td id="po"><?php echo $row['po']; ?></td>
    <td id="wo"><?php echo $row['wo']; ?></td>
    
    <td id="qty"><?php echo $row['qty']; ?></td>
    <input type="hidden" name="num" value="<?php echo $row['np']; ?>">
    <input type="hidden" name="po" value="<?php echo $row['po']; ?>">
    <input type="hidden" name="wo" value="<?php echo $row['wo']; ?>">
    <input type="hidden" name="info" value="<?php echo $row['info']; ?>">
    <input type="hidden" name="qty" value="<?php echo $row['qty']; ?>">
    <input type="hidden" name="client" value="<?php echo $row['client']; ?>">
    
    <td><button type="submit" id="enviar">Calidad</button></td>
</form>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
        <footer>    <style>
        footer{            background-color: lightslategray;
            color: aliceblue;
          
           width: 100%;                                }
            p{                font: bold italic            } 
                          
    </style>    <p>Created by Jorge Garrido</p></footer>
    </body>
    </html>
<?php
} else {
    header("location:../main/principal.php");
    exit();
}
?>
