<?php
// Connection to the database
$host = "localhost";
$user = "pcadmin";
$clave = "SupAdmin1212";

$bd = "trazabilidad";

// Connect to the database
$con = mysqli_connect($host, $user, $clave, $bd);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
date_default_timezone_set("America/Mexico_City");
$month=date("m-Y");

$calibreselect=22;
$sql = "SELECT calibre, presion FROM registro_pull WHERE calibre =$calibreselect and fecha LIKE '%%%$month'";
$result = $con->query($sql);
$data = []; // Initialize an array to store the retrieved data

// Fetch data and store it in the array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
                'presion' => floatval($row['presion'])
        ];
    }
}

// Close the database connection
$con->close();

// Output the JSON-encoded data
header('Content-Type: application/json'); // Set the response content type
echo json_encode($data);
?>
