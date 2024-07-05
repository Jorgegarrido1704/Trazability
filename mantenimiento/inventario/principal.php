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
    <link rel="stylesheet" href="principal.css">
    <title>Document</title>
</head>
<body>
    <div id="button">    <button class="logout"><a href="logout.php">Logout</a></button> </div>
    <div id="title"><h1>Bienvenido <?php  echo $_SESSION['user'];?></h1>       
        <h2>Selecciona el registro que deseas realizar</h2>
</div>
<div>
<button id="herramientas" class="inv"><a href="herramientas.php">Herramientas</a></button>
<button id="refacciones" class="inv"><a href="refacciones.php">Refacciones</a></button>
<button id="solventes" class="inv"><a href="solventes.php">Solventes</a></button>

</div>

</body>
</html>