<?php
require "../../app/conection.php";
$numero=$_GET['pn']?$_GET['pn']:"";


$routings = mysqli_query($con,"SELECT * FROM routing_process ORDER BY id_routing_process DESC");
$registroDatos = mysqli_query($con,"SELECT * FROM routing_models WHERE pn_routing = '$numero' ");
if(isset($_GET['descripcionRuteo']) and isset($_GET['qtyTimes']) and isset($_GET['timeProcess'])){
    $descripcionRuteo=$_GET['descripcionRuteo']?$_GET['descripcionRuteo']:"";
    $qtyTimes=$_GET['qtyTimes']?$_GET['qtyTimes']:"";
    $timeProcess=$_GET['timeProcess']?$_GET['timeProcess']:"";
    $verificar = mysqli_query($con,"SELECT * FROM routing_process WHERE routingDescription = '$descripcionRuteo' limit 1 ");
        $updateAss=mysqli_fetch_assoc($verificar);
        $possibleAsset=$updateAss['posibleAssets'];
        $numberRoutingUpdate=$updateAss['routingNumber'];
        $pn=$_GET['pn'];
       
    $sql=mysqli_query($con,"INSERT INTO routing_models ( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
    VALUES ('$pn','$numberRoutingUpdate','$possibleAsset','$descripcionRuteo','$qtyTimes','$timeProcess','300')");
    header("Location: addProcess.php?pn=$pn");
}

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
    <div class="container align-items-left">
        <button class="btn btn-success"><a href="../busqueda.php" id="principal">Home</a></button>
    </div>
    <div class="row">
        <div class="col-lg-12 mb-4 center">
            <h3 class="text-center">Agregar Routing Process</h3>
        </div>
        <div class="col-lg-12 mb-4 ">
            <form class="row g-3" action="addProcess.php" method="GET">
                <div class="col-lg-1 mb-4 "></div>
                        <div class="col-lg-3 mb-4 ">
                            <div class="select-group ">
                                <label for="descripcionRuteo">Descripcion</label>
                            </div>
                            <div class="select-group ">
                                <select name="descripcionRuteo" id="descripcionRuteo" required>
                                    <option value="" disabled selected> Seleccionar</option>
                                    <?php
                                    foreach ($routings as $routing) {
                                        echo "<option value='" . $routing['routingDescription'] . "'>" . $routing['routingDescription'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                    </div>
                        <div class="col-lg-2 mb-4 ">
                            <div class="input-group">
                                <label for="qtyTimes">Qty Times</label>
                            </div>
                            <div class="input-group ">
                               <input type="number" name="qtyTimes" id="qtyTimes" step="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-4 ">
                            <div class="input-group">
                                <label for="timeProcess">Time in each process</label>
                            </div>
                            <div class="input-group ">
                               <input type="number" name="timeProcess" id="timeProcess" step="0.001" min="0.001" required>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-4 ">
                            <div class="input-group ">
                                <input type="hidden" name="pn" id="pn" value="<?php echo $numero; ?>">
                            <button class="btn btn-primary"> Agregar </button>
                            </div>
                        </div>
                </form>
        </div>
    </div>

<table class="table table-bordered table-striped" id="regTable">
    <thead>
        <tr>
            <th>Routing Number</th>
            <th>Descripcion</th>
            <th>Posibles Assets</th>
            <th>Qty Times</th>
            <th>Time Per Process</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($registroDatos as $registroDato) { 
            echo "<tr>";
            echo "<td>" . $registroDato['work_routing'] . "</td>";
            echo "<td>" . $registroDato['work_description'] . "</td>";
            echo "<td>" . $registroDato['posible_stations'] . "</td>";
            echo "<td>" . $registroDato['QtyTimes'] . "</td>";
            echo "<td>" . $registroDato['timePerProcess'] . "</td>";
            echo "</tr>";   
        }?>
    </tbody>
</table>
</body>
</html>