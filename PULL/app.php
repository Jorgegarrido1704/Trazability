<?php 
$host = "localhost";
$user = "pcadmin";
$clave = "SupAdmin1212";
$db_name = "trazabilidad";

$con = mysqli_connect($host, $user, $clave, $db_name);
if (mysqli_connect_errno()) {
    die("Failed to connect with MySQL: " . mysqli_connect_error());
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
$fecha=date("d-m-Y H:i");
// Validación básica
if (!$fecha || !$qry || !$aplicador || !$trabajo || !$quien || !$maquina) {
    die("Datos incompletos.");
}


if( $maquina!="" and $quien!="" and $trabajo!=""){
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

$stmt->close();
$con->close();
    header("Location: solicitar.php");
}else if($aplicador !="No esta(preguntar y agregar)"){
    
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
$nombreEquipo =  $aplicador;

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

$stmt->close();
$con->close();
    header("Location: solicitar.php");

}



}






