<?php
require "../../app/conection.php";
require "timesReg.php";


if (isset($_GET['np'])) {
    $paramNp = $_GET['np'];
    if (strpos($paramNp, ',') !== false) {
        $datos = explode(',', $paramNp); 
    } else {
        $datos = [$paramNp]; 
    }
} else {
    echo "No se han recibido números de parte.";
    header("location:../registro.php");
}

foreach ($datos as $np) {
$tipoSplice=[];   

    $splices = mysqli_query($con, "SELECT dataFrom FROM listascorte WHERE pn='$np' AND dataFrom IS NOT NULL and (dataFrom LIKE 'SPL%' or dataFrom LIKE 'spl%'
    or dataFrom  LIKE 'Spl%' or dataFrom  LIKE 'splice%' or dataFrom  LIKE 'SPLICE%' or dataFrom  LIKE 'Empalme%')");
    if (mysqli_num_rows($splices) > 0) {
        while($row = mysqli_fetch_array($splices)){
            $splice=$row['dataFrom']; 
            if (isset($tipoSplice[$splice])) {
                $tipoSplice[$splice]=$tipoSplice[$splice]+1;
            } else {
                $tipoSplice[$splice] = 1;
            }
        }
    }
         $splices1 = mysqli_query($con, "SELECT dataTo FROM listascorte WHERE pn='$np' AND dataTo IS NOT NULL and (dataTo LIKE 'SPL%' or dataTo LIKE 'spl%'
    or dataTo  LIKE 'Spl%' or dataTo  LIKE 'splice%' or dataTo  LIKE 'SPLICE%' or dataTo  LIKE 'Empalme%')");
    if (mysqli_num_rows($splices1) > 0) {
        while($row = mysqli_fetch_array($splices1)){
            $splice=$row['dataTo'];
            if (isset($tipoSplice[$splice])) {
                $tipoSplice[$splice]=$tipoSplice[$splice]+1;
            } else {
                $tipoSplice[$splice] = 1;
            }
        }
        
    }


    foreach ($tipoSplice as $key => $value) {
            $QtySpliceA=$QtySpliceB=0;
            $QtySpliceA=intval($value/2)+intval($value%2);
            $QtySpliceB=intval($value/2);
            $random2 = rand(0, count($setSplice) - 1);
            $random3 = rand(0, count($applySpleceInMachine) - 1);
            $createSpliceTime = $setSplice[$random2];
            $applySpliceTime = $applySpleceInMachine[$random3];
            $timpoSetSplice = ($QtySpliceA * $createSpliceTime) * $createSpliceTime;
            $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','10341','Pend','Create set for splice $QtySpliceA : $QtySpliceB','1','$timpoSetSplice','600')");
            $insertar2 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','10301','FB110','splice set apply with machine','1','$applySpliceTime','600')");
            $random = rand(0, count($setHeadShrink) - 1);
            $random1 = rand(0, count($burnHeatGun) - 1);
            $leyenda1 = "Set HeadShrink in splice ";
            $leyenda2 = "Burn Heatshrirnk w/headgun in Splice ";
            $timeHeadShrink = $setHeadShrink[$random];
            $gunHeatGun = $burnHeatGun[$random1];

            $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
                    VALUES ('$np','10361','Pend','$leyenda1','1','$timeHeadShrink','600')");
            $insertar2 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
                    VALUES ('$np','10401','Pend','$leyenda2','1','$gunHeatGun','600')");
                    echo $key." ".$QtySpliceA.":".$QtySpliceB."<br>";
        }
    }

    header("location:../registro.php");
            
    

   

