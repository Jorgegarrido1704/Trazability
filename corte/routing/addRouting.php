<?php
require "../../app/conection.php";
if(isset($_GET['NumeroRuteo']) and isset($_GET['descripcionRuteo']) and isset($_GET['possibleAsset'])){
    

$numeroRuteo=$_GET['NumeroRuteo']?$_GET['NumeroRuteo']:"";
$descripcionRuteo=$_GET['descripcionRuteo']?$_GET['descripcionRuteo']:"";
$possibleAsset=$_GET['possibleAsset']?$_GET['possibleAsset']:"";

if($numeroRuteo!="" and $descripcionRuteo!="" and $possibleAsset!=""){
    $existe=mysqli_query($con,"SELECT * FROM routing_process WHERE routingNumber='$numeroRuteo' limit 1");
    $rownum=mysqli_num_rows($existe);
    if($rownum>0){
        $updateAss=mysqli_fetch_assoc($existe);
        $possibleAsset=$updateAss['posibleAssets'].",".$possibleAsset;
        $updateAsset=mysqli_query($con,"UPDATE routing_process SET posibleAssets='$possibleAsset' WHERE routingNumber='$numeroRuteo'");
    }else{
    $sql=mysqli_query($con,"INSERT INTO routing_process ( `routingNumber`, `routingDescription`, `posibleAssets`) VALUES   ( '$numeroRuteo', '$descripcionRuteo', '$possibleAsset')");
    }
    header("Location: addRouting.php");
}
}
$routings = mysqli_query($con,"SELECT * FROM routing_process ORDER BY id_routing_process DESC");
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
            <form class="row g-3" action="addRouting.php" method="GET">
                <div class="col-lg-1 mb-4 "></div>
                    <div class="col-lg-3 mb-4 ">
                            <div class="input-group ">
                                <label for="NumeroRuteo">Numero de ruteo</label>
                            </div>
                            <div class="input-group ">
                            <input type="text" name="NumeroRuteo" id="NumeroRuteo" required>
                            </div>
                    </div>
                        <div class="col-lg-3 mb-4 ">
                            <div class="input-group ">
                                <label for="descripcionRuteo">Descripcion</label>
                            </div>
                            <div class="input-group ">
                            <textarea name="descripcionRuteo" id="descripcionRuteo" rows="3" cols="50" required ></textarea>
                            </div>
                    </div>
                        <div class="col-lg-3 mb-4 ">
                            <div class="input-group ">
                                <label for="possibleAsset">Posible Asset</label>
                            </div>
                            <div class="input-group ">
                                <textarea name="possibleAsset" id="possibleAsset" rows="3" cols="50" required ></textarea>
                            </div>
                    </div>
                        <div class="col-lg-2 mb-4 ">
                            <div class="input-group ">
                            <button class="btn btn-primary"> Agregar </button>
                            </div>
                        </div>
                </form>
        </div>
    </div>

<table class="table table-bordered table-striped" id="regTable">
    <thead>
        <tr>
            <th>Routing</th>
            <th>Descripcion</th>
            <th>Posibles Assets</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($routings as $routing) { 
            echo "<tr>";
            echo "<td>" . $routing['routingNumber'] . "</td>";
            echo "<td>" . $routing['routingDescription'] . "</td>";
            echo "<td>" . $routing['posibleAssets'] . "</td>";
            echo "</tr>";   
        }?>
    </tbody>
</table>
</body>
</html>