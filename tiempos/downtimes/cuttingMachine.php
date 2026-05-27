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

class crimpers {
    public 

    function __construct($id, $start_time, $end_time, $duration, $reason, $date) {
        $this->id = $id;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->duration = $duration;
        $this->reason = $reason;
        $this->date = $date;
    }
}