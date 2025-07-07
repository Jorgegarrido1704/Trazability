<?php 
include "../app/conection.php";
$week = (int)(date('W'));

//Search fist intereaction in the table assitence for comparation
$lastWeek=mysqli_query($con,"SELECT week FROM assistence WHERE week = $week ORDER BY id DESC LIMIT 1");
if(mysqli_num_rows($lastWeek) <= 0){
    //search all personal
    $personal = mysqli_query($con,"SELECT  employeeLider, employeeName, employeeNumber FROM personalberg WHERE `status` != 'Baja' ORDER BY `status` ASC");
    while($row = mysqli_fetch_array($personal)){
        $lider = $row['employeeLider'];
        $name = $row['employeeName'];
        $idEmployee = $row['employeeNumber'];
      
        //insert into assitence table
        $insert = mysqli_query($con,"INSERT INTO assistence (`week`, `lider`, `name`, `id_empleado`) VALUES ('$week', '$lider', '$name', '$idEmployee')");
    }
    
}else{
    echo "Ya se ha registrado la asistencia de esta semana";
}
header("location:./assistence.php");