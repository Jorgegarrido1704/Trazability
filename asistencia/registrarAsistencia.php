<?php
require '../app/conection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $cardCode = $_GET["cardCode"];
    if(strlen($cardCode) == 4){
        $cardCode = "i" . $cardCode;
        
    }
    $days=['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
    //day today
    $dayToday=date('N')-1;
    $todayIs=$days[$dayToday];

    $action = $_GET["action"];
    
        $comentario = "registro con QR";
    
   // echo $cardCode . "<br>" . $comentario . "<br>";
    $dateNow = date("Y-m-d");
    $timeNow = date("H:i:s");
   $weekday = intval(date('W'));

    //Busqueda de empleado con tarjeta valida
    $bucarEmpleado = mysqli_query($con, "SELECT `typeWorker` , `employeeName` FROM personalberg WHERE employeeNumber='$cardCode' AND `status` !='Baja'");
    if (mysqli_num_rows($bucarEmpleado) <= 0) {
        header("Location:  asistencias.php?success=tarjeta Invalida, vuelva a intentarlo&color=Red");
    } else {
        $row = mysqli_fetch_assoc($bucarEmpleado);
        $type = $row['typeWorker'];
        $name=$row['employeeName'];
        $datosAssistencia=$row[$todayIs];
        if(strpos($datosAssistencia, '-') !== false){
           $datosAssistencia =explode('-', $datosAssistencia)[0];
        }
        
        $buscarRegistro = mysqli_query($con, "SELECT * From relogchecador WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow' ORDER BY id DESC LIMIT 1");
        $rowRegistro = mysqli_fetch_assoc($buscarRegistro);
        if (mysqli_num_rows($buscarRegistro) <= 0) {
            $insertarAsistencia = mysqli_query($con, "INSERT INTO relogchecador (employeeNumber,fechaRegistro,entrada,comentario) VALUES ('$cardCode','$dateNow','$timeNow','$comentario')");
            if($type=='Indirecto' and $timeNow < '08:15:00'){ 
                        $status='OK';
            }else if($type=='Indirecto'  and $timeNow > '08:15:00'){
                        $status='R';
            }if($type!='Indirecto' and $timeNow < '07:35:00'){
                        $status='OK';
            }else if($type!='Indirecto' and $timeNow > '07:35:00'){
                        $status='R';
            }
                $updateregistro=mysqli_query($con,"UPDATE assistence SET `$todayIs`='$status' WHERE `name`='$name' ORDER BY id DESC LIMIT 1"); 
                
           header("Location:  asistencias.php?success=Bienvenido $row[employeeName] , su entrada ha sido registrada");
        } else {

           if($rowRegistro['permisoSalida']== '' and $rowRegistro['permisoEntrada']== ''){
                 $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET salida='$timeNow', permisoSalida='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
               if($timeNow <'17:20:00'){
                 $updateregistro=mysqli_query($con,"UPDATE assistence SET `$todayIs`='$datosAssistencia'.'-Salida' WHERE `name`='$name' ORDER BY id DESC LIMIT 1");
               }
                 header("Location:  asistencias.php?success=Gracias, $row[employeeName] , su salida ha sido registrada ");  
            }else if(($rowRegistro['permisoSalida']!= null) and ($rowRegistro['permisoEntrada']== null)){
                 $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET salida=NULL, permisoEntrada='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");  

                  $updateregistro=mysqli_query($con,"UPDATE assistence SET `$todayIs`='$datosAssistencia' WHERE `name`='$name' ORDER BY id DESC LIMIT 1");
                header("Location:  asistencias.php?success=Gracias, $row[employeeName] , su entrada ha sido registrada ");  

            }else if($rowRegistro['salida'] == null and $rowRegistro['permisoEntrada']!= "" and $rowRegistro['permisoSalida']!= ""){
                $timeIncial=strtotime($rowRegistro['permisoEntrada']);//12:08:00
                $tiempoFinal=strtotime($rowRegistro['permisoSalida']);//11:05:00
                $diferencias=abs($tiempoFinal-$timeIncial);
                $resultado=strtotime($timeNow)-$diferencias;
                $nuevoTiempo=date('H:i:s', $resultado);
                 $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET salida='$timeNow', permisoEntrada=null, permisoSalida='$nuevoTiempo' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                  if($timeNow <'17:20:00'){
                 $updateregistro=mysqli_query($con,"UPDATE assistence SET `$todayIs`='$datosAssistencia'.'-Salida' WHERE `name`='$name' ORDER BY id DESC LIMIT 1");
               }
                header("Location:  asistencias.php?success=Gracias, $row[employeeName] , su salida ha sido registrada ");  
            }else{
             header("Location:  asistencias.php?success=tiempo no  registrado&color=Red");    
            }
        }
        // header("Location:  asistencias.php");
    }
}
               
            
           

//header("Location:  asistencias.php");
