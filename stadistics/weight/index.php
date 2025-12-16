<?php

require "../../app/conection.php";

$weight = isset($_POST['weight']) ? $_POST['weight'] : "";

$ftWeight = [
    '22'=>0.002392207,
'20'=>0.003225447,
'18' =>0.005160716,
'16' =>0.006450894,
'14'=> 0.012901789,
'12' => 0.019352683,
'10' => 0.032254472,
'8' =>0.051607156,
'6' => 0.083861628,
'4' => 0.122566995,
'2' => 0.191853634,
'1/0' => 0.322544724,
'2/0' => 0.383814783,
'3/0' => 0.503888776,
'4/0' => 0.595787144
];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight</title>
</head>

<body>
    <form action="index.php" method="post"><textarea name="weight" id="" cols="30" rows="10"></textarea> <input
            type="submit" value="Buscar"></form>
    <hr>
    <?php
if($weight != "") {
    foreach(explode(",", $weight) as $weig){
        
    
    $registo = mysqli_query($con, "SELECT * FROM `datos` WHERE `part_num` = '$weig'   ORDER BY `item` ASC");
    if (mysqli_num_rows($registo) > 0) {
        while ($row = mysqli_fetch_array($registo)) {
            $part_num = $row['part_num'];
            $item = $row['item'];
            $qty = $row['qty'];

          echo $part_num.";".$item.";".$qty."<br>";  
            
    }


}else{
    echo "<br><br>no se encontro el numero de parte ".$weig." en la base de datos <br><br>";
}
    }
}
?>

</body>

</html>