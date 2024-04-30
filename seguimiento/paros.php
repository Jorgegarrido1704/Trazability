<?php
session_start();
require "../app/conection.php";

if (!isset($_SESSION['usuario'])) {
    header("location:../main/index.html");
    exit(); 
}


$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : "";
$paro = isset($_POST['paro']) ? $_POST['paro'] : "";
$codigo = isset($_POST['codigo']) ? $_POST['codigo'] : "";
date_default_timezone_set("America/Mexico_City");
$actualdate=date("d-m-Y H:i");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $busc="SELECT * FROM registro WHERE info='$codigo'";
$qry=mysqli_query($con,$busc);
$nada="";
while($row=mysqli_fetch_array($qry)){
    $rowcount=mysqli_num_rows($qry);
    $idcount=random_int(1,100);
    date_default_timezone_set('America/Mexico_City');
    $fecha=date("Y-m-d H:i");
    if($tipo=="inicio"){
   $iniciarparo="INSERT INTO `paros`(`id`, `info`,`tipo`, `registoinicial`, `registroparcial`,`count`) VALUES ('','$codigo','$paro','$fecha','','$idcount')";
    $tiempo=mysqli_query($con,$iniciarparo);
        $updateparo="UPDATE registro SET paro='$paro'  WHERE info='$codigo'";
        $upd=mysqli_query($con,$updateparo);
}else if($tipo=="fin" ){
    $buscarfiin="SELECT * FROM paros WHERE  info='$codigo'";
     $qrycodigo=mysqli_query($con,$buscarfiin);
     while($rowbus=mysqli_fetch_array($qrycodigo)){
    $iniparo=$rowbus['registoinicial'];
    $iniparo=strtotime($iniparo);
    $actualdate=strtotime($actualdate);
        $inter = abs($actualdate - $iniparo);
                    $hcalc = floor($inter / 3600);
                    $mcalc = floor(($inter % 3600) / 60);
                    $totalmin=($hcalc*60)+$mcalc;
    $parostotal= "INSERT INTO `parostotal`(`id`, `info`, `tiempo`) VALUES ('','$codigo','$totalmin')";
    $qrytotal=mysqli_query($con,$parostotal);
$delteparo="DELETE FROM `paros` WHERE info='$codigo'";
$qrydelete=mysqli_query($con,$delteparo);

$updateparos = "UPDATE registro SET paro='$nada' WHERE info='$codigo'";
$upd = mysqli_query($con, $updateparos);
     }}}}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
    <link rel="stylesheet" href="paros.css">
    <title>Paros</title>
</head>
<body> 
<small><button><a href="../main/principal.php" id="principal">home</a></button> </small>    
<br><br><br>
    <h1 align="center">Registro de paros</h1>
    <form action="paros.php" method="POST" id="form" name="form" onsubmit="return pregunta()">
        <label for="tipo">Inicio/Fin</label>
        <select name="tipo" id="tipo">
            <option value="Nulo"></option>
            <option value="inicio">Inicio de paro</option>
            <option value="fin">Fin de paro</option>
        </select>
        <label for="paro">Tipo de paro</label>
        <select name="paro" id="paro">
            <option value="null"></option>
            <option value="Retrabajo">Retrabajo</option>
            <option value="FaltaMaterial">Falta Material</option>
            <option value="Mantenimiento">Mantenimiento</option>
            <option value="Prueba_electrica">Prueba electrica</option>
            <option value="Ingenieria">Ingenieria</option>
            <option value="Calidad">Calidad</option>
        </select>
        <label for="codigo">Codigo de barras</label>
        <input type="text" name="codigo" id="codigo">
        <input type="submit" name="enviar" id="enviar" value="Registrar">
    </form>
    <footer>    <style>
        footer{            background-color: lightslategray;
            color: aliceblue;
            height: 20px;  
            align-items: center;
           width: 100%;                                }
            p{                font: bold italic            } 
            
    </style>    <p>Created by Jorge Garrido</p></footer>
</body>
</html>
<script>
    function pregunta() {
        var dato = document.getElementById("codigo").value;
        var confirmacion = prompt("¿Está seguro de registrar el código " + dato + "?", "");
        if (confirmacion === dato) {
            return true; 
        } else {
            alert("La confirmación no coincide con el código.");
            return false; 
        }
    }
</script>
