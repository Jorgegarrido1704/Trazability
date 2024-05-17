<?php 
require "../app/conection.php";

$buscar=mysqli_query($con,"SELECT DISTINCT pn FROM listascorte ");
while($row=mysqli_fetch_array($buscar)){
$nup=$row['pn'];
echo $nup."<br>";
}