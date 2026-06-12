<?php 

require '../app/conection.php';

$qry = "SELECT DISTINCT pn FROM listascorte ORDER BY pn ASC ";
$result = $con->query($qry);
$bulk = [];

while ($row = $result->fetch_array()) {
    $chunk = $row['pn'];
    $sqlInsert = "INSERT INTO `maintain_routings` (`pn`) VALUES ('$chunk')"; ;
    $con->query($sqlInsert);
}

    