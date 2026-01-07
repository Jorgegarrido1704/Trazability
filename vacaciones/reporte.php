<?php 
require "../app/conection.php";
 
$personal=mysqli_query($con,"SELECT employeeNumber, employeeName,DaysVacationsAvailble FROM `personalberg` WHERE `status` != 'Baja' order by employeeNumber asc");
if(mysqli_num_rows($personal) > 0){
while($row=mysqli_fetch_array($personal)){
    $employeeNumber=$row['employeeNumber'];
    $employeeName=$row['employeeName'];
    $DaysVacationsAvailble=$row['DaysVacationsAvailble'];
    echo "<table border='1'>";
    echo "<tr>";
    echo "<td>";
    echo $employeeNumber;
    echo "</td>";
    echo "<td>";
    echo $employeeName;
    echo "</td>";
    echo "<td>";
    echo $DaysVacationsAvailble;
    echo "</td>";
    echo "</tr>";
    
    echo "</table>";
    echo "<br>";
    $vacaciones=mysqli_query($con,"SELECT fecha_de_solicitud,usedYear   FROM `registro_vacaciones` WHERE id_empleado='$employeeNumber' ");
    if(mysqli_num_rows($vacaciones) > 0){
    while($rowVacaciones=mysqli_fetch_array($vacaciones)){
        $fecha_de_solicitud=$rowVacaciones['fecha_de_solicitud'];
        $usedYear=$rowVacaciones['usedYear'];
        echo $fecha_de_solicitud." | ".$usedYear;
        echo "<br>";
    }
}
echo "<hr>";
}

}


