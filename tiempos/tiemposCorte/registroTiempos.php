<?php 
require_once "../../app/conection.php";

$cardCode=isset($_GET['cardCode']) ? $_GET['cardCode'] : '';
$funcion=isset($_GET['funcion']) ? $_GET['funcion'] : '';
$dateTime = date("Y-m-d H:i:s");
$success = "";
if(strpos($funcion, ']') !== false){
    $funcion = str_replace(']', '|', $funcion);
 }
 if(strpos($funcion, "'") !== false){
    $funcion = str_replace("'", '-', $funcion);
 }


try{
    if(strpos($cardCode, '|') !== false){ header("Location: tiemposQr.php");}

$coneccion=mysqli_query($con,"SELECT * FROM tiemposcorte WHERE  empNum = '$cardCode' AND (estatus = 'INICIADO' or estatus LIKE 'PAUSA%') ORDER BY id DESC LIMIT 1");

if($row=mysqli_fetch_array($coneccion)){
   if($funcion == "FIN"){
    $update=mysqli_query($con,"UPDATE tiemposcorte SET finishTime = '$dateTime', estatus = 'FINALIZADO' WHERE id = ".$row['id']);
    if($update){
        $success = "Tiempo finalizado correctamente";
    }
    }else if(strpos($funcion, 'PAUSA') !== false){
        $lookingForOne = mysqli_query($con,"SELECT * FROM tiemposcortepausas WHERE  id_tiempos = '$row[id]' AND finishTime IS NULL  ORDER BY id DESC LIMIT 1");
        if($rowOne=mysqli_fetch_array($lookingForOne)){
            $update=mysqli_query($con,"UPDATE tiemposcortepausas SET finishTime = '$dateTime' WHERE id = ".$rowOne['id']);
            $update2=mysqli_query($con,"UPDATE tiemposcorte SET estatus = 'INICIADO' WHERE id = ".$row['id']);
            if($update){
                $success = "Pausa finalizada correctamente";
            }
        }else{
            $update=mysqli_query($con,"INSERT INTO tiemposcortepausas (motivo,initTime,finishTime,id_tiempos) VALUES ('$funcion','$dateTime',NULL,'".$row['id']."')");
            $update2=mysqli_query($con,"UPDATE tiemposcorte SET estatus = '$funcion' WHERE id = ".$row['id']);
            if($update){
                $success = "Tiempo pausado correctamente";
            }
        }
    }

    /*
   if($funcion == "PAUSA" && $row['finishPausedTime'] == NULL && $row['initPausedTime'] != NULL){
        $differencia =date("H:i:s")-( strtotime($row['finishPausedTime']) - strtotime($row['initPausedTime']));
        $resto = round($differencia/60,2);
        $update=mysqli_query($con,"UPDATE tiempoharneses SET finishPausedTime = '$dateTime',TotalTimePause = '$resto', estatus = 'PAUSADO' WHERE id = ".$row['id']);
        $success = "Pausa finalizada correctamente";
   }elseif($funcion == "PAUSA" && $row['finishPausedTime'] != NULL && $row['initPausedTime'] != NULL){
        $differencia =strtotime($dateTime)-( strtotime($row['finishPausedTime']) - strtotime($row['initPausedTime']));
        $resto = date("Y-m-d H:i:s",$differencia);  
        $update=mysqli_query($con,"UPDATE tiempoharneses SET initPausedTime = '$resto', finishPausedTime = NULL, estatus = 'INICIADO' WHERE id = ".$row['id']);
        $success = "Tiempo pausado correctamente";
        }elseif($funcion == "PAUSA" && $row['initPausedTime'] == NULL){
     $update=mysqli_query($con,"UPDATE tiempoharneses SET initPausedTime = '$dateTime', estatus = 'PAUSADO' WHERE id = ".$row['id']);$success = "Tiempo pausado correctamente";
   }
   if(!$update){
    $success = "Error al actualizar el tiempo";
   }*/
    header("Location: tiemposQr.php");

}else if((strpos($funcion, '|') !== false) || (strpos($funcion, ']') !== false)){
    $insert=mysqli_query($con,"INSERT INTO tiemposcorte (pn,empNum,initTime) VALUES ('$funcion','$cardCode','$dateTime')");
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