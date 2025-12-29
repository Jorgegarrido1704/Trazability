<?php 
require_once "../../app/conection.php";


$coneccion=mysqli_query($con,"SELECT * FROM tiempoharneses WHERE (estatus = 'INICIADO' or estatus = 'PAUSADO') ORDER BY id DESC");

$datos = array();
while($row=mysqli_fetch_array($coneccion)){
    $datos[] = array(
        'employeeNumber' => $row['employeeNumber'],
        'partnumber' => $row['partnumber'],
        'initTime' => $row['initTime'],
        'estatus' => $row['estatus']
    );
}
echo json_encode($datos);

