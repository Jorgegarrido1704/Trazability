<?php
require '../../app/conection.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$customer     = $input['customer'] ?? '';
$size         = $input['size'] ?? '';
$responsable  = $input['responsable'] ?? '';
$filter       = $input['filter'] ?? '';
$dateIni      = $input['dateIni'] ?? '';
$dateEnd      = $input['dateEnd'] ?? '';
$pns          = $input['pns'] ?? '';

$busquedaTable = '';
$datos = [];

if ($customer != '') {
    $busquedaTable .= "customer='$customer' ";
}
if ($size != '') {
    $busquedaTable .= ($busquedaTable != '' ? "AND " : "") . "size='$size' ";
}
if ($responsable != '') {
    $busquedaTable .= ($busquedaTable != '' ? "AND " : "") . "resposible='$responsable' ";
}

$i = 0;
$busqueda = null;
$busqueda1 = null;

if ($filter != '' && $dateIni != '' && $dateEnd != '' && $busquedaTable != '') {
    $di=strtotime(date('d-m-Y', strtotime($dateIni)));
    $df=strtotime(date('d-m-Y', strtotime($dateEnd)));
   
    $busqueda1 = mysqli_query($con, "SELECT * FROM workschedule WHERE $busquedaTable");
} else if ($filter != '' && $dateIni != '' && $dateEnd != '') {
    $di=strtotime(date('d-m-Y', strtotime($dateIni)));
    $df=strtotime(date('d-m-Y', strtotime($dateEnd)));
    $busqueda1 = mysqli_query($con, "SELECT * FROM workschedule");
} else if ($busquedaTable != '') {
    $busqueda = mysqli_query($con, "SELECT * FROM workschedule WHERE $busquedaTable");
} else if ($pns != '') {
    $busqueda = mysqli_query($con, "SELECT * FROM workschedule WHERE pn LIKE '%$pns%'");
}

if ($busqueda) {
    while ($row = mysqli_fetch_array($busqueda)) {
        $datos[$i++] = $row;
    }
}

if ($busqueda1) {
    while ($row = mysqli_fetch_array($busqueda1)) {
        if (strtotime($row[$filter])>=$di && strtotime($row[$filter])<=$df) {
            $datos[$i++] = $row;
        }
    }
}

echo json_encode($datos ?: ['error' => 'No se encontraron resultados']);
exit;
?>
