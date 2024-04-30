<?php
session_start();
require "../app/conection.php";
date_default_timezone_set('America/Mexico_City');
$fecha = date('d-m-Y H:i');
$info = isset($_POST['info']) ? $_POST['info'] : "";
$num = isset($_POST['np']) ? $_POST['np'] : "";
$item =isset($_POST['item'])? $_POST['item']:"";
$id=isset($_POST['id'])? $_POST['id']:"";
$cantidad=isset($_POST['faltantes'])?$_POST['faltantes']:"";

$faltan = isset($_POST['faltan'])?$_POST['faltan']:"";

for($i=0;$i<=$id;$i++){
$falta=$cantidad[$i]-$faltan[$i];

$update="UPDATE faltantes SET qty=$falta WHERE info='$info' AND item='$item[$i]'";
$qry=mysqli_query($con,$update);
$check="SELECT * FROM faltantes WHERE info='$info' AND item='$item[$i]'";
$qrycheck=mysqli_query($con,$check);
while($row=mysqli_fetch_array($qrycheck)){
    $qty=$row['qty'];
    if($qty<=0){
        $delet="DELETE FROM faltantes WHERE info='$info' and item='$item[$i]'";
        $delqry=mysqli_query($con,$delet);
    }
}

    
}
header("location:baja.php");


?>
