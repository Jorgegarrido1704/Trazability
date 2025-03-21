<?php
require "../../../app/conection.php";

// Arreglo para almacenar los resultados
$lista = [];

$busqueda = mysqli_query($con, "
    SELECT DISTINCT item
    FROM datos
    WHERE item NOT LIKE 'WG%' 
    AND item NOT LIKE 'WTXL%' 
    AND item NOT LIKE 'WSGX%' 
    AND item NOT LIKE 'LTP%' 
    AND item NOT LIKE 'LW%' 
    AND item NOT LIKE 'TAPE-%'
");

while ($row = mysqli_fetch_array($busqueda)) {
    $item = $row['item'];

    // Comprobar que el tercer carácter sea 'C' y que el siguiente sea un número
    if ((isset($item[1]) && strtoupper($item[1]) == 'C' && isset($item[2]) && is_numeric($item[2])) || 
        (isset($item[2]) && strtoupper($item[2]) == 'C' && isset($item[3]) && is_numeric($item[3]))) {

        echo "--" . $item . "--<br>";

        // Realizar la consulta para obtener los "part_num" asociados al item
        $parno = mysqli_query($con, "SELECT part_num FROM datos WHERE item = '$item'");
            $numRow=mysqli_num_rows($parno);
        // Consultar todos los items relacionados con los "part_num" encontrados
        while ($row = mysqli_fetch_array($parno)) {
            $part_num = $row['part_num'];

            // Realizar una sola consulta para obtener todos los items que coincidan con el part_num
            $term = mysqli_query($con, "
                SELECT item 
                FROM datos 
                WHERE part_num = '$part_num' 
                AND (item LIKE '%T1-%' 
                OR item LIKE '%T2-%' 
                OR item LIKE '%TA2-%' 
                OR item LIKE '%TA1-%'
                )
            ");
            
            // Procesar los resultados obtenidos para asociarlos con el "item"
            while ($row = mysqli_fetch_array($term)) {
                $ter = $row['item'];

                // Asegurarse de no duplicar el item
                if (!array_key_exists($ter, $lista)) {
                    $lista[$ter] = 1;  // Primer encuentro del item
                } else {
                    $lista[$ter] += 1; // Incrementar contador si el item ya existe
                }
            }
        }
        //
        foreach ($lista as $it => $count) {
            if ($count == $numRow) {
            echo $it . " : " . $count . "<br>";
            }
        }
        // Limpiar la lista para la siguiente iteración
        $lista = [];
    }
}
?>
