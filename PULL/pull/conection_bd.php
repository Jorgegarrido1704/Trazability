<?php
session_start();
$host = "localhost";
$user = "pcadmin";
$clave = "SupAdmin1212";

$bd = "engineery";


$con = mysqli_connect($host, $user, $clave, $bd);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


$calibre = $_POST['aws'];
$cliente = $_POST['cliente'];
$num = $_POST['num_part'];
$wo = $_POST['wo'];
$presion = $_POST['presion'];
$personal = $_POST['persona'];
$apply = $_POST['apply'];
$apply = strtoupper($apply);
$cont = $_POST['specific'];
$cont = strtoupper($cont); 
$val = $_POST['valid'];

date_default_timezone_set('America/Mexico_City');
$fecha = date('d-m-Y H:i'); 
$month=date("m-Y");

if($cont=='MT1-4' || $cont=='MT1-54'){
    $insertar = "INSERT INTO `minifit`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
    $qry = mysqli_query($con, $insertar);  
    header('Location: ../pull/registro.php');
    if ($calibre == 14 && ($presion <=21)) {
        $insertar = "INSERT INTO `malas`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
        $qry = mysqli_query($con, $insertar);
        header('Location: ../pull/error.php');
    } elseif ($calibre == 16 && ($presion <= 19.8)) {
        $insertar = "INSERT INTO `malas`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
        $qry = mysqli_query($con, $insertar);
        header('Location: ../pull/error.php');
    } elseif ($calibre == 18 && ($presion <= 19.8)) {
        $insertar = "INSERT INTO `malas`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
        $qry = mysqli_query($con, $insertar);
        header('Location: ../pull/error.php');
    } elseif ($calibre == 20 && ($presion <= 13.3)) {
        $insertar = "INSERT INTO `malas`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
        $qry = mysqli_query($con, $insertar);
        header('Location: ../pull/error.php');
    } elseif ($calibre == 22 && ($presion <= 8.78)) {
        $insertar = "INSERT INTO `malas`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
        $qry = mysqli_query($con, $insertar);
        header('Location: ../pull/error.php');  
}
}

else if ($calibre == 10 && ($presion <= 80 || $presion > 161.12)) {
    $insertar = "INSERT INTO `malas`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../pull/error.php');
} elseif ($calibre == 12 && ($presion <= 70 || $presion > 148.95)) {
    $insertar = "INSERT INTO `malas`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../pull/error.php');
} elseif ($calibre == 14 && ($presion <= 50 || $presion > 93.32)) {
    $insertar = "INSERT INTO `malas`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../pull/error.php');
} elseif ($calibre == 16 && ($presion <= 30 || $presion > 83.13)) {
    $insertar = "INSERT INTO `malas`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../pull/error.php');
} elseif ($calibre == 18 && ($presion <= 20 || $presion > 66.4)) {
    $insertar = "INSERT INTO `malas`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../pull/error.php');
} elseif ($calibre == 20 && ($presion <= 13 || $presion > 46.39)) {
    $insertar = "INSERT INTO `malas`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../pull/error.php');
} elseif ($calibre == 22 && ($presion <= 8 || $presion > 44.95)) {
    $insertar = "INSERT INTO `malas`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../pull/error.php');
} else {
    
    $insertar = "INSERT INTO `registro`(`id`,`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`) VALUES ('','$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val')";
    $qry = mysqli_query($con, $insertar);

    if ($qry) {
        header('Location: ../pull/registro.php');
    } else {
        echo '<script> alert("Insertion failed");</script>';
    }
}


mysqli_close($con);
?>
