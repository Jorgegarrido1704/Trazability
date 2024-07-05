<?php 

$host="localhost";
$user="root";
$pass="";
$db="trazabilidad";
$con = mysqli_connect($host,$user,$pass,$db);
if(!$con){
    echo "error";   
}   
