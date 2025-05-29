<?php

require "../app/conection.php";

$week = (int)(date('W'));
$today = (int)(date('N'));

echo $today . "<br>";
switch ($today) {
    case 1:
        $day = "lunes";
        break;
    case 2:
        $day = "martes";
        break;
    case 3:
        $day = "miercoles";
        break;
    case 4:
        $day = "jueves";
        break;
    case 5:
        $day = "viernes";
        break;
    default:    
        break;
}

$personalIndirectos = mysqli_query($con, "SELECT * FROM personalberg WHERE typeWorker = 'Indirecto'");
while($row = mysqli_fetch_array($personalIndirectos)){
    $indirectos = $row['employeeNumber'];

// Search first interaction in the table assistence for comparation
$registro=mysqli_query($con, "UPDATE assistence SET `$day` = 'OK' WHERE `week` = $week AND `id_empleado` = '$indirectos' AND `$day` = '' ");
if($registro){
    echo "Asistencia actualizada para el empleado <br>";
} else {
    echo "Error al actualizar la asistencia: " . mysqli_error($con);
}

}

header("location:../errores/mejoraTiempoPrecio.php");