<?php
require "../../../app/conection.php";


// Prepare the SQL query to get the "corte" values
$sql = "SELECT * FROM registromovimientoslistas WHERE Tipodemovimiento = 'Creacion de lista'"; // Make sure the table and column names match your database
$result = $con->query($sql);
$i=0;
// Initialize an array to store the results
$cortes = array();

// Check if the query returned any results
if ($result->num_rows > 0) {
    // Fetch each row and add it to the cortes array
    while($row = $result->fetch_assoc()) {
        $corte[$i] = array(
            'id' => $row['id'],
            'creador' => $row['creadorLista'],
            'pn' => $row['pn'],
            'cliente' => $row['cliente'],
            'rev' => $row['rev']
        );
        $i++;
    }
} else {
    // No results found
    $corte = array();
}

// Close the connection
$con->close();

// Set the header for JSON response
header('Content-Type: application/json');

// Return the data as a JSON response
echo json_encode($corte);
?>
