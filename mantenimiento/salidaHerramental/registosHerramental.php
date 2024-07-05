<?php
$host = "localhost";
$user = "root";
$clave = "";
$bd = "mantenimiento";

// Connect to the database
$con = mysqli_connect($host, $user, $clave, $bd);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
session_start();

// Get the 'codigo' value from the POST request
$herramental = $_POST['codigo'];

date_default_timezone_set('America/Mexico_City');
$fecha = date('d-m-Y H:i');
// Select the 'count' column for the provided 'herramental' value
$selectconteo = "SELECT count FROM conteo WHERE herramental = '$herramental'";
$qry1 = mysqli_query($con, $selectconteo);

// Check if a row was found
if (mysqli_num_rows($qry1) > 0) {
    while ($row = mysqli_fetch_array($qry1)) {
        $count = $row['count'];
        

        // Increment the count and update the database
        $count += 1;
       
        $insertar = "UPDATE `conteo` SET `count`='$count' WHERE herramental='$herramental'";
        $insert = mysqli_query($con, $insertar);

        // Check if the count is even
        if ($count % 2 == 0) {
            $accion="Entrada";
           $entrada="INSERT INTO `herramental`(`herramental`, `fecha`, `Accion`) VALUES ('$herramental','$fecha','$accion')";
           $qry2=mysqli_query($con,$entrada);
           
        } else {
            $accion="Salida";
           $salida="INSERT INTO `herramental`(`herramental`, `fecha`, `Accion`) VALUES ('$herramental','$fecha','$accion')";  
            $qry=mysqli_query($con,$salida);
            
        }
     
    }
} else {
    echo "No matching records found for codigo: $herramental";
}
$golptotal="SELECT golpestotales FROM golpeteo WHERE herramental='$herramental'";
$golt=mysqli_query($con,$golptotal);
 while($row=mysqli_fetch_array($golt)){
     $golpes=$row['golpestotales'];
 }
 $_SESSION['accion']=$accion;
 $_SESSION['herramental']=$herramental;
$_SESSION['golpes']=$golpes;
 echo $herramental."<br>";
 echo $golpes;
header("location:mostrar.php");
?>

</html>