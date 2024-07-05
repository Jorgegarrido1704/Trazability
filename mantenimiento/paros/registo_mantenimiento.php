<?php

require "conection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the submitted IDs and quienInput values
    $ids = isset($_POST['id']) ? $_POST['id'] : [];
    $quienInputs = isset($_POST['quienInput']) ? $_POST['quienInput'] : "";

    // Loop through the array and process the data
    for ($i = 0; $i < count($ids); $i++) {
        $id = $ids[$i];
        $quien = isset($quienInputs) ? $quienInputs: ""; // Get quienInput value for the current ID
        
        // Sanitize input
        $id = mysqli_real_escape_string($con, $id);
        $quien = mysqli_real_escape_string($con, $quien);
        echo $id. $quien;   
    }
date_default_timezone_set("America/Mexico_City");
$fechainicio=date("d-m-Y");
$horainicio=date("H:i");
$fecha=date("d-m-Y H:i");

$altaquien="UPDATE registro_paro_corte SET atiende='$quien' WHERE id='$id'";
$qry=mysqli_query($con,$altaquien);


$reporte="SELECT idincidencia FROM tiempoman WHERE idincidencia='$id'";
$sqyman=mysqli_query($con,$reporte);
if($row=mysqli_fetch_array($sqyman)>0){
    
    $fintiempo1="UPDATE registro_paro SET trabajo='$quien',finhora='$fecha' WHERE id LIKE '$id'";
    $qryfin=mysqli_query($con,$fintiempo1);

  
    $fintiempo="UPDATE tiempoman SET finfecha='$fechainicio',finhora='$horainicio' WHERE idincidencia='$id'";
    $qryfin=mysqli_query($con,$fintiempo);
    
   
    $quitarmante="DELETE FROM registro_paro_corte WHERE id=$id";
    $quitarqry=mysqli_query($con,$quitarmante);

  

}else{
    $altaquien1="UPDATE registro_paro SET atiende='$quien', inimant='$fecha' WHERE id='$id'";
    $qry1=mysqli_query($con,$altaquien1);
    $tiempoinicio="INSERT INTO `tiempoman`(`id`, `idincidencia`, `iniciofecha`, `iniciohora`, `finfecha`, `finhora`) VALUES ('','$id','$fechainicio','$horainicio','','')";
    $registroinicio=mysqli_query($con,$tiempoinicio);
}

header("location:Fallas.php");
    }
?>
