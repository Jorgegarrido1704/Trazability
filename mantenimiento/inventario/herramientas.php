<?php 
session_start();
if(!$_SESSION['user']){
    header("location:index.html");
}
$_SESSION['user'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="herramientas.css">
    <title>Herramental</title>
</head>
<body>
    <div class="home"><button class="home"><a href="principal.php">Home</a></button></div>
    <div id="ingreso">
        <form action="save.php" method="POST">
        <h1 class="title">Introduzca la información requerida</h1>
        <form action="save.php" method="POST">
        <label for="herramienta"><h3>Herramienta</h3></label>
        <input type="text" id="herramienta" name="herramienta">
        <label for="marca"id="derecha"><h3>Marca</h3></label>
        <input type="text" id="marca" class="marca" name="marca">
        <label for="modelo"><h3>Modelo</h3></label>
        <input type="text" id="modelo" name="modelo">
        <label for="qty" id><h3>Cantidad</h3></label>
        <input type="number" id="qty" name="qty">
    <br>
        <input type="submit" id="guard" value="Guardar">
</form>
    </div>
</body>
</html>