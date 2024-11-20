<?php

require "../app/conection.php";

$buscar=mysqli_query($con,"SELECT DISTINCT pn FROM listascorte ");

while($row=mysqli_fetch_array($buscar)){
    $pn=$row['pn'];
    $buscarRev=mysqli_query($con,"SELECT rev  FROM precios WHERE pn='$pn'  order by id desc limit 1");
    $rowRev=mysqli_fetch_array($buscarRev);
    $rev=$rowRev['rev'];
    $update=mysqli_query($con,"UPDATE listascorte SET rev='$rev' WHERE pn='$pn'");
    if($update){
    print($pn." | ".$rev."<br>");}
    else{
        print("Error <br>");
    }

    
}