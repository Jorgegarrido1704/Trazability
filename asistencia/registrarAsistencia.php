<?php
require '../app/conection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardCode = $_POST["cardCode"];
    $action = $_POST["action"];
    if (strpos($cardCode, '$')) {
        $cardCode = explode('$', $cardCode)[0];
        $comentario = "$" . "registro con QR";
    } else {
        $comentario = "registro con codigo manual o codigo de barras";
    }
    $cardCode = "i" . $cardCode;
    echo $cardCode . "<br>" . $comentario . "<br>";
    $dateNow = date("Y-m-d");
    $timeNow = date("H:i:s");

    //Busqueda de empleado con tarjeta valida
    $bucarEmpleado = mysqli_query($con, "SELECT * FROM personalberg WHERE employeeNumber='$cardCode'");
    if (mysqli_num_rows($bucarEmpleado) <= 0) {
        echo "El código de tarjeta no es válido.<br>";
        header("Location:  asistencias.php?error=tarjetaInvalida");
    } else {
        $row = mysqli_fetch_assoc($bucarEmpleado);
        $cardCode = $row['employeeNumber'];

        $buscarRegistro = mysqli_query($con, "SELECT * From relogchecador WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow' ORDER BY id DESC LIMIT 1");
        $rowRegistro = mysqli_fetch_assoc($buscarRegistro);
        if (mysqli_num_rows($buscarRegistro) <= 0) {
            $insertarAsistencia = mysqli_query($con, "INSERT INTO relogchecador (employeeNumber,fechaRegistro,entrada) VALUES ('$cardCode','$dateNow','$timeNow')");
            echo "Asistencia registrada correctamente<br>";
        } else {
            if ($action == 'entrada') {
                echo "Ya se ha registrado una entrada para hoy.<br>";
            } else if ($action == 'salida') {
                $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET salida='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
            } else if ($action == 'desayuno') {
                if ($rowRegistro['desayunoSalida'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET desayunoSalida='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                } else if ($rowRegistro['desayunoEntrada'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET desayunoEntrada='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                }
            } else if ($action == 'comida') {
                if ($rowRegistro['comidaSalida'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET comidaSalida='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                } else if ($rowRegistro['comidaEntrada'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET comidaEntrada='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                }
            } else if ($action == 'permiso') {
                if ($rowRegistro['permisoSalida'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET permisoSalida='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                } else if ($rowRegistro['permisoEntrada'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET permisoEntrada='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                } else if ($rowRegistro['permiso2Salida'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET permiso2Salida='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                } else if ($rowRegistro['permiso2Entrada'] == null) {
                    $insertarAsistencia = mysqli_query($con, "UPDATE relogchecador SET permiso2Entrada='$timeNow' WHERE employeeNumber='$cardCode' AND fechaRegistro='$dateNow'");
                }
            }
        }
    }
}

header("Location:  asistencias.php");
