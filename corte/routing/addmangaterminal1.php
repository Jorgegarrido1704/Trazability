<?php
require "../../app/conection.php";

if (isset($_GET['np'])) {
    if (strpos($_GET['np'], ',') !== false) {
        $datos[0] = $_GET['np'];
    } else {
        $datos =  explode(',', $_GET['np']);
    }
} else {
    echo "No se han recibido números de parte.";
    header("location:../registro.php");
}

foreach ($datos as $np) {
    $cantidad = 0;

    $buscarMangas = mysqli_query($con, "SELECT terminal1 FROM listascorte WHERE pn='$np' AND terminal1 IS NOT NULL and (terminal1 LIKE '%MANGA%' or terminal1 LIKE '%Manga%')");
    if (mysqli_num_rows($buscarMangas) > 0) {
        $cantidad = $cantidad + intval(mysqli_num_rows($buscarMangas));
    }
    $buscarMangas2 = mysqli_query($con, "SELECT terminal2 FROM listascorte WHERE pn='$np' AND terminal2 IS NOT NULL and (terminal2 LIKE '%MANGA%' or terminal2 LIKE '%Manga%')");
    if (mysqli_num_rows($buscarMangas2) > 0) {
        $cantidad = $cantidad + intval(mysqli_num_rows($buscarMangas2));
    }
    echo 'Mangas a terminales: '  .$cantidad;
}
