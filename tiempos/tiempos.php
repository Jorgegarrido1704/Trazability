<?php
require '../app/conection.php';
$registo = isset($_GET['registro']) ? $_GET['registro'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    date_default_timezone_set('America/Mexico_City');
    $today = date('d-m-Y H:i');
    $mainProcess = $_POST['proceso'];
    $subProcess = $_POST['subproceso'];
    $Process_Number = $_POST['Process_Number'];
    $DescriptionProcess = $_POST['DescriptionProcess'];
    $mm = $_POST['mm'];
    $PartNumber = $_POST['PartNumber'];
    $quien = $_POST['quien'];
    $obs = $_POST['obs'];
    $laps = $_POST['laps'];

    $insertInfo = mysqli_query($con, "INSERT INTO `timeprocess`( `dayHourProcess`, `process`, `subProcess`,`Process_Number`, `DescriptionProcess`, `mm`, `partnum`, `Operator`, `obs`, `laps`) VALUES ('$today','$mainProcess','$subProcess','$Process_Number','$DescriptionProcess','$mm','$PartNumber','$quien','$obs','$laps')");

    if ($insertInfo) {

        header('Location: tiempos.php?registro=Registro exitoso');
    } else {
        header('Location: tiempos.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script>
        const respuesta = "<?php echo $registo; ?>";
        if (respuesta != "") {
            alert(respuesta);
            redirect = "tiempos.php";
            window.location.href = redirect;
        }
    </script>
    <title>Timer </title>

    <style>
        #lapButton {
            margin-top: 10px;
        }

        .lap-times {
            margin-top: 20px;
        }

        #timerDisplay {
            font-size: 2em;
            margin-top: 20px;
        }

        #lapDisplay {
            font-size: 1.5em;
            margin-top: 20px;
            color: #555;
        }

        .lap-time {
            margin-top: 10px;
            font-size: 1.2em;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Timer with Laps</h1>
            </div>
            <div class="col-10">
                <form action="tiempos.php" method="post">
                    <div class="mb-3">
                        <label for="proceso" class="form-label">Proceso</label>
                        <select name="proceso" id="proceso" class="form-select" required onchange="diffprocess()">
                            <option value="">Select Proceso</option>
                            <option value="Cutting">Cutting</option>
                            <option value="Terminals">Terminals</option>
                            <option value="Assembly">Assembly</option>
                            <option value="Looming">Looming</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subproceso" class="form-label">Subproceso</label>
                        <select name="subproceso" class="form-select" id="subproceso" required onchange="suproceso()"></select>
                    </div>
                    <div class="mb-3">
                        <label for="Process_Number" class="form-label">Process Number</label>
                        <input type="text" name="Process_Number" class="form-control" id="Process_Number" required>
                    </div>
                    <div class="mb-3">
                        <label for="DescriptionProcess" class="form-label">Where Or what asset is being processed</label>
                        <select name="DescriptionProcess" id="DescriptionProcess" class="form-select" ></select>
                    </div>
                    <div class="mb-3" id="size-mm" style="display: none ;">
                        <label for="mm" class="form-label">Tamano del cable</label>
                        <input type="text" name="mm" class="form-control" id="mm" value="0.00" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="PartNumber" class="form-label">Part Number</label>
                        <input type="text" name="PartNumber" class="form-control" id="PartNumber" required>
                    </div>
                    <div class="mb-3">
                        <label for="quien" class="form-label">Quien realiza proceso</label>
                        <input type="text" name="quien" class="form-control" id="quien" required>
                    </div>
                    <div class="mb-3">
                        <label for="obs" class="form-label">Obseravisones</label>
                        <textarea name="obs" id="obs" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="laps" class="form-label">Laps</label>
                        <textarea name="laps" id="laps" class="form-control" rows="3" required></textarea>

                    </div>

                    <button type="button" id="startStopButton" class="btn btn-primary">Start</button>
                    <button type="button" id="lapButton" class="btn btn-secondary" disabled>Lap</button>
                    <button type="submit" class="btn btn-success" id="submitButton" disabled>Submit</button>
                </form>

                <!-- Total Elapsed Time Display -->
                <div id="timerDisplay">00</div>

                <!-- Lap Time Display -->
                <div id="lapDisplay">Lap Time: 00</div>

                <!-- List of Lap Times -->
                <div id="lapList" class="lap-times"></div>
            </div>
        </div>
    </div>

</body>

</html>

<script src="manejoTiempos.js"></script>