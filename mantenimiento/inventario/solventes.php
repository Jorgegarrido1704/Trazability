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
    <link rel="stylesheet" href="solventes.css">
    <title>Solventes</title>
</head>
<body>
  <div class="logout"> <button class="logout"><a href="principal.php">Home</a></button></div>
    <div class="registro">
    <h1>Registre los solventes correctamete</h1>
    <form action="save.php" method="POST">
    <label for="solvente"><h4>Tipo de solvente</h4></label>
    <input type="text" name="solvente" id="solvente">
    <label for="id"><h4>Id del solvente</h4></label>
    <input type="text" name="id" id="id">
    <label for="cantidadsol"><h4>Cantidad</H4></label>
    <input type="number" name="cantidadsol" id="cantidadsol">
     <br>
     <input type="submit" id=enviar name="enviar" value="Guardar "> 
      </form>

    </div>
</body>
</html>