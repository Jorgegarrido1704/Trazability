<?php 
$host="localhost";
$user="pcadmin";
$pass="SupAdmin1212";
$base="inv_mant";
$con=mysqli_connect($host,$user,$pass,$base);
if(!$con){
    die("Error:".mysqli_connect_error());
}

?>