<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['user'];
    $password = $_POST['pass'];

    
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);
    $_SESSION['usuario'] =$username;

}
if(!$_SESSION['usuario']){
    header("location:index.html");
}
$user=$_SESSION['usuario'];

$_SESSION['user']=substr($user,0,4);
if($_SESSION['usuario']==="Admin"){
    $_SESSION['admin']=$_SESSION['usuario'];
    

}else{
    $_SESSION['admin']='';

}

if($_SESSION['user']=== 'cali'){
    header('location:calidadPrincipal.php');
}else if($_SESSION['user']=== 'Boss'or $_SESSION['user']==='plan'){
    header('location:Bossprincipal.php');
}else if($_SESSION['admin']=== 'Admin'){
header('location:admin.php');
}else if($_SESSION['admin']=== 'Mant'){
    header('location:Mant.php');}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="fth.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>CVTS By Jorge Garrido</title>
</head>
<body  >
    <div><small><a href="../log/logout.php" id="logout"><button>Cerrar la sesión</button></a></small></div>
    <div align="center" class="title"><h1>CVTS By <span style="color:red;">Jorge Garrido</span></h1></div>

    <div class="links">    
    <ul>
   
        <?php if($_SESSION['user']== 'emba'){ ?>
            <li><a href="../seguimiento/embarque.php"><button><h1>Registro de proceso embarque</h1></button></a></li>
       <li><a href="../seguimiento/seguimiento.php"><button><h1>Donde esta el arnes</h1></button></a></li>
       <!-- <li><a href="../filtro/index.html"><button><h1>Filtro Booms</h1></button></a></li>
        <li><a href="../seguimiento/checker_estacion.php"><button><h1>Registro de proceso</h1></button></a></li>
        <li><a href="../firm/index.html"><button><h1>Requerimiento de material</h1></button></a></li>     
        <li><a href="../../mantenimiento/paros/multiparo.php"><button><h1>Mantenimiento</h1></button></a></li>
       <li><a href="../seguimiento/paros.php"><button><h1>Registro de paros</h1></button></a></li>
        <li><a href="../seguimiento/tiempos.php"><button><h1>Tiempos de arnes</h1></button></a></li>   
      <li><a href="../desviasion/index.html"><button><h1>Desviacion</h1></button></a></li> 
       <li><a href="../pull/pull/tabla.php"><button><h1>Registros de pruebas de pull</h1></button></a></li>
        <li><a href="../stadistics/precios.php"><button><h1>Lista de REV</h1></button></a></li>
     -->
         
        <?php  }else if($_SESSION['user']== 'apli'){?>
            <li><a href="../mantenimiento/contadorgolpes/index.php"><button><h1>Registro de golpeteo </h1></button></a></li>
            <li><a href="../mantenimiento/contadorgolpes/diario.php"><button><h1>Golpeteo acumulado</h1></button></a></li>
            <li><a href="../mantenimiento/contadorgolpes/tabla.php"><button><h1>Golpeteo diario</h1></button></a></li>
            <li><a href="../mantenimiento/contadorgolpes/req.php"><button><h1>Requerimiento de aplicador</h1></button></a></li>
            <li><a href="../mantenimiento/contadorgolpes/addherra.php"><button><h1>Agregar aplicador</h1></button></a></li>
        <?php  }else{?>
            <li><a href="../seguimiento/checker_estacion.php"><button><h1>Registro de proceso </h1></button></a></li>
       <li><a href="../seguimiento/seguimiento.php"><button><h1>Donde esta el arnes</h1></button></a></li>
        <?php  }?>
       <!-- <?php if(substr($_SESSION['usuario'],5,9)=='angel'){?>
        <li><a href="../graficas/angel.php"><button><h1>Graficas</h1></button></a></li>
        <?php }else if(substr($_SESSION['usuario'],5,9)=='Brand'){?>
        <li><a href="../graficas/atla.php"><button><h1>Graficas</h1></button></a></li>
        <?php }else if(substr($_SESSION['usuario'],5,9)=='David'){?>
        <li><a href="../graficas/calif.php"><button><h1>Graficas</h1></button></a></li>
        <?php }else if(substr($_SESSION['usuario'],5,8)=='Zam'){?>
        <li><a href="../graficas/ber.php"><button><h1>Graficas</h1></button></a></li>
        <?php }else if(substr($_SESSION['usuario'],5,9)=='Saul'){?>
        <li><a href="../graficas/saul.php"><button><h1>Graficas</h1></button></a></li>
        <?php }else if(substr($_SESSION['usuario'],5,8)=='Ale'){?>
        <li><a href="../graficas/kalm.php"><button><h1>Graficas</h1></button></a></li>
        <?php }?>
        <li><a href="../inventario/registro.php"><button><h1>Registrar uno nuevo</h1></button></a></li>
         <li><a href="../faltantes/faltantes.php"><button><h1>Atla Faltantes</h1></button></a></li>
        <li><a href="../faltantes/baja.php"><button><h1>Baja Faltantes</h1></button></a></li> 
        <li><a href="../inventario/registrado.php"><button><h1>Registros</h1></button></a></li>
        <li><a href="../inventario/marbetes.php"><button><h1>Marbetes</h1></button></a></li>
    
    -->
    </ul>
<?php 


?>

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