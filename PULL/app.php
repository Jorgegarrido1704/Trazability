<?php 
$host = "localhost";
$user = "pcadmin";
$clave = "SupAdmin1212";
$db_name = "trazabilidad";

// 1. Conexión
$con = mysqli_connect($host, $user, $clave, $db_name);

if ($con->connect_error) {
    die("Error en la conexión: " . $con->connect_error);
}

date_default_timezone_set('America/Mexico_City');

// 2. Inicializar variables
$aplicador = isset($_GET['aplicador1']) ? $_GET['aplicador1'] : "";
$maquina   = isset($_GET['maquina1'])   ? $_GET['maquina1']   : "";
$quien     = isset($_GET['quien1'])     ? $_GET['quien1']     : "";
$trabajo   = isset($_GET['trabajo1'])   ? $_GET['trabajo1']   : "";
$qry       = ""; // Valor por defecto para evitar que la variable no exista

// 3. Lógica de búsqueda (Corregida con AND)
if ($aplicador != "No esta(preguntar y agregar)" && $aplicador != "") {
    // Usar Sentencias Preparadas también aquí es más seguro
    $stmt_search = $con->prepare("SELECT herramental FROM mant_golpes_diarios WHERE terminal = ?");
    $stmt_search->bind_param("s", $aplicador);
    $stmt_search->execute();
    $result = $stmt_search->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $qry = $row['herramental'];
    }
    $stmt_search->close();
}

// 4. Formato de fecha correcto para MySQL
$fecha = date("Y-m-d H:i:s");

try {
    // Preparar consulta de inserción
    $stmt = $con->prepare("
        INSERT INTO registro_paro 
        (`fecha`, `equipo`, `nombreEquipo`, `dano`, `quien`, `area`, `atiende`) 
        VALUES (?, 'Bancos para terminales', ?, ?, ?, ?, 'Nadie aun')
    ");

    if (!$stmt) {
        throw new Exception("Error al preparar: " . $con->error);
    }

    // Concatenar nombre de equipo
    $nombreEquipo = $qry . ' / ' . $aplicador;

    // Vincular parámetros
    $stmt->bind_param("sssss", $fecha, $nombreEquipo, $trabajo, $quien, $maquina);

    if ($stmt->execute()) {
        $stmt->close();
        $con->close();
        // Redirigir
        header("Location: solicitar.php");
        exit;
    } else {
        echo "Error al insertar: " . $stmt->error;
    }

} catch (Exception $e) {
    echo "Error de ejecución: " . $e->getMessage();
}
?>