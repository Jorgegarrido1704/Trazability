<?php
// Conexión a la base de datos
$con = mysqli_connect("localhost", "pcadmin", "SupAdmin1212", "trazabilidad");
if (!$con) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Inicializar grupos
$grupos = [
    'A' => [],
    'B' => [],
    'C' => [],
    'D' => [],
    'E' => [],
    'F' => [],
    'G' => [],
    'H' => [],
];

// Obtener el conteo de pn directamente con SQL
$sql = "SELECT pn, COUNT(*) AS cantidad FROM listascorte GROUP BY pn";
$result = mysqli_query($con, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pn = $row['pn'];
        $cantidad = $row['cantidad'];

        // Asignar a grupo
        $grupo = asignarGrupo($cantidad);
        array_push($grupos[$grupo], $pn);
    }
} else {
    echo "Error en la consulta: " . mysqli_error($con);
}

// Función para asignar un PN a un grupo
function asignarGrupo($cantidad) {
    if ($cantidad >= 300) return 'A';
    if ($cantidad >= 200) return 'B';
    if ($cantidad >= 100) return 'C';
    if ($cantidad >= 50) return 'D';
    if ($cantidad >= 25) return 'E';
    if ($cantidad >= 10) return 'F';
    if ($cantidad >= 5) return 'G';
    return 'H';
}

// Función para procesar las familias de cada grupo
function familias($group) {
    global $grupos, $con;

    $subFamiliesA = $grupos[$group];  // Obtener los subgrupos del grupo especificado
    $groups = [];
    $y = 0;

    // Procesar las familias en lotes de 2000
    foreach ($subFamiliesA as $subA) {
        $i = 0;
        $items = [];
        $compatativo = [];

        // Llamar una vez por todas las entradas relacionadas con el PN (subA)
        $sql = "
            SELECT * FROM datos 
            WHERE part_num = '$subA' 
            AND item NOT LIKE 'WTXL-%' 
            AND item NOT LIKE 'WGXL-%' 
            AND item NOT LIKE 'WSGX-%' 
            AND item NOT LIKE 'LTP%' 
            AND item NOT LIKE 'LW-%' 
            AND item NOT LIKE 'TAPE-25'
        ";

        $result = mysqli_query($con, $sql);
        if (!$result) {
            echo "Error en la consulta: " . mysqli_error($con);
            continue;
        }

        while ($row = mysqli_fetch_array($result)) {
            $item = $row['item'];
            $items[$subA][$i] = $item;
            $i++;
        }

        $totalItems = mysqli_num_rows($result);  // Total de ítems para calcular el porcentaje

        // Consultar los items relacionados con cada uno de los items obtenidos
        foreach ($items as $key => $valueArray) {
            // Creating placeholders dynamically
            $placeholders = implode(",", array_fill(0, count($valueArray), '?'));
            $values = $valueArray;

            // Prepare the SQL query
            $sqlcomp = "
                SELECT * FROM datos WHERE item IN ($placeholders) AND part_num != '$subA'
            ";
            $stmt = mysqli_prepare($con, $sqlcomp);

            // Bind parameters dynamically
            $types = str_repeat("s", count($values));  // Create a string with 's' for each value
            mysqli_stmt_bind_param($stmt, $types, ...$values);

            if (!mysqli_stmt_execute($stmt)) {
                echo "Error en la consulta: " . mysqli_error($con);
                continue;
            }

            $resultcomp = mysqli_stmt_get_result($stmt);
            while ($row1 = mysqli_fetch_array($resultcomp)) {
                $part_num = $row1['part_num'];

                // Agregar al array comparativo
                if (isset($compatativo[$key][$part_num])) {
                    $compatativo[$key][$part_num] += 1;
                } else {
                    $compatativo[$key][$part_num] = 1;
                }
            }
        }

        // Filtrar y mostrar resultados con compatibilidad > 75%
        $groups[$subA][0] = $subA.' no compatibility >= 75 %';
        foreach ($compatativo as $key => $comparisons) {
            foreach ($comparisons as $part_num => $count) {
                $compatibility = ($count / $totalItems) * 100;
                if ($compatibility >= 75) {
                    $y++;
                    // Insertar los resultados de compatibilidad en el grupo
                    $groups[$subA][$y] = $part_num.' compatibility: '.round($compatibility, 2).'%';
                    print($subA . ' - ' . $part_num . ' - ' . round($compatibility, 2) . '%<br>');
                }
            }
        }
    }
    echo "<br> <br>";

    return $groups;
}



$allGroups = [];
foreach ($grupos as $groupName => $groupMembers) {
    $allGroups[$groupName] = familias($groupName);  
    
}



echo "<pre>";
print_r($allGroups); // Solo para ver los resultados en pantalla
echo "</pre>";

// Cerrar conexión
mysqli_close($con);
?>
