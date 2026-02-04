<?php
require "../../app/conection.php";
require "timesReg.php";

/*
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
}*/
//$delete=mysqli_query($con,"DELETE FROM routing_models WHERE pn_routing='$np' and work_routing='10381'");
$buscarNP = mysqli_query($con, "SELECT DISTINCT pn FROM `listascorte` WHERE pn IS NOT NULL ORDER BY pn ASC");
while ($rowNP = mysqli_fetch_assoc($buscarNP)) {
    $np = $rowNP['pn']; 
     //print("Procesando NP: $np <br>");
    $delete=mysqli_query($con,"DELETE FROM routing_models WHERE pn_routing='$np' and (work_routing='11001' OR work_routing='10601' or work_routing='11101' or work_routing='10701' or work_routing='11101' or work_routing='11000' or work_routing='11050' or work_routing='10801')"); 
     
    //looming LTP
    $buscarinDatos=mysqli_query($con,"SELECT item,qty FROM datos WHERE item LIKE 'LTP%' AND part_num='$np'");
    if(mysqli_num_rows($buscarinDatos)>0){
        $timeLooming = $totalLoomingTime = $timetotalLoomingTime = $tappingTime= 0;
        while($rowDatos=mysqli_fetch_assoc($buscarinDatos)){
            $item=$rowDatos['item'];
            $qty=$rowDatos['qty'];
            $randomKey = rand(0, count($loomingTime) - 1);
            $timeLooming = $loomingTime[$randomKey];
            $totalLoomingTime = $timeLooming * $qty;
            $timetotalLoomingTime += round($totalLoomingTime,0);
            //print("\t Item: $item, Cantidad: $qty, Tiempo Looming por unidad: $timeLooming seg, Tiempo total looming: $totalLoomingTime seg <br>");
        }
        if($timetotalLoomingTime<30){
            $timetotalLoomingTime=30;
        }
        //print("Tiempo total de looming para NP $np: $timetotalLoomingTime seg <br>");
        $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','11000','Pend','looming','1','$timetotalLoomingTime','150')");
            $insertar2 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','11001','Pend','Taping/Looming','1','$timetotalLoomingTime','150')");
        //print("Tiempo total de tapping looming para NP $np: $timetotalLoomingTime seg <br>");

    }
    //tape-835
      $buscarinDatos=mysqli_query($con,"SELECT item,qty FROM datos WHERE item = 'TAPE-835'  AND part_num='$np'");
    if(mysqli_num_rows($buscarinDatos)>0){
        $timeLooming = $totalLoomingTime = $timetotalLoomingTime = $tappingTime= 0;
        while($rowDatos=mysqli_fetch_assoc($buscarinDatos)){
            $item=$rowDatos['item'];
            $qty=$rowDatos['qty'];
            $randomKey = rand(0, count($loomingTime) - 1);
            $timeLooming = $loomingTime[$randomKey];
            $totalLoomingTime = $timeLooming * $qty;
            $timetotalLoomingTime += round($totalLoomingTime,0);
            //print("\t Item: $item, Cantidad: $qty, Tiempo tapping with TAPE-835 por unidad: $timeLooming seg, Tiempo total tapping: $totalLoomingTime seg <br>");
        }
        //print("Tiempo total de tapping para NP $np: $timetotalLoomingTime seg <br>");
         $insertar2 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','11001','Pend','Taping/Looming','1','$timetotalLoomingTime','150')");
    }
    //labeling
      $buscarinDatos=mysqli_query($con,"SELECT item,qty FROM datos WHERE item LIKE 'LW-%' AND part_num='$np'");
        if(mysqli_num_rows($buscarinDatos)>0){
            $timeLooming = $totalLoomingTime = $timetotalLoomingTime = $tappingTime= 0;
            while($rowDatos=mysqli_fetch_assoc($buscarinDatos)){
                $item=$rowDatos['item'];
                $qty=$rowDatos['qty'];
                $totalLoomingTime = 5 * $qty;
                $timetotalLoomingTime += round($totalLoomingTime,0);
                //print("\t Item: $item, Cantidad: $qty, Tiempo por etiquetas: 5 seg, Tiempo total etiqutado: $totalLoomingTime seg <br>");
            }
            //print("Tiempo total de labeling para NP $np: $timetotalLoomingTime seg <br><br>");
            $insertar2 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
                VALUES ('$np','11050','Pend','labeling','1','$timetotalLoomingTime','150')");

        }
     //braid
      $buscarinDatos=mysqli_query($con,"SELECT item,qty FROM datos WHERE item LIKE 'LSL%-%' AND part_num='$np'");
        if(mysqli_num_rows($buscarinDatos)>0){
        $timeLooming = $totalLoomingTime = $timetotalLoomingTime = $tappingTime= 0;
        while($rowDatos=mysqli_fetch_assoc($buscarinDatos)){
            $item=$rowDatos['item'];
            $qty=$rowDatos['qty'];
            $randomKey = rand(0, count($loomingTime) - 1);
            $timeLooming = $loomingTime[$randomKey];
            $totalLoomingTime = ($timeLooming * $qty)*1.33; //factor de 1.33 para braiding
            $timetotalLoomingTime += round($totalLoomingTime,0);
            //print("\t Item: $item, Cantidad: $qty, Tiempo de braiding por unidad: $timeLooming seg, Tiempo total braiding: $totalLoomingTime seg <br>");
        }
        //print("Tiempo total de braiding para NP $np: $timetotalLoomingTime seg <br>");
         $insertar2 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','11101','Pend','Braiding','1','$timetotalLoomingTime','150')");
    }
    //TIE on 
      $buscarinDatos=mysqli_query($con,"SELECT item,qty FROM datos WHERE item LIKE 'PA%-%' AND part_num='$np'");
        if(mysqli_num_rows($buscarinDatos)>0){
        $timeLooming = $totalLoomingTime = $timetotalLoomingTime = $tappingTime= 0;
        while($rowDatos=mysqli_fetch_assoc($buscarinDatos)){
            $item=$rowDatos['item'];
            $qty=$rowDatos['qty'];
           // $randomKey = rand(0, count($loomingTime) - 1);
           // $timeLooming = $loomingTime[$randomKey];
            $totalLoomingTime = (5.3 * $qty)*1.15; //factor de 1.33 para braiding
            $timetotalLoomingTime += round($totalLoomingTime,0);
            print("\t Item: $item, Cantidad: $qty, Tiempo de TIE por unidad: 5.3 seg, Tiempo total TIE: $totalLoomingTime seg <br>");
        }
        //print("Tiempo total de braiding para NP $np: $timetotalLoomingTime seg <br>");
         $insertar2 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','10801','Pend','Add Ties','1','$timetotalLoomingTime','150')");
    }

}
 //header("location:../registro.php");
//header("location:../registro.php");