<?php 
session_start();
if(!$_SESSION['user']){
    header("location:index.html");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="refacciones.css">
    <title>Refacciones</title>
</head>
<body>
    <div class="home"><button><a href="principal.php">Home</a></button></div>
    <div class="title"> <h1>Registra tus refacciones correctamente</h1></div>
    <div class="box">
    <div id="ingresoref" name="ingresoref">
        <form action="save.php" method="POST">
        <label for="refacciones"><h4>Tipo de refacción</h4></label>
        <input type="text" id="refacciones" name="refacciones">
        <label for="modeloref"><h4>Modelo</h4></label>
    <input type="text" name="modeloref" id="modeloref">
    </div>
    <div id="elotro">
    <label for="maquina"><h4>Maquina</h4></label>
        <input type="text" name="maquina" id="maquina">
    <label for="cantidadref"><h4>Cantidad</h4></label>
            <input type="number" id="cantidadref" name="cantidadref" class="cantidadref">
</div>
</div>
<DIV class="button"><input type="submit" name="enviar" id="enviar" value="Guardar">
</form></DIV>

</body>
</html>