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
$datos = 200;
// Obtener el conteo de pn directamente con SQL
$sql = "SELECT pn,rev, COUNT(*) AS cantidad FROM listascorte GROUP BY pn HAVING COUNT(*) > $datos  ORDER BY cantidad DESC";
$result = mysqli_query($con, $sql);
if ($result) {
    $grupos = array(); // Inicializar el arreglo de grupos
    while ($row = mysqli_fetch_assoc($result)) {
        $pn = $row['pn'];
        $cantidad = $row['cantidad'];
        $rev = $row['rev'];

        // Asignar a grupo
        $grupo = asignarGrupo($cantidad);
        
        // Asegurarse de que el grupo esté inicializado en el arreglo
        if (!isset($grupos[$grupo])) {
            $grupos[$grupo] = array();
        }
        // Añadir el PN al grupo correspondiente
        $grupos[$grupo] = array( $pn);
    }
} 
foreach($grupos as $grupo => $pns){
        echo $grupo."=>" . $pns;
        
        echo "<br>";    
    }
    echo "<br>";



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
