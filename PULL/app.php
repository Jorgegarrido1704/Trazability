<?php 
$host = "localhost";
$user = "pcadmin";
$clave = "SupAdmin1212";
$db_name = "trazabilidad";

$con = mysqli_connect($host, $user, $clave, $db_name);
if (mysqli_connect_errno()) {
    die("Failed to connect with MySQL: " . mysqli_connect_error());
}
date_default_timezone_set('America/Mexico_City');

$aplicador=isset($_GET['aplicador1'])?$_GET['aplicador1']:"";
$maquina=isset($_GET['maquina1'])?$_GET['maquina1']:"";
$quien=isset($_GET['quien1'])?$_GET['quien1']:"";
$trabajo=isset($_GET['trabajo1'])?$_GET['trabajo1']:"";
if($aplicador !="No esta(preguntar y agregar)" or $aplicador != ""){
$herra=mysqli_query($con,"SELECT * FROM mant_golpes_diarios WHERE terminal='$aplicador'");
while($row=mysqli_fetch_array($herra)){
$qry=$row['herramental'];
}
$fecha=date("d-m-Y H:i");
//echo $maquina,$quien,$trabajo,$fecha,$aplicador,$qry;
if( $maquina!="" and $quien!="" and $trabajo!=""){
    $sql=mysqli_query($con,"INSERT INTO registro_paro ( `fecha`, `equipo`, `nombreEquipo`, `dano`, `quien`, `area`, `atiende`) VALUES   ( '$fecha','Bancos para terminales', '$qry/$aplicador', '$trabajo', '$quien', '$maquina', 'Nadie aun')");
    header("Location: solicitar.php");
}
}else if($aplicador !="No esta(preguntar y agregar)"){
    $fecha=date("d-m-Y H:i");

    $sql=mysqli_query($con,"INSERT INTO registro_paro ( `fecha`, `equipo`, `nombreEquipo`, `dano`, `quien`, `area`, `atiende`) VALUES   ( '$fecha','Bancos para terminales', '$aplicador', '$trabajo', '$quien', '$maquina', 'Nadie aun')");
    header("Location: solicitar.php");

}