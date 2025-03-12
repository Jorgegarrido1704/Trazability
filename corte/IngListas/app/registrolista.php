<?php
require "../../../app/conection.php";



if ($con->connect_error) {
    die("Conexión fallida: " . $con->connect_error);
}

// Obtener los datos del formulario (en formato JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Comprobamos si los datos están presentes
if ($data) {
    // Insertar datos en la base de datos
    $stmt = $con->prepare("INSERT INTO listasing (creadorLista, client, pn, rev, corte, cons, tipo, calibre, color, longitud, term1, term2, estamp, fromPos, toPos, comment, categoria) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($data as $row) {
        $stmt->bind_param("sssssssssssssssss", 
            $row['creador'],
            $row['cliente'],
            $row['pn'],
            $row['rev'],
            $row['corte'],
            $row['cons'],
            $row['tipo'],
            $row['calibre'],
            $row['color'],
            $row['longitud'],
            $row['term1'],
            $row['term2'],
            $row['estamp'],
            $row['fromPos'],
            $row['toPos'],
            $row['comment'],
            $row['categoria']
        );
        $stmt->execute(); 
    }

    echo json_encode(['status' => 'success', 'message' => 'Datos guardados correctamente']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se recibieron datos']);
}

$stmt->close();
$con->close();
?>
