<?php
require "../app/conection.php";
$numerosDeParte = array();

$selectListas = mysqli_query($con,"SELECT DISTINCT pn FROM listascorte");
while ($row = mysqli_fetch_array($selectListas)) {
    $selectRouting = mysqli_query($con,"SELECT DISTINCT pn_routing FROM routing_models WHERE pn_routing='$row[pn]'");
    if (mysqli_num_rows($selectRouting) == 0) {
        $numerosDeParte[] = $row['pn'];
    }
    if(count($numerosDeParte)>=20){
       header("Location: duplicados.php?np=" . implode(',', $numerosDeParte));
       exit;
    }
}

if (empty($numerosDeParte)) {
    header("Location: registro.php");
    exit;
} else {
    header("Location: duplicados.php?np=" . implode(',', $numerosDeParte));
    exit;
}
