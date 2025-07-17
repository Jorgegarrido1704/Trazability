<?php
require "../../app/conection.php";
require "timesReg.php";

if (isset($_GET['np'])) {
    if (strpos($_GET['np'], ',') !== false) {
        $datos[0] = $_GET['np'];
    } else {
        $datos =  explode(',', $_GET['np']);
    }
} else {
    echo "No se han recibido números de parte.";
    header("location:../registro.php");
}

foreach ($datos as $np) {
    $cantidad = 0;

    $buscarMangas = mysqli_query($con, "SELECT terminal1 FROM listascorte WHERE pn='$np' AND terminal1 IS NOT NULL and (terminal1 LIKE '%MANGA%' or terminal1 LIKE '%Manga%')");
    if (mysqli_num_rows($buscarMangas) > 0) { $cantidad = $cantidad + intval(mysqli_num_rows($buscarMangas));  }
    $buscarMangas2 = mysqli_query($con, "SELECT terminal2 FROM listascorte WHERE pn='$np' AND terminal2 IS NOT NULL and (terminal2 LIKE '%MANGA%' or terminal2 LIKE '%Manga%')");
    if (mysqli_num_rows($buscarMangas2) > 0) { $cantidad = $cantidad + intval(mysqli_num_rows($buscarMangas2));}
    $random = rand(0, count($setHeadShrink) - 1);
    $random1 = rand(0, count($burnHeatGun) - 1);
    $leyenda1 = "Set HeadShrink in Terminals ";
    $leyenda2 = "Burn Heatshrirnk w/headgun in Terminals ";
    $timeHeadShrink = $setHeadShrink[$random];
    $gunHeatGun = $burnHeatGun[$random1];

    $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','10301','FB110','$leyenda1','$cantidad','$timeHeadShrink','600')");
   
            
    echo 'Mangas a terminales: '  .$cantidad;

   
}
