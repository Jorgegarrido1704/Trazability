<?php
require "../app/conection.php";
$activaListas = isset($_GET['active']) ? $_GET['active'] : "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <title>Corte</title>
</head>

<body>
    <div class="row ">
        <div class="col-lg-2 mb-4 border">
            <h1>Listas de corte</h1>
            <form action="busqueda.php" method="GET">
                <input type="hidden" name="active" id="active" value="active">
                <input type="submit" name="enviar" id="enviar" value="Activar listas">
            </form>
        </div>
        <div class="col-lg-2 mb-4 border">
            <h1>Modificar Cuenta</h1>
             <form action="../errores/embarque.php" method="get">
            <label for="registro">WO(s)</label>    
            <input type="text" name="registro" id="registro">
            <label for="count">count</label>
            <input type="number"  id= "count" name="count" value="20">
            <button class="btn btn-primary" type="submit" >Modificar</button>
        </form>
        </div>
        <div class="col-lg-2 mb-4 border">
            <h1>Modificacion de lista</h1>
            <form action="modificacionLista.php" method="GET">
                <label for="Num">Part Number: </label>
                <input type="text" name="Num" id="Num">
                <label for="cons1">Consecutivo: </label>
                <input type="text" name="cons1" id="cons1" placeholder="separados por comas(1,5)">
                <input type="submit" name="value" id="value" value="Buscar">
            </form>
        </div>
        <div class="col-lg-2 mb-4 border">
            <h1>Cargar listas</h1>
            <form action="uplista.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="csv_file" accept=".csv">
                <br>
                <input type="submit" name="upload" value="Cargar CSV">
            </form>
        </div>
        <div class="col-lg-2 mb-4 border">
            <h1>Update Boms</h1>
            <form action="guardarprueba.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="excel_file" id="excel_file" accept=".csv">
                <input type="submit" name="upload" value="Subir">
            </form>
        </div>
        <div class="col-lg-2 mb-4 border">
            <h1>Lista actividades</h1>
            <form action="../ing/upActividades.php" method="POST" enctype="multipart/form-data">

                <input type="file" name="csv_file" accept=".csv">
                <br>
                <input type="submit" name="upload" value="Cargar CSV">
            </form>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-2 mb-4 border">
            <h3>Cargar Routing</h3>
            <form action="routing/cortes.php" method="GET">
                <input type="text" name="np" id="np" required>
                <br>
                <input type="submit" name="upload" value="Cargar Routing">
            </form>
        </div>
        <div class="col-lg-2 mb-4 border">
            <h3>Agregar Routing Asset</h3>
            <form action="routing/addRouting.php" method="GET">
                <button class="btn btn-primary"> Agregar </button>
            </form>
        </div>
        <div class="col-lg-2 mb-4 border">
            <h3>Agregar Routing Process</h3>
            <form action="routing/addProcess.php" method="GET">
                <div class="input-group mb-3">
                    <label for="pn">Numero de parte </label>
                </div>
                <div class="input-group mb-3">
                    <input type="text" name="pn" id="pn" class="form-control" required>
                </div>
                <button class="btn btn-primary"> Agregar </button>
            </form>
        </div>
        <div class="col-lg-2 mb-4 border">
            <h1>Cargar MPS </h1>
            <form action="../reportes/MPS/updateDatos.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="csv_file" accept=".csv">
                <br>
                <input type="submit" name="upload" value="Cargar MSP">
            </form>
        </div>
        <div class="col-lg-2 mb-4 border">
            <h1>MPS</h1>
            <form action="../reportes/MPS/registos.php" method="POST" >
                <br>
                <button class="btn btn-primary">MPS</button>
            </form>
        </div>
       <div class="col-lg-2 mb-4 border">
            <h1>Differencia Inventarios</h1>
            <form action="../errores/diferenciaInvnetario.php" method="POST" >
                <br>
                <button class="btn btn-primary">Ajuste inventario</button>
            </form>
        </div>
    </div>
    <br>

    <?php
    if ($activaListas != "") {
        $i = 1;
        echo "<div class='titles'><h1 >Lista en sistema</h1>";
        echo "<table><tbody><tr>";
        $buscar = mysqli_query($con, "SELECT DISTINCT pn,rev FROM listascorte ");
        while ($row = mysqli_fetch_array($buscar)) {
            $nup = $row['pn'];
            $revs = $row['rev'];
            if ($i % 10 == 0) {
                echo "<tr>";
                echo "<td>" . $nup . "-" . $revs . "</td>";
            } else {
                echo "<td>" . $nup . "-" . $revs . "</td>";
            }
            if (($i + 1) % 10 == 0) {
                echo "</tr>";
            }
            $i++;
        }
        echo "</tbody></table></div>";
    }
    ?>

</body>

</html>