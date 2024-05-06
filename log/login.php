<?php
session_start();
require "../app/conection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['user'];
    $password = $_POST['pass'];
$i=0;
$users=$claves=[];
    $sql = "SELECT * FROM login";
   $stml=mysqli_prepare($con,$sql);
   mysqli_stmt_execute($stml);
   $result=mysqli_stmt_get_result($stml);
   while($row=mysqli_fetch_array($result)){
    $user=$row['user'];
    $clave=$row['clave'];
    
    $i++;

   }
     
    if ($user !="" and $clave!="") {
        $log = "login";
        $_SESSION['usuario'] = $username; 

        date_default_timezone_set('America/Mexico_City');
        $fecha = date('d-m-Y H:i');
        $registro = "INSERT INTO `registros`(`id`, `fecha`, `userName`, `action`) VALUES ('', '$fecha', '$username', '$log')";

        mysqli_query($con, $registro); 

       header("location: ../main/principal.php");
    } else {
        header("location: ../main/index.html");
    }
} 

mysqli_close($con);
?>