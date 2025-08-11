<?php
//session_start();
require "../app/conection.php";

try{
// Retrieve form data
$calibre = $_POST['aws'];
$cliente = $_POST['cliente'];
$num = $_POST['num_part'];
$wo = $_POST['wo'];
$presion = $_POST['presion'];
$personal = $_POST['persona'];
$apply = $_POST['apply'];
$apply = strtoupper($apply);
$cont = $_POST['specific'];
$cont = strtoupper($cont); // Convert to uppercase
$val = 'Sergio';

$fecha = date('d-m-Y'); 
$month=date("m-Y");

if($cont=='MT1-4' || $cont=='MT1-54'){
    
    if ($calibre == 14 && ($presion <=21)) {
        $insertar = "INSERT INTO `registro`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
        $qry = mysqli_query($con, $insertar);
        header('Location: ../PULL/error.php');
    } elseif ($calibre == 16 && ($presion <= 19.8)) {
        $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
        $qry = mysqli_query($con, $insertar);
        header('Location: ../PULL/error.php');
    } elseif ($calibre == 18 && ($presion <= 19.8)) {
        $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
        $qry = mysqli_query($con, $insertar);
        header('Location: ../PULL/error.php');
    } elseif ($calibre == 20 && ($presion <= 13.3)) {
        $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
        $qry = mysqli_query($con, $insertar);
        header('Location: ../PULL/error.php');
    } elseif ($calibre == 22 && ($presion <= 8.78)) {
        $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
        $qry = mysqli_query($con, $insertar);
        header('Location: ../PULL/error.php');  
}else{
    $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','minfir')";
    $qry = mysqli_query($con, $insertar);  
    header('Location: ../PULL/registro.php');
}
}
// Check the calibre value and corresponding pressure range
else if ($calibre == 6 && ($presion <= 225)) {
    $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../PULL/error.php');
}
else if ($calibre == 10 && ($presion <= 80 || $presion > 161.12)) {
    $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../PULL/error.php');
} elseif ($calibre == 12 && ($presion <= 70 || $presion > 148.95)) {
    $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../PULL/error.php');
} elseif ($calibre == 14 && ($presion <= 50 || $presion > 93.32)) {
    $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../PULL/error.php');
} elseif ($calibre == 16 && ($presion <= 30 || $presion > 83.13)) {
    $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../PULL/error.php');
} elseif ($calibre == 18 && ($presion <= 20 || $presion > 66.4)) {
    $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../PULL/error.php');
} elseif ($calibre == 20 && ($presion <= 13 || $presion > 46.39)) {
    $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../PULL/error.php');
} elseif ($calibre == 22 && ($presion <= 8 || $presion > 44.95)) {
    $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','malas')";
    $qry = mysqli_query($con, $insertar);
    header('Location: ../PULL/error.php');
} else {
    // Insert data into the database
    $insertar = "INSERT INTO `registro_pull`(`fecha`, `calibre`, `Cliente`, `Num_part`, `wo`, `presion`, `forma`, `cont`, `quien`,`val`,`tipo`) VALUES ('$fecha', '$calibre', '$cliente', '$num', '$wo', '$presion', '$apply', '$cont', '$personal','$val','OK')";
    $qry = mysqli_query($con, $insertar);

    if ($qry) {
        header('Location: ../PULL/registro.php');
    } else {
        echo '<script> alert("Insertion failed");</script>';
    }
}
}catch(Exception $e){
echo $e->getMessage();

}

?>  
