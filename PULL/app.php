<?php require "../app/conection.php";

$aplicador=isset($_GET['aplicador1'])?$_GET['aplicador1']:"";
$maquina=isset($_GET['maquina1'])?$_GET['maquina1']:"";
$quien=isset($_GET['quien1'])?$_GET['quien1']:"";
$trabajo=isset($_GET['trabajo1'])?$_GET['trabajo1']:"";
$herra=mysqli_query($con,"SELECT herramental FROM herramental WHERE comp='$aplicador'");
while($row=mysqli_fetch_array($herra)){
$qry=$row['herramental'];
}
$fecha=date("d-m-Y H:i");
echo $maquina,$quien,$trabajo,$fecha,$aplicador,$qry;
if( $maquina!="" and $quien!="" and $trabajo!=""){
    $sql=mysqli_query($con,"INSERT INTO registro_paro ( `fecha`, `equipo`, `nombreEquipo`, `dano`, `quien`, `area`, `atiende`) VALUES   ( '$fecha','Bancos para terminales', '$qry', '$trabajo', '$quien', '$maquina', 'Nadie aun')");
    header("Location: solicitar.php");
}