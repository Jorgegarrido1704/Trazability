<?php
require "../../../app/conection.php";
$registro=$_GET['registro']; 

// Prepare the SQL query to get the "corte" values
$sql = "SELECT * FROM listasing WHERE pn ='$registro' Order by categoria ASC "; // Make sure the table and column names match your database
$result = $con->query($sql);
$i=0;
// Initialize an array to store the results
$cortes = array();

// Check if the query returned any results
if ($result->num_rows > 0) {
    // Fetch each row and add it to the cortes array
    while($row = $result->fetch_assoc()) {
        $corte[$i] = array(
            "creadorLista" => $row['creadorLista'],
            "client" => $row['client'],
            "pn" => $row['pn'],
            "rev" => $row['rev'],
            "corte" => $row['corte'],
            "cons" => $row['cons'],
            "tipo" => $row['tipo'],
            "calibre" => $row['calibre'],
            "color" => $row['color'],
            "longitud" => $row['longitud'],
            "term1" => $row['term1'],
            "term2" => $row['term2'],
            "estamp" => $row['estamp'],
            "fromPos" => $row['fromPos'],
            "toPos" => $row['toPos'],
            "comment" => $row['comment'],
            "categoria" => $row['categoria'],            
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
