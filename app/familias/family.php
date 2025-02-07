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
    $buffer = ""; // Almacena los resultados a imprimir
    while ($row = mysqli_fetch_assoc($result)) {
        $pn = $row['pn'];
        $cantidad = $row['cantidad'];

        // Asignar a grupo
        $grupo = asignarGrupo($cantidad);
        array_push($grupos[$grupo], $pn);
        $buffer .= "Grupo $grupo PN: $pn Cantidad: $cantidad<br>";
    }
    // Imprimir resultados
    //echo $buffer;
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


/* foreach($grupos['H'] as $pn){
    echo $pn."<br>";
}*/
// Cerrar conexión

?>
