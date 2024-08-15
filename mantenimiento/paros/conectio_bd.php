<?php 
	
require "conection.php";
	
	date_default_timezone_set('America/Mexico_City');
	$fecha = date('d-m-Y H:i');
	$equipo= isset($_POST['equipo'])?$_POST['equipo']:"";
    $dano = isset($_POST['dano'])?$_POST['dano']:"";
    $quien = isset($_POST['espec'])? $_POST['espec']:"";
	$quien=strtoupper($quien);
	$nom_eq = isset($_POST['nom_equipo'])?$_POST['nom_equipo']:"s";
	$nom_eq = strtoupper($nom_eq);
	$area=isset($_POST['area'])? $_POST['area']:"";
	$insertar1= "INSERT INTO `registro_paro`(`id`, `fecha`, `equipo`, `nombreEquipo`, `dano`, `quien`, `area`,`atiende`, `trabajo`, `Tiempo`, `inimant`, `finhora`) VALUES ('','$fecha','$equipo','$nom_eq','$dano','$quien','$area','Nadie aun','','','','')";
	$qry1= mysqli_query($con,$insertar1);
	if($qry1){
		header('location:multiparo.php');
	}else{
		echo '<script> alert("Incorrecto");</script>';

	}
 ?>