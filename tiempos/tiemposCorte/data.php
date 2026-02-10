<?php 
require_once "../../app/conection.php";


$coneccion=mysqli_query($con,"SELECT * FROM tiemposcorte WHERE (estatus = 'INICIADO' or estatus LIKE 'PAUSA%') ORDER BY id DESC");

$datos = array();
while($row=mysqli_fetch_array($coneccion)){
    $datos[] = array(
        'employeeNumber' => $row['empNum'],
        'partnumber' => $row['pn'],
        'initTime' => $row['initTime'],
        'estatus' => $row['estatus']
    );
}
echo json_encode($datos);

