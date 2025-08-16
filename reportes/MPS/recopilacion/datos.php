<?php


try{
    require "../../app/conectionTraza.php";
    $registrosMPS = mysqli_query($con, "SELECT * FROM `datos_mps`");


$currentWeek = date("W");
$pnRegistros = [];
$allWeeks = [];

// Get MPS records
$registrosMPS = mysqli_query($con, "SELECT DISTINCT pn FROM `datos_mps`");

while ($row = mysqli_fetch_assoc($registrosMPS)) {
    $pn = $row['pn'];
    $pnRegistros[]=$pn;
}
header("location: ../../../corte/routing/cortes.php?np=" . implode(',', $pnRegistros));
}
catch(Exception $e){
    echo 'Error: ' . $e->getMessage();
}