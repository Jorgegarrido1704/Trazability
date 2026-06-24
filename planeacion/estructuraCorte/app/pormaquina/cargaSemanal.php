<?php
require '../conection.php';

try {
$qry="SELECT DISTINCT r.id,r.NumPart,r.wo,r.Qty,r.programado,c.urgencia FROM corte c JOIN registro r on c.wo=r.wo WHERE `count` IN ('2','3','17','1') AND `cutStatus` = 'Activo' ORDER BY 
`programado` DESC,  `urgencia` DESC, `Qty` DESC,`NumPart` DESC";
    $datos=mysqli_query($con,$qry);
    echo json_encode(mysqli_fetch_all($datos));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}