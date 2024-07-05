<?php
session_start();
require "conection.php";

$usuario = isset($_POST['user']) ? $_POST['user'] : "";
$key = isset($_POST['pass']) ? $_POST['pass'] : "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // To prevent SQL injection
    $usuario = mysqli_real_escape_string($con, $usuario);
    $key = mysqli_real_escape_string($con, $key);

    $sql = "SELECT * FROM users WHERE user = '$usuario'";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Database query error: " . mysqli_error($con)); // Handle query errors gracefully
    }

    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($key, $row['password'])) {
        $log = "login";
        $_SESSION['user'] = $usuario; // Store the username in the session

        date_default_timezone_set('America/Mexico_City');
        $fecha = date('d-m-Y H:i');
        $registro = "INSERT INTO `registros`(`id`, `fecha`, `userName`, `action`) VALUES ('', '$fecha', '$usuario', '$log')";

        mysqli_query($con, $registro); // Insert the login record into the 'registros' table

        mysqli_close($con);
        header("location: principal.php");
    } else {
        header("location: index.html");
    }
} elseif (isset($_SESSION['user'])) {
    $usuario = $_SESSION['user'];

    $log = "logout"; // Assuming this is a logout action
    date_default_timezone_set('America/Mexico_City');
    $fecha = date('d-m-Y H:i');
    $registro1 = "INSERT INTO `registros`(`id`, `fecha`, `userName`, `action`) VALUES ('', '$fecha', '$usuario', '$log')";

    mysqli_query($con, $registro1); // Insert the logout record into the 'registros' table

    session_destroy(); // Destroy the session

    mysqli_close($con);
    header("location: index.html");
} else {
    header("location: index.html"); // Redirect if not a POST request and no session user
}
?>
