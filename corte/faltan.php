<?php 
require "../app/conection.php";

$buscar=mysqli_query($con,"SELECT DISTINCT NumPart,registro.rev FROM registro JOIN listascorte ON registro.NumPart=listascorte.pn and listascorte.rev NOT IN (SELECT rev FROM registro)");
while($row=mysqli_fetch_array($buscar)){
    
    $pn=$row['NumPart']; 
    $rev=$row['rev'];
    echo $pn.' '. $rev.'<br>';}