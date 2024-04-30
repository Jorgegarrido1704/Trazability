<?php
session_start();
require "conection.php";

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $pn = $_POST['pn'];
       $selectInfo = $con->prepare("SELECT client, rev, `desc`, price, `send` FROM precios WHERE pn = ?");
        $selectInfo->bind_param("s", $pn);
        $selectInfo->execute();

        $selectInfo->bind_result($client,$rev, $description, $price,$send);
        $selectInfo->fetch();
        $selectInfo->close();

        echo json_encode(array("client" => $client, "Rev" => $rev, "Description" => $description, "Uprice" => $price, "send" => $send));
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(array("error" => "Invalid request method."));
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("error" => "Error in database query."));
}
?>
