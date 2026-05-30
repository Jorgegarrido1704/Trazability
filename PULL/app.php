<?php 
$host = "127.0.0.1";
$user = "root";
$clave = "";
$db_name = "trazabilidad";

// 1. Conexión
$con = new mysqli($host, $user, $clave, $db_name);

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


try {
    $fecha = date("Y-m-d H:i:s");
    $hoy = date("Y-m-d");
 // Concatenar nombre de equipo
    $nombreEquipo = $qry . ' / ' . $aplicador;
    // buscar duplicados
    $buscardduplicados = mysqli_query($con, "SELECT * FROM registro_paro WHERE fecha LIKE '$hoy%' AND  nombreEquipo = '$nombreEquipo' AND dano = '$trabajo' AND quien = '$quien' AND area = '$maquina'");
    if (mysqli_num_rows($buscardduplicados) > 0) {
        echo "Error: Ya existe un registro similar para hoy.";
         header("Location: solicitar.php");
        exit;
    }
    
    // Preparar consulta de inserción
    $stmt = $con->prepare("
        INSERT INTO registro_paro 
        (`fecha`, `equipo`, `nombreEquipo`, `dano`, `quien`, `area`, `atiende`) 
        VALUES (?, 'Bancos para terminales', ?, ?, ?, ?, '')
    ");

    if (!$stmt) {
        throw new Exception("Error al preparar: " . $con->error);
    }



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