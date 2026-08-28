<?php
$host = "127.0.0.1";
$user = "root";
$clave = "";
$bd = "trazabilidad";
$con = mysqli_connect($host, $user, $clave, $bd);
date_default_timezone_set('America/Mexico_City');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Conexión PDO (nueva, la necesita congelar.php y cualquier script que use $pdo)
try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$bd};charset=utf8mb4",
        $user,
        $clave,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die("PDO connection failed: " . $e->getMessage());
}