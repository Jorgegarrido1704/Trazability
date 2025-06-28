<?php 
require "../app/conection.php";

if (isset($_GET['np'])) {
    $datos =  explode(',', $_GET['np']);
    // Aquí puedes realizar las operaciones necesarias con los números de parte
} else {
    echo "No se han recibido números de parte.";
}

foreach ($datos as $np) {
     $buscarExistencia = mysqli_query($con, "SELECT `pn_routing` FROM routing_models WHERE pn_routing='$np' ");
            if (mysqli_num_rows($buscarExistencia) == 0) {
    $buscar = mysqli_query($con, "SELECT cons, tipo, aws, color FROM listascorte WHERE pn='$np' AND terminal1 != '' AND terminal2 != ''
    AND terminal1 != 'Empalme' AND terminal2 != 'Empalme' AND terminal1 not like 'Blund' AND terminal2 not like 'Blund' ");
    if (mysqli_num_rows($buscar) > 0) {
        while ($row = mysqli_fetch_array($buscar)) {
            $cons = $row['cons'];
            $tipo = $row['tipo'];
            $aws = $row['aws'];
            $color = $row['color'];
            $dataLabel = 'Cutting cons' . $cons . ' // Tipo:' . $tipo . '// AWG: ' . $aws . '// Color: ' . $color;
            $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','10001','FB036','$dataLabel','1','0','60')");

        }
        }
    } else {
        echo "No se encontraron registros para el número de parte: " . htmlspecialchars($np);
    }
}


header("location:registro.php");