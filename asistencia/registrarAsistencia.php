<?php
require '../app/conection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardCode = $_POST["cardCode"];
    if(strlen($cardCode) == 4){
        $cardCode = "i" . $cardCode;
        
    }
    $days=['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
    //day today
    $dayToday=date('N')-1;
    $todayIs=$days[$dayToday];
    $action = $_POST["action"];
    if (strpos($cardCode, '|')) {
        $cardCode = explode('|', $cardCode)[0];
        $comentario = "registro con QR";
    } else {
        $comentario = "registro con codigo manual o codigo de barras";
    }
    echo $cardCode . "<br>" . $comentario . "<br>";
    $dateNow = date("Y-m-d");
    $timeNow = date("H:i:s");
    $weekDay=date('w');

    //Busqueda de empleado con tarjeta valida
    $bucarEmpleado = mysqli_query($con, "SELECT * FROM personalberg WHERE employeeNumber='$cardCode' AND `status` !='Baja'");
    if (mysqli_num_rows($bucarEmpleado) <= 0) {
        header("Location:  asistencias.php?success=tarjeta Invalida, vuelva a intentarlo");
    } else {
        $row = mysqli_fetch_assoc($bucarEmpleado);
        $cardCode = $row['employeeNumber'];
        $type = $row['typeWorker'];
        
        $buscarRegistro = mysqli_query($con, "SELECT * From relogchecador WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow' ORDER BY id DESC LIMIT 1");
        $rowRegistro = mysqli_fetch_assoc($buscarRegistro);
        if (mysqli_num_rows($buscarRegistro) <= 0) {
            $insertarAsistencia = mysqli_query($con, "INSERT INTO relogchecador (employeeNumber,fechaRegistro,entrada,comentario) VALUES ('$cardCode','$dateNow','$timeNow','$comentario')");
            if($type=='Indirecto' && $timeNow < '08:30:00'){
                $updateregistro=mysqli_query($con,"UPDATE assistence SET $todayIs='OK' WHERE `week`='$weekDay' AND id_empleado='$cardCode' ");
                }else if($type=='Indirecto' && $timeNow > '08:30:00'){
                                $updateregistro=mysqli_query($con,"UPDATE assistence SET $todayIs='R' WHERE `week`='$weekDay' AND id_empleado='$cardCode' ");
            }if($type=='Directo' && $timeNow < '07:35:00'){
                                $updateregistro=mysqli_query($con,"UPDATE assistence SET $todayIs='OK' WHERE `week`='$weekDay' AND id_empleado='$cardCode' ");
                }else if($type=='Directo' && $timeNow > '07:35:00'){
                                $updateregistro=mysqli_query($con,"UPDATE assistence SET $todayIs='R' WHERE `week`='$weekDay' AND id_empleado='$cardCode' ");
                }
           header("Location:  asistencias.php?success=Bienvenido $row[employeeName] , su entrada ha sido registrada");
        } else {
            if ($action == 'entrada') {
                header("Location:  asistencias.php?success= $row[employeeName] , su entrada ya registrada");

            } else if ($action == 'salida') {
                if ($rowRegistro['entrada'] == null) {
                    header("Location:  asistencias.php?success= $row[employeeName] , su entrada no fue registrada, dirijase con recurson humanos");
                }else if ($rowRegistro['salida'] != null) {
                    header("Location:  asistencias.php?success= $row[employeeName] , su salida ya registrada");

                }else if($rowRegistro['salida'] == null) {                
                $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET salida='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                header("Location:  asistencias.php?success=Gracias por tu esfuerzo $row[employeeName] , su salida ha sido registrada");
            }
            } else if ($action == 'desayuno') {
                if ($rowRegistro['desayunoSalida'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET desayunoSalida='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                header("Location:  asistencias.php?success=Buen provecho $row[employeeName] , su salida a desayuno ha sido registrada");
                } else if ($rowRegistro['desayunoEntrada'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET desayunoEntrada='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                header("Location:  asistencias.php?success=Bienvenido de nuevo $row[employeeName] , su entrada de desayuno ha sido registrada");
                }else{
                header("Location:  asistencias.php?success= $row[employeeName] , ya registraste la entrada y salida de desayuno");
                }
            } else if ($action == 'comida') {
                if ($rowRegistro['comidaSalida'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET comidaSalida='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                header("Location:  asistencias.php?success=Buen provecho $row[employeeName] , su salida a comida ha sido registrada");
                } else if ($rowRegistro['comidaEntrada'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET comidaEntrada='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                header("Location:  asistencias.php?success=Bienvenido de nuevo $row[employeeName] , su entrada de comida ha sido registrada");
                }else {
                header("Location:  asistencias.php?success= $row[employeeName] , ya registraste la entrada y salida de comida");
                }
            } else if ($action == 'permiso') {
                if ($rowRegistro['permisoSalida'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET permisoSalida='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                    header("Location:  asistencias.php?success=$row[employeeName] , su salida por permiso ha sido registrada");
                } else if ($rowRegistro['permisoEntrada'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET permisoEntrada='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                    header("Location:  asistencias.php?success=$row[employeeName] , su entrada por permiso ha sido registrada");
                } else if ($rowRegistro['permiso2Salida'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET permiso2Salida='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                    header("Location:  asistencias.php?success=$row[employeeName] , su segunda salida por permiso ha sido registrada");
                } else if ($rowRegistro['permiso2Entrada'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET permiso2Entrada='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                    header("Location:  asistencias.php?success=$row[employeeName] , su segunda entrada por permiso ha sido registrada");
                }else {
                    header("Location:  asistencias.php?success=$row[employeeName] , ya registraste todas tus entradas y salidas por permiso, Si necesitas más permisos, por favor dirígete con recursos humanos");
                }
            }
        }
    }
}

//header("Location:  asistencias.php");
