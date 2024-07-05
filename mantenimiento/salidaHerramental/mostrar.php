<?php 
session_start();
$accion=$_SESSION['accion'];
$herra=$_SESSION['herramental'];
$golpes=$_SESSION['golpes'];
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>registros</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="mostrar.css">
        <meta http-equiv="refresh" content="2;url=registro.php">
    </head>
    <body>
        <br><br><br><br>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <h1><?php echo $accion." del herramental ".$herra."<br> con ".$golpes." golpes totales";  ?></h1>
            
          </body>
</html>