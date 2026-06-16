<?php
$localhost = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'trazabilidad';

// Create connection
$conn = new mysqli($localhost, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set("America/Mexico_City"); 
$codigo=isset($_POST['codigo'])?$_POST['codigo']:"";
if($codigo!=""){
    $actualdate = date('Y-m-d');
    $busqueda = "UPDATE `corte` SET `cutStatus` = 'Cortado',`fechaDeregistro` = '$actualdate' WHERE `codigo` = '$codigo'";
    $result = $conn->query($busqueda);
    if ($result) {
        echo "<div class='alert alert-success' role='alert'>Corte removido con exito</div>";
        //reload
        echo "<meta http-equiv='refresh' content='2; url=baja_corte_sistema.php'>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error al remover corte</div>";
    }}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Remover cortes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
      <h1>Remover cortes</h1>
      <form method="POST" action="baja_corte_sistema.php">
        <div class="row">
            <div class="col-md-6">
                    <label for="codigo" class="form-label">Escane el codigo de corte a remover</label>
                    <input type="text" class="form-control" id="codigo" name="codigo" maxlength="12" placeholder="001241014"  required>
            </div> 
                    <button type="submit" class="btn btn-primary">Agregar Tiempo Muerto</button>
                
            </div>      
            </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>
