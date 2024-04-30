<?php
// Connection to the database
$host = "localhost";
$user = "root";
$clave = "";
$bd = "prueba_pull";

// Connect to the database
$con = mysqli_connect($host, $user, $clave, $bd);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

    $desiredCalibre = 16;
    
    $cliente="Plastic_omniun";
    // Sanitize the input to prevent SQL injection
    $desiredCalibre = mysqli_real_escape_string($con, $desiredCalibre);
    
    // Query to retrieve data with filtering based on calibre
    $sql = "SELECT calibre, presion FROM registro WHERE calibre = '$desiredCalibre' and Cliente like'$cliente'";

$result = $con->query($sql);

$data = [];
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = floatval($row['presion']);
        }
    }
    $result->free(); // Free the result set
}

echo json_encode($data);

$con->close();

?>
