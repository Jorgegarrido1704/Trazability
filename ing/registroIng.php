<?php 
session_start();
require '../app/conection.php';
if(!$_SESSION['usuario']){
    header('location:../main');
}else{
    date_default_timezone_set('America/Mexico_City');
    $date=date('d-m-Y H:i');
$type=isset($_POST['work'])? $_POST['work']:'';
$info=isset($_POST['desc'])? $_POST['desc']:'';
$who=isset($_POST['quien'])? $_POST['quien']:'';
$place=isset($_POST['area'])? $_POST['area']:'';
$atiende=isset($_POST['atiende'])? $_POST['atiende']:'';
$id=isset($_POST['id'])? $_POST['id']:'';

echo $atiende. "". $id;
if($type!= '' or $info!='' or $who!= '' or $place!='' ){

$inserData="INSERT INTO `reqing`(`id`, `type`, `info`, `who`, `donde`, `timeIni`) VALUES ('','$type','$info','$who','$place','$date')";
$qryins=mysqli_query($con,$inserData);
if($qryins){
    echo "Insertation succesfully";
    header("location:emailing.php");
}
}else if($atiende != "" ){
$inseratid="UPDATE reqing set atiende='$atiende',count='1', respTime='$date' WHERE id='$id'";
$qryid=mysqli_query($con,$inseratid);
echo $atiende. "". $id;
if($qryid){
    echo "Insertation succesfully";
    header("location:tableengi.php");
}
}
    }?>