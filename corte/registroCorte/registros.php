<?php 
include "../../app/conection.php";
$codigo=isset($_GET['codigo'])?$_GET['codigo']:"";

$buscarRegistro=mysqli_query($con,"SELECT * FROM corte WHERE codigo='$codigo'  ");
$registros=[];
while($reg=mysqli_fetch_array($buscarRegistro)){
   $registros[0]=$reg['np'];
   $registros[1]=$reg['cliente'];
   $registros[2]=$reg['rev'];
   $registros[3]=$reg['wo'];
   $registros[4]=$reg['cons'];
   $registros[5]=$reg['color'];
   $registros[6]=$reg['tipo'];
   $registros[7]=$reg['aws'];
   $registros[8]=$reg['codigo'];
   $registros[9]=$reg['term1'];
   $registros[10]=$reg['term2'];
   $registros[11]=$reg['dataFrom'];
   $registros[12]=$reg['dataTo'];
   $registros[13]=$reg['qty'];
   $registros[14]=$reg['tamano'];
   $registros[15]=$reg['conector'];
}

echo json_encode($registros);
?>