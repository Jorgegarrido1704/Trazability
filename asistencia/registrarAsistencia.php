<?php
require '../app/conection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $cardCode = isset($_GET["cardCode"]) ? trim($_GET["cardCode"]) : '';
    $action   = isset($_GET["action"]) ? $_GET["action"] : '';

    if (strlen($cardCode) == 4) {
        $cardCode = "i" . $cardCode;
    }

    if (strlen($cardCode) > 6) {
        header("Location: asistencias.php?success=" . urlencode("tarjeta Invalida, vuelva a intentarlo") . "&color=Red");
        exit; // <-- IMPORTANTE: sin esto el script seguía ejecutándose después del redirect
    }

    $days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
    $dayToday = date('N') - 1;
    $todayIs  = $days[$dayToday];

    $comentario = "registro con QR";
    $dateNow    = date("Y-m-d");
    $yesterday  = date("Y-m-d", strtotime("-1 day"));
    $timeNow    = date("H:i:s");

    // ---------------------------------------------------------------
    // Búsqueda de empleado con tarjeta válida (prepared statement)
    // ---------------------------------------------------------------
    $stmt = mysqli_prepare($con, "SELECT `typeWorker`, `employeeName`, `employeeShift`
                                   FROM personalberg
                                   WHERE employeeNumber = ? AND `status` != 'Baja'");
    mysqli_stmt_bind_param($stmt, "s", $cardCode);
    mysqli_stmt_execute($stmt);
    $bucarEmpleado = mysqli_stmt_get_result($stmt);

    if (!$bucarEmpleado || mysqli_num_rows($bucarEmpleado) <= 0) {
        header("Location: asistencias.php?success=" . urlencode("tarjeta Invalida, vuelva a intentarlo") . "&color=Red");
        exit;
    }

    $row   = mysqli_fetch_assoc($bucarEmpleado);
    $type  = $row['typeWorker'];
    $name  = $row['employeeName'];
    $shift = $row['employeeShift'];

    $fechaRegistro = $dateNow;
    if ($shift == 'secondShift' && $timeNow > '00:00:00' && $timeNow < '07:30:00') {
        $fechaRegistro = $yesterday;
    }

    $stmt = mysqli_prepare($con, "SELECT * FROM relogchecador
                                   WHERE employeeNumber = ? AND fechaRegistro = ?
                                   ORDER BY id DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "ss", $cardCode, $fechaRegistro);
    mysqli_stmt_execute($stmt);
    $buscarRegistro = mysqli_stmt_get_result($stmt);
    $rowRegistro    = mysqli_fetch_assoc($buscarRegistro);

    if (mysqli_num_rows($buscarRegistro) <= 0) {

       
        $stmt = mysqli_prepare($con, "INSERT INTO relogchecador (employeeNumber, fechaRegistro, entrada, comentario)
                                       VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $cardCode, $fechaRegistro, $timeNow, $comentario);
        mysqli_stmt_execute($stmt);

        // ---------------------------------------------------
        // Cálculo de $status
        // Se definen umbrales por turno:
        //   - Turno 1 (firstShift / no-Indirecto): 07:30 - 17:30
        //   - Turno 2 (secondShift): 19:00 - 07:00 (día siguiente)
        // Antes esta cadena estaba rota por un "if" en vez de
        // "elseif", lo que hacía que TODO empleado "Indirecto"
        // terminara sobrescrito con '-' sin importar la hora real.
        // ---------------------------------------------------
        if ($type == 'Practicante') {
            $status = 'PCT';
        } elseif ($type == 'Asimilado') {
            $status = 'ASM';
        } elseif ($type == 'Servicio comprado') {
            $status = 'SCE';
        } elseif ($type == 'Indirecto' && $timeNow <= '08:15:00') {
            $status = 'OK';
        } elseif ($type == 'Indirecto' && $timeNow > '08:15:00' && $timeNow < '17:30:00') {
            $status = 'R';
        } elseif ($type != 'Indirecto' && $shift != 'secondShift' && $timeNow <= '07:35:00') {
            $status = 'OK';
        } elseif ($type != 'Indirecto' && $shift != 'secondShift' && $timeNow > '07:35:00' && $timeNow < '17:30:00') {
            $status = 'R';
        } elseif ($shift == 'secondShift' && ($timeNow >= '19:00:00' || $timeNow < '07:30:00') && $timeNow <= '19:15:00') {
            $status = 'N';
        } elseif ($shift == 'secondShift' && $timeNow >= '19:15:00') {
            $status = 'R';
         }else {
            $status = ' ';
        }

        // Update por id_empleado en vez de name (evita colisiones
        // de nombres duplicados). Se asume que `assistence` tiene
        // columna id_empleado; si no la tiene, agrégala.
        $stmt = mysqli_prepare($con, "UPDATE assistence SET `$todayIs` = ?
                                       WHERE `id_empleado` = ?
                                       ORDER BY id DESC LIMIT 1");
        mysqli_stmt_bind_param($stmt, "ss", $status, $cardCode);
        mysqli_stmt_execute($stmt);

        header("Location: asistencias.php?success=" . urlencode("Bienvenido {$row['employeeName']}, su entrada ha sido registrada"));
        exit;

    } else {

        // -------------------------------------------------------
        // Ya existe registro hoy -> lógica de salida / permisos
        // -------------------------------------------------------
        if ($rowRegistro['permisoSalida'] == '' && $rowRegistro['permisoEntrada'] == '') {

            $stmt = mysqli_prepare($con, "UPDATE relogchecador SET salida = ?, permisoSalida = ?
                                           WHERE employeeNumber = ? AND fechaRegistro = ?");
            mysqli_stmt_bind_param($stmt, "ssss", $timeNow, $timeNow, $cardCode, $fechaRegistro);
            mysqli_stmt_execute($stmt);

            header("Location: asistencias.php?success=" . urlencode("Gracias, {$row['employeeName']}, su salida ha sido registrada"));
            exit;

        } elseif (($rowRegistro['permisoSalida'] != null) && ($rowRegistro['permisoEntrada'] == null)) {

            $stmt = mysqli_prepare($con, "UPDATE relogchecador SET salida = NULL, permisoEntrada = ?
                                           WHERE employeeNumber = ? AND fechaRegistro = ?");
            mysqli_stmt_bind_param($stmt, "sss", $timeNow, $cardCode, $fechaRegistro);
            mysqli_stmt_execute($stmt);

            header("Location: asistencias.php?success=" . urlencode("Gracias, {$row['employeeName']}, su entrada ha sido registrada"));
            exit;

        } elseif ($rowRegistro['salida'] == null && $rowRegistro['permisoEntrada'] != "" && $rowRegistro['permisoSalida'] != "") {

            $timeIncial   = strtotime($rowRegistro['permisoEntrada']);
            $tiempoFinal  = strtotime($rowRegistro['permisoSalida']);
            $diferencias  = abs($tiempoFinal - $timeIncial);
            $resultado    = strtotime($timeNow) - $diferencias;
            $nuevoTiempo  = date('H:i:s', $resultado);

            $stmt = mysqli_prepare($con, "UPDATE relogchecador SET salida = ?, permisoEntrada = NULL, permisoSalida = ?
                                           WHERE employeeNumber = ? AND fechaRegistro = ?");
            mysqli_stmt_bind_param($stmt, "ssss", $timeNow, $nuevoTiempo, $cardCode, $fechaRegistro);
            mysqli_stmt_execute($stmt);

            header("Location: asistencias.php?success=" . urlencode("Gracias, {$row['employeeName']}, su salida ha sido registrada"));
            exit;

        } else {
            header("Location: asistencias.php?success=" . urlencode("tiempo no registrado") . "&color=Red");
            exit;
        }
    }
}