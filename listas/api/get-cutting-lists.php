<?php
require '../../app/conection.php';

try {
    $color=isset($_GET['color']) ? $_GET['color'] : '';
    $awg=isset($_GET['awg']) ? $_GET['awg'] : '';
    $qry = "SELECT  pn,color,aws,cons FROM listascorte WHERE 1=1";
    if (!empty($color) and empty($awg)) {
        $qry .= " AND color = '$color'";
    }
    if (!empty($awg) and empty($color)) {
         $qry .= " AND aws = '$awg'";
    }
    if (!empty($color) and !empty($awg)) {
        $qry .= " AND color = '$color'";
        $qry .= " AND aws = '$awg'";
    }
    $stmt = mysqli_query($con, $qry);
    $gauges = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
    echo json_encode($gauges);
} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    $gauges = [];
}