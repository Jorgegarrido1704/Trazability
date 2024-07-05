<?php 
$host="localhost";
$user="pcadmin";
$clave="SupAdmin1212";
$bd="trazabilidad";
	$con= mysqli_connect($host,$user,$clave,$bd);
	if(!$con){
		die("ERROR: ".mysqli_connect_error());

	}
