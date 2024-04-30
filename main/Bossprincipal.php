<?php
session_start();

if(!$_SESSION['usuario']){
    header("location:index.html");
}

if($_SESSION['user']=== 'Boss' or $_SESSION['user']==='Este'){
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
       <!--  <li><a href="../seguimiento/seguimiento.php"><button><h1>Where is the harness</h1></button></a></li>
       <li><a href="../seguimiento/checker_estacion.php"><button><h1>Follow proccess</h1></button></a></li>
         <li><a href="../seguimiento/tiempos.php"><button><h1>Harness Time</h1></button></a></li> 
         <li><a href="../filtro/index.html"><button><h1>Booms</h1></button></a></li>
        <li><a href="../stadistics/grafica.php"><button><h1>Graphic Sales</h1></button></a></li>
        <li><a href="../stadistics/sales.php"><button><h1>Sales per hour</h1></button></a></li>
        <li><a href="../ing/reqing.php"><button><h1>Engineer Request</h1></button></a></li>
        <li><a href="../calidad/piso.php"><button><h1>Waitting for Engineer</h1></button></a></li>
       <li><a href="../calidad/grafic.php"><button><h1>Quality Graphic</h1></button></a></li>
        <li><a href="../graficas/graficas.php"><button><h1>Graphics Production</h1></button></a></li> 
          <li><a href="../stadistics/busctime.php"><button><h1>average time</h1></button></a></li> 
       <li><a href="../inventario/registro.php"><button><h1>Registrar uno nuevo</h1></button></a></li>
        <li><a href="../inventario/registrado.php"><button><h1>Registros</h1></button></a></li>
         <li><a href="../stadistics/precios.php"><button><h1> REV LIST</h1></button></a></li>
         <li><a href="../firm/index.html"><button><h1>Material Request</h1></button></a></li> 
        <li><a href="../inventario/marbetes.php"><button><h1>Marbetes</h1></button></a></li>
    <li><a href="../graficas/dashboard.php"><button><h1>Dashboard</h1></button></a></li>
     <li><a href="../faltantes/auditoria.php"><button><h1>Audit</h1></button></a></li>
        <li><a href="../faltantes/registroaudi.php"><button><h1>Audited list</h1></button></a></li>
      
-->
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