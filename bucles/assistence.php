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

$personalIndirectos = mysqli_query($con, "SELECT  employeeNumber, typeWorker,`status` FROM personalberg ORDER BY typeWorker ASC");
while($row = mysqli_fetch_array($personalIndirectos)){
    $indirectos = $row['employeeNumber'];
    $tipo = $row['typeWorker'];
    $status = $row['status'];
if($tipo == 'Indirecto' && $status == 'Activo'){   
$registro=mysqli_query($con, "UPDATE assistence SET `$day` = 'OK' WHERE `week` = $week AND `id_empleado` = '$indirectos' ");
}elseif($tipo == 'Practicante' && $status == 'Activo'){
    $registro=mysqli_query($con, "UPDATE assistence SET `$day` = 'PCT' WHERE `week` = $week AND `id_empleado` = '$indirectos' ");
}elseif($status == 'PSS'){
    $registro=mysqli_query($con, "UPDATE assistence SET `$day` = 'PSS' WHERE `week` = $week AND `id_empleado` = '$indirectos' ");
} elseif($status == 'Vacaciones'){
    $registro=mysqli_query($con, "UPDATE assistence SET `$day` = 'V' WHERE `week` = $week AND `id_empleado` = '$indirectos' ");
}else if($status == 'PCS'){
    $registro=mysqli_query($con, "UPDATE assistence SET `$day` = 'PCS' WHERE `week` = $week AND `id_empleado` = '$indirectos' ");
} else if($status == 'Suspension'){
    $registro=mysqli_query($con, "UPDATE assistence SET `$day` = 'SUS' WHERE `week` = $week AND `id_empleado` = '$indirectos' ");    
}else if($status == 'Incapacidad'){
    $registro=mysqli_query($con, "UPDATE assistence SET `$day` = 'INC' WHERE `week` = $week AND `id_empleado` = '$indirectos' ");
}else if($status == 'Asimilado'){
    $registro=mysqli_query($con, "UPDATE assistence SET `$day` = 'ASM' WHERE `week` = $week AND `id_empleado` = '$indirectos' ");
}else if($status == 'Servicio comprado'){
    $registro=mysqli_query($con, "UPDATE assistence SET `$day` = 'SCE' WHERE `week` = $week AND `id_empleado` = '$indirectos' ");
    
}


}

header("location:../errores/mejoraTiempoPrecio.php");