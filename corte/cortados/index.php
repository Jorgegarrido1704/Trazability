<?php
require "../app/conection.php";

try {
    // Consulta optimizada con suma condicional y GROUP BY
    $query = "
        SELECT 
            np,
            wo,
            COUNT(*) as total_cortes,
            SUM(CASE WHEN cutStatus = 'Activo' THEN 1 ELSE 0 END) as activos,
            ROUND((SUM(CASE WHEN cutStatus = 'Activo' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as porcentaje_activos
        FROM `corte` 
        GROUP BY np, wo
        ORDER BY np DESC
    ";

    $cortes = mysqli_query($con, $query);

    if (!$cortes) {
        throw new Exception("Error en la consulta: " . mysqli_error($con));
    }

    // Ejemplo de cómo recorrer los datos si lo necesitas:
    // while ($row = mysqli_fetch_assoc($cortes)) {
    //     echo "NP: " . $row['np'] . " | WO: " . $row['wo'] . " | Progreso: " . $row['porcentaje_activos'] . "%<br>";
    // }
    foreach($cortes as $corte) {
        echo "NP: " . $corte['np'] . " | WO: " . $corte['wo'] . " | Progreso: " . $corte['porcentaje_activos'] . "%<br>";
    }

} catch(Exception $e) {
    echo "Ocurrió un error: " . $e->getMessage();
}
?>