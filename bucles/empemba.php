<?php

require "../app/conection.php";


$datos=mysqli_query($con, "SELECT * FROM registro JOIN  po ON registro.po = po.po");

while($row=mysqli_fetch_array($datos)){
   $pos=$row['po'];
   $wo = $row['wo'];
   $poQty=$row['qty'];
   $regQty=$row['Qty'];
   if($poQty-$regQty !== 0){
    echo $pos.' '.$poQty.'-'.$regQty.'='.$poQty-$regQty."<br>";
   }
}
