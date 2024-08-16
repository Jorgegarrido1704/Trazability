<?php
session_start();

if(!$_SESSION['usuario']){
    header("location:index.html");
}

else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="fth.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>Trazabilidad</title>
</head>
<body  >
    <div><small><a href="../log/logout.php" id="logout"><button>Cerrar la sesión</button></a></small></div>
    <div align="center" class="title"><h1>CVTS By <span style="color:red;">Jorge Garrido</span></h1></div>

    <div class="links">    
    <ul>
    <li><a href="../mantenimiento/paros/Fallas.php"><button><h1>Fallas</h1></button></a></li>
        <li><a href="../mantenimiento/paros/pendientes.php"><button><h1>Registros pendientes</h1></button></a></li>
      
            
    </ul>

</div>
<footer>    <style>
        footer{            background-color: lightslategray;
            color: aliceblue;
          
           width: 100%;                                }
            p{                font: bold italic            } 
            .links{
                padding-bottom: 42%;
            }                
    </style>    <p>Created by Jorge Garrido</p></footer>
</body>
</html>
<?php } ?>