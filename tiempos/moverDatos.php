<?php 
require '../app/conection.php';

$data = mysqli_query($con, "SELECT * FROM `timeprocess` ");

while($row = mysqli_fetch_array($data)){
    $id=$row['id_tp'];
    $partnum = $row['partnum'];
    $laps=  $row['laps'];
    echo $id." - " . $partnum. ' = Seg/ ';    $divlaps=explode('-', $laps);
    $tiempo='';
    $i=$registroAnterios=0;
    foreach($divlaps as $lp){
        if(((int) $lp-1)>=$registroAnterios){
        if(($i+1)==count($divlaps)){
            $tiempo.= $lp;
        }else{
        $tiempo.= $lp.' - ';
    }
    $registroAnterios=$lp;
       
    }  $i++;
}
//mysqli_query($con, "UPDATE `timeprocess` SET `laps`='$tiempo' WHERE `id_tp`='$id'");

    echo $tiempo. '<br> <br>';   
}



  