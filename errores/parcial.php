<?php

require "../app/conection.php";

$selct = mysqli_query($con, "SELECT registroparcial.codeBar 
                             FROM registroparcial 
                             WHERE registroparcial.codeBar NOT IN (SELECT info FROM registro)");
while($row=mysqli_fetch_array($selct)){
    $info=$row['codeBar'];
    $delte=mysqli_query($con,"DELETE FROM registroparcial WHERE codeBar ='$info' ");
    print($info."<br>");
}