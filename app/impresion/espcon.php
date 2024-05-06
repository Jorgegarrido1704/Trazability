<?php 
session_start();
require "../app/conection.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title>Document</title>
</head>

<body>
    <div><small><a href="../main/principal.php"><button>Home</button></a></small></div>
    <div align="center">
        <h1>Impresion de consecutivos</h1>
        <form action="impesp.php" method="GET">
          
  <label for="wo">WO</label>
        <input type="text" name="wo" id="wo"  autofocus >
   <br>
<input type="submit" name="enviar" id="enviar" value="Imprimir">
</form>
    </div>
</body>
</html>