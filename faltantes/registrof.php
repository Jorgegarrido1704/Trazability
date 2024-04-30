<?php
session_start();
require "../app/conection.php";
date_default_timezone_set('America/Mexico_City');
$fecha = date('d-m-Y H:i');
$info = isset($_POST['info']) ? $_POST['info'] : "";
$num = isset($_POST['np']) ? $_POST['np'] : "";
$item =isset($_POST['item'])? $_POST['item']:"";
$id=isset($_POST['id'])? $_POST['id']:"";


$faltan = isset($_POST['faltan'])?$_POST['faltan']:"";
if($id>0){
for($i=0;$i<$id;$i++){
    $falta=$faltan[$i]*1.02;
    
    $searchduplicate="SELECT * FROM faltantes WHERE info ='$info' AND item='$item[$i]'";
    $duplicateqry=mysqli_query($con,$searchduplicate);
    while($duplitems=mysqli_fetch_array($duplicateqry)){
        $cuentas=$duplitems['qty'];
        $objeto=$duplitems['item'];
    }
$falta=$faltan[$i]*1.02;
$numitems=mysqli_num_rows($duplicateqry);
if($numitems>0){
    if($faltan[$i]>0){
        $total=$cuentas+$faltan[$i];
        $updateitem="UPDATE faltantes SET qty='$total' Where info='$info' And item='$objeto'";
        $updateqry=mysqli_query($con,$updateitem);

    }else{
   
        echo "<br><br><br><br><br><div align='center'><h1>Dirijase al area de sistemas para actualizar datos</h1></div>";
        
        header("Refresh:3;url=faltantes.php");
    

}}else{
if($faltan[$i]>0){
$add="INSERT INTO `faltantes`(`id`, `partNum`, `info`, `item`, `qty`) VALUES ('','$num','$info','$item[$i]','$falta')";
$qry=mysqli_query($con,$add);}
}
$paros="UPDATE registro SET `paro`='FaltaMaterial' WHERE info='$info'";
$qtyupdate=mysqli_query($con,$paros);
date_default_timezone_set('America/Mexico_City');
    $fecha=date("Y-m-d H:i");
    $iniciarparo="INSERT INTO `paros`(`id`, `info`,`tipo`, `registoinicial`, `registroparcial`,`count`) VALUES ('','$info','FaltaMaterial','$fecha','','')";
    $tiempo=mysqli_query($con,$iniciarparo);    
header("location:faltantes.php");
}}else{
   
    echo "<br><br><br><br><br><div align='center'><h1>Dirijase al area de sistemas para actualizar datos</h1></div>";
    
    header("Refresh:3;url=faltantes.php");
}


?>
