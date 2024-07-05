<?php 
$local = "localhost";
$user = "root";
$pass = "";
$bd = "mantenimiento";
$con = mysqli_connect($local, $user, $pass, $bd);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : "";

if (!empty($fecha)) {
    $buscar = "SELECT * FROM herramental WHERE fecha LIKE '%$fecha%'";
    $qry = mysqli_query($con, $buscar);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tabla.css">
    <title>Registros de Entradas y Salidas</title>
</head>
<body>
    <header>
        <h2>Registros de Entradas y Salidas</h2>
    </header>

    <div class="container">
        <form method="post" action="tabla.php">
            <label for="fecha">Fecha a buscar información</label>
            <input type="text" id="fecha" name="fecha">
            <button type="submit">Buscar</button>
        </form>

        <?php 
        if (!empty($fecha) && mysqli_num_rows($qry) > 0) {
            while ($row = mysqli_fetch_array($qry)) {
        ?>    
            <p>Herramental: <?php echo $row['herramental']; ?></p>
            <p>Fecha: <?php echo $row['fecha']; ?></p>
            <p>Accion: <?php echo $row['Accion']; ?></p>
            <br>
        <?php 
            }
        } else if (!empty($fecha)) {
            echo "<p>No se encontraron resultados para la fecha ingresada.</p>";
        }
        ?>
        
        <div class="button-container">
            <a href="registro.php" class="button">Registro</a>
        </div>
    </div>
</body>
</html>
