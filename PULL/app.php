<?php 
$host = "localhost";
$user = "pcadmin";
$clave = "SupAdmin1212";
$db_name = "trazabilidad";

$con = new mysqli($host, $user, $clave, $db_name);

if (!$con->connect_error) {
    echo "Conexión exitosa";
} else {
    die("Error en la conexión: " . $con->connect_error);
}
date_default_timezone_set('America/Mexico_City');

$aplicador=isset($_GET['aplicador1'])?$_GET['aplicador1']:"";
$maquina=isset($_GET['maquina1'])?$_GET['maquina1']:"";
$quien=isset($_GET['quien1'])?$_GET['quien1']:"";
$trabajo=isset($_GET['trabajo1'])?$_GET['trabajo1']:"";

if($aplicador !="No esta(preguntar y agregar)" or $aplicador != ""){
$herra=mysqli_query($con,"SELECT * FROM mant_golpes_diarios WHERE terminal='$aplicador'");
while($row=mysqli_fetch_array($herra)){
$qry=$row['herramental'];
}
}
$fecha=date("d-m-Y H:i");
// Validación básica




   // Preparar consulta segura
$stmt = $con->prepare("
    INSERT INTO registro_paro 
    (`fecha`, `equipo`, `nombreEquipo`, `dano`, `quien`, `area`, `atiende`) 
    VALUES (?, 'Bancos para terminales', ?, ?, ?, ?, 'Nadie aun')
");

if (!$stmt) {
    die("Error al preparar la consulta: " . $con->error);
}

// Concatenar $qry y $aplicador de forma segura
$nombreEquipo = $qry . '/' . $aplicador;

// Vincular parámetros (s = string)
$stmt->bind_param("sssss", $fecha, $nombreEquipo, $trabajo, $quien, $maquina);

// Ejecutar y verificar
if ($stmt->execute()) {
    // Cerrar y redirigir
    $stmt->close();
    $con->close();
    header("Location: solicitar.php");
    exit;
} else {
    echo "Error al insertar: " . $stmt->error;
}











