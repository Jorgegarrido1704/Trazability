<?php 

require "../app/conection.php";
date_default_timezone_set("america/Mexico_City");

$busqueda = mysqli_query($con,"SELECT `id`, `employeeNumber`, `employeeName`, `employeeArea`, `employeeLider`, 
`DateIngreso`, `DaysVacationsAvailble`, `lastYear`, `currentYear`, `nextYear` FROM `personalberg` ");
if(mysqli_num_rows($busqueda) > 0){
    while($row = mysqli_fetch_array($busqueda)){
        $id = $row['id'];
        $employeeNumber = $row['employeeNumber'];
        $employeeName = $row['employeeName'];
        $employeeArea = $row['employeeArea'];
        $employeeLider = $row['employeeLider'];
        $DateIngreso = $row['DateIngreso'];
        $DaysVacationsAvailble = $row['DaysVacationsAvailble'];
        $lastYear = $row['lastYear'];
        $currentYear = $row['currentYear'];
        $nextYear = $row['nextYear'];
        

        $date = date("Y-m-d");
        $anio = date("Y");
        $nextYear = date("Y", strtotime($date . "+1 year"));
        $empleadoIngeso=date("Y", strtotime($DateIngreso));
        $cumple= $anio."-".substr($DateIngreso,5,2)."-".substr($DateIngreso,8,2);
        $cumple=date("Y-m-d", strtotime($cumple));
        $difference = date_diff(date_create($cumple), date_create($date));
        $difference =(int) $difference->format('%R%a');
        if($difference<0){
        $anosEnEmpleado=$anio-$empleadoIngeso;
        } else{
        $anosEnEmpleado=($anio+1)-$empleadoIngeso;
        }
        if($anosEnEmpleado==1){
            $diasVacaciones=12;
        }else if($anosEnEmpleado==2){
            $diasVacaciones=14;
        }else if($anosEnEmpleado==3){
            $diasVacaciones=16;
        }else if($anosEnEmpleado==4){
            $diasVacaciones=18;
        }else if($anosEnEmpleado==5){
            $diasVacaciones=20;
        }else if($anosEnEmpleado>5 && $anosEnEmpleado<11){
            $diasVacaciones=22;
        }else if($anosEnEmpleado>10 && $anosEnEmpleado<15){
            $diasVacaciones=24;
        }else if($anosEnEmpleado>15 && $anosEnEmpleado<21){
            $diasVacaciones=26;
        }else if($anosEnEmpleado>20 && $anosEnEmpleado<25){
            $diasVacaciones=28;
        }else if($anosEnEmpleado>25 && $anosEnEmpleado<31){
            $diasVacaciones=30;
        }else if($anosEnEmpleado>30){
            $diasVacaciones=32;
        }else {
            $diasVacaciones=0;
        }
        
        if($difference<0){
            $absDifference=abs($difference);
        $diasVacacionesPendientes=(int)(($diasVacaciones/365)*(365-$absDifference));
        $total=$diasVacacionesPendientes+$lastYear;
        $menos= mysqli_query($con,"SELECT COUNT(*) as total FROM `registro_vacaciones` WHERE  `fecha_de_solicitud` LIKE '$anio%' AND (`id_empleado` = '$employeeNumber' or `id_empleado` LIKE '$employeeLider-%' or `id_empleado` LIKE '%-$employeeLider') AND `usedYear` = '$anio' ");
        if(mysqli_num_rows($menos) > 0){
        $menos = mysqli_fetch_array($menos);
        $total=$total-$menos['total'];
         echo $menos['total'] . "<br>";
        $diasVacacionesPendientes=$diasVacacionesPendientes-$menos['total'];
    }else {
        $total=$total;}
        $subit =mysqli_query($con,"UPDATE `personalberg` SET `currentYear` = '$diasVacacionesPendientes', `DaysVacationsAvailble` = '$total' WHERE `id` = '$id' ");
        } else{
        $diasVacacionesPendientes=(int)(($diasVacaciones/365)*$difference);
        $total=$currentYear+$diasVacacionesPendientes+$lastYear;
         $menos= mysqli_query($con,"SELECT COUNT(*) as total FROM `registro_vacaciones` WHERE  `fecha_de_solicitud` LIKE '$anio%' AND (`id_empleado` = '$employeeNumber' or `id_empleado` LIKE '$employeeLider-%' or `id_empleado` LIKE '%-$employeeLider') AND `usedYear` = '$nextYear' ");
        if(mysqli_num_rows($menos) > 0){
        $menos = mysqli_fetch_array($menos);
        echo $menos['total'] . "<br>";
        $total=$total-$menos['total'];
        $diasVacacionesPendientes=$diasVacacionesPendientes-$menos['total'];
    }else {
        $total=$total;}

        $subit =mysqli_query($con,"UPDATE `personalberg` SET `nextYear` = '$diasVacacionesPendientes', `DaysVacationsAvailble` = '$total' WHERE `id` = '$id' ");
        }
        
        echo $employeeName. " anos en la empresa: ". $anosEnEmpleado. " dias de vacaciones pendientes: ". $diasVacacionesPendientes. " dias de diferencia: ".$difference."<br>";

    }
}else{
    echo "No se encontraron registros";
}

header("location:../errores/mejoraTiempoPrecio.php");