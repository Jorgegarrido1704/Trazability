<?php

require "../app/conection.php";

$select = mysqli_query($con, "SELECT wo,info,Qty FROM registro ");

while ($row = mysqli_fetch_array($select)) {
    $wo = $row['wo'];
    $info = $row['info'];
    $Qty = $row['Qty'];
   $buscar = mysqli_query($con, "SELECT * FROM `registroparcial` WHERE `codeBar`='$info' AND `wo`='$wo' ");
    while ($row = mysqli_fetch_array($buscar)) {
        $cortPar = $row['cortPar'];
        $libePar = $row['libePar'];
        $ensaPar = $row['ensaPar'];
        $loomPar = $row['loomPar'];
        $testPar = $row['testPar'];
        $embPar = $row['embPar'];
        $preCalidad = $row['preCalidad'];
        $eng = $row['eng'];
        $fallasCalidad = $row['fallasCalidad'];
        $specialWire = $row['specialWire'];
        $pn = $row['pn'];

        $sum= $cortPar+$libePar+$ensaPar+$loomPar+$testPar+$embPar+$preCalidad+$eng+$fallasCalidad+$specialWire;
        if ($sum > $Qty) {
            echo "Total en orden " .$pn.": " . $Qty . " Total Parcial: " . $sum . "<br>";
        }


        
    }
}