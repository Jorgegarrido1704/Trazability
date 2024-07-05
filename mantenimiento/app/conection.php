<?php 

$host="localhost";
$user="root";
$pass="";
$db="mantenimiento";
$con = mysqli_connect($host,$user,$pass,$db);
if(!$con){
    echo "error";   
}   
