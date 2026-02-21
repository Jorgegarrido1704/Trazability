<?php
require "../../app/conection.php";
require "timesReg.php";

// 1. Limpieza inicial 
$worksToDelete = ['11001','10601','11101','10701','11000','11050','10801','10901'];
$inWorks = "'" . implode("','", $worksToDelete) . "'";
function insertRouting($con, $np, $work, $desc, $time){
    if ($time <= 0) return;
    $time = max(30, round($time));
    mysqli_query($con,"
        INSERT INTO routing_models 
        (pn_routing, work_routing, posible_stations, work_description, QtyTimes, timePerProcess, setUp_routing)
        VALUES ('$np','$work','Pend','$desc','1','$time','150')
    ");
}

if (isset($_GET['np'])) {
    $paramNp = $_GET['np'];
    if (strpos($paramNp, ',') !== false) {
        $datos = explode(',', $paramNp); 
    } else {
        $datos = [$paramNp]; 
    }
} else {
 
    header("location:../registro.php");
}

foreach ($datos as $np) {
 mysqli_query($con, "DELETE FROM routing_models WHERE work_routing IN ($inWorks) AND pn_routing = '$np'");


    // ---------- LOOMING + TAPPING 835 ----------         
        $res = mysqli_query($con,"
            SELECT item, qty 
            FROM datos 
            WHERE part_num='$np' 
            AND (item LIKE 'LTP%' OR item='TAPE-835')
        ");

        $loomingTotal = 0;
        $tapingTotal  = 0;
        $normalTaping = 0;
        $tappingandlooming=0;

        while ($d = mysqli_fetch_assoc($res)) {
            $qty = $d['qty'];
            $time = $loomingTime[array_rand($loomingTime)];

            if ($d['item'] === 'TAPE-835') {
                $tapingTotal += ($time * $qty) * 1.25;
               
            } else {
                $loomingTotal += $time * $qty;
                 $normalTaping += $time * $qty;
            }
        }
                $tappingandlooming=($loomingTotal+$tapingTotal) * 1.20;
                $normalTaping *= 1.55;
            
        insertRouting($con,$np,'11000','looming',$loomingTotal);
        insertRouting($con,$np,'11001','Taping/Looming',$tappingandlooming);
        insertRouting($con,$np,'10901','Taping Body/Assembly',$normalTaping);

    // ----------  TAPPING 25 ----------         
        $res = mysqli_query($con,"
            SELECT item, qty 
            FROM datos 
            WHERE part_num='$np' 
            AND (item LIKE 'LTP%' OR item='TAPE-25')
        ");

        $loomingTotal = 0;
     
        $normalTaping = 0;

        while ($d = mysqli_fetch_assoc($res)) {
            $qty = $d['qty'];
            $time = $loomingTime[array_rand($loomingTime)];

            if ($d['item'] === 'TAPE-25') {
                $normalTaping += ($time * $qty) * 1.25;
               
            } else {
                $loomingTotal += $time * $qty;
            }
        }
        if($loomingTotal <= 0){
            $normalTaping= round(($normalTaping*2.5),2);
            insertRouting($con,$np,'10901','Taping Body/Assembly',$normalTaping);
        } 

    // ---------- LABELING ---------- 
        $res = mysqli_query($con,"
            SELECT SUM(qty)*5 AS total 
            FROM datos 
            WHERE part_num='$np' AND item LIKE 'LW-%'
        ");
        if ($row = mysqli_fetch_assoc($res)) {
            insertRouting($con,$np,'11050','labeling',$row['total']);
        }

    // ---------- BRAIDING ---------- 
        $res = mysqli_query($con,"
            SELECT qty 
            FROM datos 
            WHERE part_num='$np' AND item LIKE 'LSL%-%'
        ");
        $braidTotal = 0;
        while ($d = mysqli_fetch_assoc($res)) {
            $time = $loomingTime[array_rand($loomingTime)];
            $braidTotal += ($time * $d['qty']) * 1.33;
        }
        insertRouting($con,$np,'11101','Braiding',$braidTotal);
    // ---------- TIE ---------- 
        $res = mysqli_query($con,"
            SELECT SUM(qty)*5.3*1.15 AS total
            FROM datos 
            WHERE part_num='$np' AND item LIKE 'PA%-%'
        ");
        if ($row = mysqli_fetch_assoc($res)) {
            insertRouting($con,$np,'10801','Add Ties',$row['total']);
        }
    }
    


    header("location:../registro.php");


