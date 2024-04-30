<?php
session_start();
$host = "localhost";
$user = "pcadmin";
$clave = "SupAdmin1212";

$db_name = "engineery";

$con = mysqli_connect($host, $user, $password, $db_name);
if (mysqli_connect_errno()) {
    die("Failed to connect with MySQL: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['user'];
    $password = $_POST['pass'];

    
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);

    $sql = "SELECT * FROM login WHERE user = '$username' AND clave = '$password'";
    $result = mysqli_query($con, $sql);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        $log = "login";
        $_SESSION['usuario'] = $username; 

        date_default_timezone_set('America/Mexico_City');
        $fecha = date('d-m-Y H:i');
        $registro = "INSERT INTO `registros`(`id`, `fecha`, `userName`, `action`) VALUES ('', '$fecha', '$username', '$log')";

        mysqli_query($con, $registro); 

        header("location: registro.php");
    } else {
        header("location: index.html");
    }
} elseif (isset($_SESSION['usuario'])) {
    $username = $_SESSION['usuario'];

    $log = "logout"; 
    date_default_timezone_set('America/Mexico_City');
    $fecha = date('d-m-Y H:i');
    $registro = "INSERT INTO `registros`(`id`, `fecha`, `userName`, `action`) VALUES ('', '$fecha', '$username', '$log')";

    mysqli_query($con, $registro); 

    session_destroy(); 

    header("location: index.html");
} else {
    header("location: index.html"); 
}

mysqli_close($con);
?>