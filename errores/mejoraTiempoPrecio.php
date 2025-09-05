<?php

require '../app/conection.php';
date_default_timezone_set("America/Mexico_City");
$now=date("d-m-Y H:i");
$now=strtotime($now);
$dateIni = date("d-m-Y H:i");

if(date("N") != 7){
    

// add price if  it was not registered
$buscarPrecio = mysqli_query($con, "SELECT DISTINCT pn FROM po ORDER BY id DESC");
foreach($buscarPrecio as $rowPo){
    $prtn=$rowPo['pn'];
  $buscarPo=mysqli_query($con,"SELECT * FROM precios WHERE pn= '$prtn' "); 
  if(!(mysqli_num_rows($buscarPo)>0)){
$Po=mysqli_query($con,"SELECT client,pn,rev,description,price,send FROM po WHERE pn='$prtn' order by id desc limit 1");
 while($row=mysqli_fetch_array($Po)){
    $clie=$row['client'];
    $pn=$row['pn'];
    $rev=$row['rev'];
    $desc=$row['description'];
    $price=$row['price'];
    $send=$row['send'];
   print($prtn." | ".$clie." | ".$pn." | ".$rev." | ".$desc." | ".$price." | ".$send." <br>");
   mysqli_query($con,"INSERT INTO `precios` (`id`, `client`, `pn`, `desc`, `rev`, `price`, `send`) VALUES ('', '$clie', '$pn', '$desc', '$rev', '$price', '$send')");
 }
}


// add price if record is high than 
$buscar=mysqli_query($con,"SELECT price,NumPart,id FROM registro ");
while($row = mysqli_fetch_array($buscar)){
    $price=$row['price'];    
    $pn=$row['NumPart'];
    $buscarPrice=mysqli_query($con,"SELECT price FROM precios WHERE pn='$pn' and price>'$price' order by id desc limit 1");
    while($rowPrice = mysqli_fetch_array($buscarPrice)){
        $pre= $rowPrice['price'];
      //  echo $pn." ".$pre."<br>"; 
        $update=mysqli_query($con,"UPDATE registro SET price='$pre' WHERE NumPart='$pn'");    
       
    }

}}

//Tiempos
    $buscarTime=mysqli_query($con,"SELECT fecha,id FROM registro ");
    while($rowTime = mysqli_fetch_array($buscarTime)){
        $time= $rowTime['fecha'];
        $id= $rowTime['id'];
        $time=strtotime($time);
        $rest=$now-$time;
        $restD=round($rest/(60*60*24));
        $restH=round(($rest%(60*60*24))/(60*60));
        $restM=round((($rest%(60*60*24))%(60*60))/60);
        if($restD<1){            $restD=0;        }
        if($restM<1){            $restM=0;        }
        $registroTiempo=$restD." dias, ".$restH." horas, ".$restM." min";
        $updateDias=mysqli_query($con,"UPDATE registro SET tiempototal='$registroTiempo' WHERE id='$id'");
    } }
 header("location:../app/depuracion.php");
 // header("location:../../mant/trazabiliy/ing/piso.php");