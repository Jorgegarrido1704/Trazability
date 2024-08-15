<?php
require "conection.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the submitted IDs and quienInput values
    $ids = isset($_POST['id']) ? $_POST['id'] : [];
    $quienInputs = isset($_POST['quienInput']) ? $_POST['quienInput'] : "";
    // Loop through the array and process the data
    for ($i = 0; $i < count($ids); $i++) {
        $id = $ids[$i];
        $quien = isset($quienInputs) ? $quienInputs: ""; 
        $id = mysqli_real_escape_string($con, $id);
        $quien = mysqli_real_escape_string($con, $quien);
        echo $id. $quien;    }
date_default_timezone_set("America/Mexico_City");
$fechainicio=date("d-m-Y");
$horainicio=date("H:i");
$fecha=date("d-m-Y H:i");
$bucar =mysqli_query($con,"SELECT * FROM registro_paro WHERE id='$id'");
while($row= mysqli_fetch_array($bucar)){
    if($row['inimant']==''){
        $altmant=mysqli_query($con,"UPDATE registro_paro SET inimant='$fechainicio', atiende='$quien' WHERE id='$id'");
        $upReg=mysqli_query($con,"UPDATE registro_mant SET horaIniServ='$horainicio',tecMAnt='$quien' WHERE id_falla='$id'");
}else if($row['inimant']!='' and $row['finhora']==''){
    $iniT=strtotime($row['inimant']);
    $finT=strtotime($fecha);
    $dif=$finT-$iniT;
    $dif=round($dif/60);
    $altmant=mysqli_query($con,"UPDATE registro_paro SET finhora='$horainicio', Tiempo='$dif' WHERE id='$id'");
    $upReg=mysqli_query($con,"UPDATE registro_mant SET horaFinServ='$horainicio',ttServ='$dif' WHERE id_falla='$id'");
}}
header("location:Fallas.php");
    }

