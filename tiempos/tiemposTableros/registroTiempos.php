<?php 
require_once "../../app/conection.php";
$pn=isset($_GET['pn']) ? $_GET['pn'] : '';
$cardCode=isset($_GET['cardCode']) ? $_GET['cardCode'] : '';
$funcion=isset($_GET['funcion']) ? $_GET['funcion'] : '';
$dateTime = date("Y-m-d H:i:s");
$success = "";


try{
    if(strpos($cardCode, '|') !== false){ header("Location: tiemposQr.php");}

$coneccion=mysqli_query($con,"SELECT id FROM tiempoharneses WHERE  employeeNumber = '$cardCode' AND (estatus = 'INICIADO' or estatus = 'PAUSADO') ORDER BY id DESC LIMIT 1");

if($row=mysqli_fetch_array($coneccion)){
   if($funcion == "FIN"){
     $differencia =-( strtotime($row['finishTime']) - strtotime($row['initTime']));
    $resto = round($differencia/60,2);
    $update=mysqli_query($con,"UPDATE tiempoharneses SET finishTime = '$dateTime',TotalTime = '$resto', estatus = 'FINALIZADO' WHERE id = ".$row['id']);
    if($update){
        $success = "Tiempo finalizado correctamente";
    }}
    
   if($funcion == "PAUSA" && $row['finishPausedTime'] == NULL && $row['initPausedTime'] != NULL){
        $differencia =date("H:i:s")-( strtotime($row['finishPausedTime']) - strtotime($row['initPausedTime']));
        $resto = round($differencia/60,2);
        $update=mysqli_query($con,"UPDATE tiempoharneses SET finishPausedTime = '$dateTime',TotalTimePause = '$resto', estatus = 'INICIADO' WHERE id = ".$row['id']);
        $success = "Pausa finalizada correctamente";
   }elseif($funcion == "PAUSA" && $row['finishPausedTime'] != NULL && $row['initPausedTime'] != NULL){
        $differencia =strtotime($dateTime)-( strtotime($row['finishPausedTime']) - strtotime($row['initPausedTime']));
        $resto = date("Y-m-d H:i:s",$differencia);  
        $update=mysqli_query($con,"UPDATE tiempoharneses SET initPausedTime = '$resto', finishPausedTime = NULL, estatus = 'PAUSADO' WHERE id = ".$row['id']);
        $success = "Tiempo pausado correctamente";
        }elseif($funcion == "PAUSA" && $row['initPausedTime'] == NULL){
     $update=mysqli_query($con,"UPDATE tiempoharneses SET initPausedTime = '$dateTime', estatus = 'PAUSADO' WHERE id = ".$row['id']);$success = "Tiempo pausado correctamente";
   }
   if(!$update){
    $success = "Error al actualizar el tiempo";
   }
    header("Location: tiemposQr.php");
}else if((strpos($funcion, '|') !== false) || (strpos($funcion, ']') !== false)){
    $insert=mysqli_query($con,"INSERT INTO tiempoharneses (partnumber,employeeNumber,initTime) VALUES ('$funcion','$cardCode','$dateTime')");
    if($insert){
        $success = "Tiempo registrado correctamente";
    }else{
        $success = "Error al registrar el tiempo";
    }
     header("Location: tiemposQr.php");
}
    header("Location: tiemposQr.php");
}catch(Exception $e){
    echo "Error: " . $e->getMessage();
}