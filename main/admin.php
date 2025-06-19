<?php
session_start();

if(!$_SESSION['usuario']){
    header("location:index.html");
}

if($_SESSION['user']=== 'Admi'){
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
    <li><a href="../registro/cargapo.php"><button><h1>Registo de PO</h1></button></a></li>
        <li><a href="../registro/work.php"><button><h1>Registo de arnes</h1></button></a></li>
         <li><a href="../seguimiento/seguimiento.php"><button><h1>Donde esta el arnes</h1></button></a></li>
        <li><a href="../filtro/index.html"><button><h1>Filtro Booms</h1></button></a></li>
        <li><a href="../../mantenimiento/paros/multiparo.php"><button><h1>Mantenimiento</h1></button></a></li>
        <li><a href="../seguimiento/checker_estacion.php"><button><h1>Registro de proceso</h1></button></a></li>
        <li><a href="../ing/reqing.php"><button><h1>Solicitud Ingenieria</h1></button></a></li>
       <li><a href="../seguimiento/tiempos.php"><button><h1>Tiempos de arnes</h1></button></a></li>     
        <li><a href="../calidad/grafic.php"><button><h1>Graficas Calidad</h1></button></a></li>
        <li><a href="../calidad/fallas.php"><button><h1>Registro calidad</h1></button></a></li>
        <li><a href="../stadistics/precios.php"><button><h1>Lista de REV</h1></button></a></li>
        <li><a href="../stadistics/grafica.php"><button><h1>Graphic Sales</h1></button></a></li>
        <li><a href="../stadistics/busctime.php"><button><h1>average time</h1></button></a></li>
        <li><a href="../stadistics/sales.php"><button><h1>Sales per hour</h1></button></a></li>
        <li><a href="../desviasion/index.html"><button><h1>Desviacion</h1></button></a></li>    
        <li><a href="../graficas/angel.php"><button><h1>Graficas libe</h1></button></a></li>
        <li><a href="../graficas/atla.php"><button><h1>Graficas brando</h1></button></a></li>
        <li><a href="../graficas/calif.php"><button><h1>Graficas David</h1></button></a></li>
        <li><a href="../graficas/ber.php"><button><h1>Graficas Zam</h1></button></a></li>
        <li><a href="../graficas/saul.php"><button><h1>Graficas Saul</h1></button></a></li>
        <li><a href="../graficas/kalm.php"><button><h1>Graficas Ale</h1></button></a></li>
        <li><a href="../graficas/graficas.php"><button><h1>Graficas </h1></button></a></li>
        <li><a href="../pull/pull/tabla.php"><button><h1>Registros de pruebas de pull</h1></button></a></li>
        <li><a href="../firm/index.html"><button><h1>Requerimiento de material</h1></button></a></li> 
        <li><a href="../impresion/espcon.php"><button><h1>Impresion consecutivo</h1></button></a></li> 
        <li><a href="../movimientosAdmin/editarOrden.php"><button><h1>Editar</h1></button></a></li> 
        <li><a href="../seguimiento/emba2.php"><button><h1>Registro de proceso embarque</h1></button></a></li>
        <li><a href="../reportes/reportes/index.html"><button><h1>Reportes</h1></button></a></li>
    <!--movimientosAdmin
<li><a href="../inventario/registro.php"><button><h1>Registrar uno nuevo</h1></button></a></li>
        <li><a href="../inventario/registrado.php"><button><h1>Registros</h1></button></a></li>
        <li><a href="../inventario/marbetes.php"><button><h1>Marbetes</h1></button></a></li>
      <li><a href="../faltantes/auditoria.php"><button><h1>Auditoria</h1></button></a></li>
        <li><a href="../faltantes/registroaudi.php"><button><h1>registro Auditoria</h1></button></a></li>
        <li><a href="../faltantes/faltantes.php"><button><h1>Atla Faltantes</h1></button></a></li>
        <li><a href="../faltantes/baja.php"><button><h1>Baja Faltantes</h1></button></a></li>
         <li><a href="../facturacion/facturacion.php"><button><h1>Facturacion</h1></button></a></li>
        <li><a href="../seguimiento/paros.php"><button><h1>Registro de paros</h1></button></a></li>    
            <li><a href="../registro/confirmation.php"><button><h1> Ordenes creadas</h1></button></a></li>
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