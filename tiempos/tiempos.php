<?php
require '../app/conection.php';
$registo =isset($_GET['registro']) ? $_GET['registro'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 date_default_timezone_set('America/Mexico_City');
    $today = date('d-m-Y H:i');   
    $mainProcess = $_POST['proceso'];
    $subProcess = $_POST['subproceso'];
    $Process_Number=$_POST['Process_Number'];
    $DescriptionProcess = $_POST['DescriptionProcess'];
    $mm = $_POST['mm'];
    $PartNumber = $_POST['PartNumber'];
    $quien = $_POST['quien'];
    $obs = $_POST['obs'];
    $laps = $_POST['laps'];    

$insertInfo=mysqli_query($con,"INSERT INTO `timeprocess`( `dayHourProcess`, `process`, `subProcess`,`Process_Number`, `DescriptionProcess`, `mm`, `partnum`, `Operator`, `obs`, `laps`) VALUES ('$today','$mainProcess','$subProcess','$Process_Number','$DescriptionProcess','$mm','$PartNumber','$quien','$obs','$laps')");

if($insertInfo){
    
     header('Location: tiempos.php?registro=Registro exitoso');
}else{
        header('Location: tiempos.php');
}}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script >
        const respuesta = "<?php echo $registo; ?>";
        if(respuesta != ""){
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
                        <select name="subproceso" class="form-select" id="subproceso"  required onchange="suproceso()"></select>
                    </div>
                    <div class="mb-3">
                        <label for="Process_Number" class="form-label">Process Number</label>
                        <input type="text" name="Process_Number" class="form-control" id="Process_Number" required>
                    </div>
                    <div class="mb-3">
                        <label for="DescriptionProcess" class="form-label">Where Or what asset is being processed</label>
                        <input type="text" name="DescriptionProcess" class="form-control" id="DescriptionProcess" required>
                    </div>
                    <div class="mb-3" id="size-mm" style="display: none ;">
                        <label for="mm" class="form-label">Tamano del cable</label>
                        <input type="text" name="mm" class="form-control" id="mm"  value="0.00" step="0.01" required>
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
                        <textarea name="obs" id="obs" class="form-control" rows="3" ></textarea>
                      </div>

                    <div class="mb-3">
                        <label for="laps" class="form-label">Laps</label>      
                        <textarea name="laps" id="laps" class="form-control" rows="3" required ></textarea>      
                    
                    </div>
                  
                    <button type="button" id="startStopButton" class="btn btn-primary">Start</button>
                    <button type="button" id="lapButton" class="btn btn-secondary" disabled>Lap</button>
                    <button type="submit" class="btn btn-success" id="submitButton" disabled>Submit</button>
                </form>

                <!-- Total Elapsed Time Display -->
                <div id="timerDisplay">00:00:000</div>

                <!-- Lap Time Display -->
                <div id="lapDisplay">Lap Time: 00:00:000</div>

                <!-- List of Lap Times -->
                <div id="lapList" class="lap-times"></div>
            </div>
        </div>
    </div>

</body>

</html>

<script>
    let timerInterval;
    let isRunning = false;
    let startTime = 0;
    let elapsedTime = 0;
    let lapStartTime = 0;
    let laps = [];

    const startStopButton = document.getElementById('startStopButton');
    const lapButton = document.getElementById('lapButton');
    const submitButton = document.getElementById('submitButton');
    const lapsInput = document.getElementById('laps');
    const timerDisplay = document.getElementById('timerDisplay');
    const lapDisplay = document.getElementById('lapDisplay');
    const lapList = document.getElementById('lapList');
    const lapsres = document.getElementById('lapsres');

    startStopButton.addEventListener('click', () => {
        if (isRunning) {
            clearInterval(timerInterval);
            startStopButton.textContent = 'Start';
            lapButton.disabled = true;
            submitButton.disabled = false;
        } else {
            startTime = Date.now() - elapsedTime;
            lapStartTime = Date.now(); // Reset lap start time for each new session
            timerInterval = setInterval(updateTime, 100);
            startStopButton.textContent = 'Stop';
            lapButton.disabled = false;
        }
        isRunning = !isRunning;
    });

    lapButton.addEventListener('click', () => {
        const lapTime = formatTime(elapsedTime);
        laps.push(lapTime);
        lapsInput.value = laps.join('-');
       

        // Reset lap timer to 00:00:000 for next lap
        lapStartTime = Date.now();
        
        // Display lap time in the list
        
    });

    function updateTime() {
        elapsedTime = Date.now() - startTime;
        const formattedTime = formatTime(elapsedTime);
        timerDisplay.textContent = formattedTime; // Display total time on the page

        const lapElapsedTime = Date.now() - lapStartTime; // Time since last lap started
        const formattedLapTime = formatTime(lapElapsedTime);
        lapDisplay.textContent = `Lap Time: ${formattedLapTime}`; // Display lap time on the page
    }

    function formatTime(ms) {
        const minutes = Math.floor(ms / 60000);
        const seconds = Math.floor((ms % 60000) / 1000);
        const milliseconds = ms % 1000;
        return `${padZero(minutes)}:${padZero(seconds)}:${padZero(milliseconds, 3)}`;
    }

    function padZero(num, length = 2) {
        return num.toString().padStart(length, '0');
    }
</script>
<script>
    const proceso = document.getElementById('proceso');
const subproceso = document.getElementById('subproceso');
function diffprocess(){
    

var corte =['','cutting w/2 terminals without saels',
            'cutting w/1 terminals without saels',
            'cutting w/1 terminals with saels',
            'cutting w/2 terminals with saels',
            'cutting without terminals'];

var terminales =['',
                'twist wire',
                'terminal apply with machine',
                'terminal apply with handtool',
                'create set for splice',
                'splice set apply with machine',
                'splice set apply with handtool',
                'put seal on wire',
                'set headshrink',
                'burn headshrirnk w/headgun',
                'burn headshrink w/machine',
                'set tin point',];

var assembly =['','subassembly',
              'assembly',
                'Add Ties',
            ];
var loom =['','looming',
            'braiding',
            'set slavee for braiding',
            'Tapping',
            'labeling',
            
        ];

if(proceso.value == 'Cutting'){
    subproceso.innerHTML = '';
    for (let i = 0; i < corte.length; i++) {
        subproceso.innerHTML += `<option value="${corte[i]}">${corte[i]}</option>`;
    }

}else if(proceso.value == 'Terminals'){
    subproceso.innerHTML = '';
    for (let i = 0; i < terminales.length; i++) {
        subproceso.innerHTML += `<option value="${terminales[i]}">${terminales[i]}</option>`;
    }
}else if(proceso.value == 'Assembly'){
    subproceso.innerHTML = '';
    for (let i = 0; i < assembly.length; i++) {
        subproceso.innerHTML += `<option value="${assembly[i]}">${assembly[i]}</option>`;
    }    
}else if(proceso.value == 'Looming'){
    subproceso.innerHTML = '';
    for (let i = 0; i < loom.length; i++) {
        subproceso.innerHTML += `<option value="${loom[i]}">${loom[i]}</option>`;
    }    
}
}
function suproceso(){
    if(subproceso.value == 'cutting w/2 terminals without saels' || subproceso.value == 'cutting w/1 terminals without saels'
     || subproceso.value == 'cutting w/1 terminals with saels' || subproceso.value == 'cutting w/2 terminals with saels' 
     || subproceso.value == 'cutting without terminals' || subproceso.value == 'twist wire'){
        document.getElementById('size-mm').style.display = 'block';
    }else{
        document.getElementById('size-mm').style.display = 'none';
    }
}

</script>