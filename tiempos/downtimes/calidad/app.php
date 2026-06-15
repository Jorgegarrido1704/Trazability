<?php

$localhost = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'toi';

// Create connection
$conn = new mysqli($localhost, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set("America/Mexico_City");