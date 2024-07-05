<?php
session_start();
require "../app/conection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Contador de golpes</title>
</head>
<body>

    <div align="left"><small><a href="../../main/principal.php" id="principal"><button>Home</button></a></small></div>
        <div align="center">
        </div>
    <h1>Ingrese el numero de golpeteos</h1>
    <form action="registro.php" method="POST" name="f1">
    <label for="dado">Herramental</label>
    <select name="dado" id="dado" required>
    <option value="null"></option>
            <?php 
                        $qry = mysqli_query($con, "SELECT * From mant_golpes_diarios");
            if ($qry) {
                while ($row = mysqli_fetch_array($qry)) {
                    echo "<option value='" . $row['herramental'] . "'>" . $row['herramental'] . "</option>"; // Added 'value' attribute
                }
            } else {
                echo "Error: " . mysqli_error($con);
            }
            ?>
    </select>
    <label for="cantidad">Cantidad</label>
    <input type="number" id="cantidad" name="cantidad" required>
   
    <input type="submit" id="enviar" value="enviar">

    </form>
    

    <br><br><br>
    
</body>
</html>
<script>  
            function validation()  
            {  
                var id=document.f1.cantidad.value;  
                
                if (id.length=="") {  
                        alert("your code is empty");  
                        return false;  
                    }}
        </script>  