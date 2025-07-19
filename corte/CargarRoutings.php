<?php
require "../app/conection.php";

// Buscar hasta 20 números de parte que no estén en routing_models
$query = "
    SELECT DISTINCT l.pn 
FROM listascorte l
LEFT JOIN routing_models r 
    ON l.pn COLLATE utf8mb4_unicode_ci = r.pn_routing COLLATE utf8mb4_unicode_ci
WHERE r.pn_routing IS NULL
LIMIT 20
";

$result = mysqli_query($con, $query);

$numerosDeParte = [];
while ($row = mysqli_fetch_array($result)) {
    $numerosDeParte[] = $row['pn'];
}

if (empty($numerosDeParte)) {
    header("Location: registro.php");
    exit;
} else {
    header("Location: duplicados.php?np=" . implode(',', $numerosDeParte));
    exit;
}
