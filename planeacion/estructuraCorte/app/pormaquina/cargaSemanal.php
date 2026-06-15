<?php
require '../conection.php';

try {
    $qry="SELECT id,NumPart,wo,Qty,programado FROM registro WHERE `count` IN ('2','3') ORDER BY `NumPart`,`Qty`,`count` ASC";
    $datos=mysqli_query($con,$qry);
    echo json_encode(mysqli_fetch_all($datos));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}