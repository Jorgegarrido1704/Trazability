<?php
require_once "../app/conection.php";
$success = isset($_GET['success']) ? $_GET['success'] : "";
$color = isset($_GET['color']) ? $_GET['color'] : "";
$color==""?$color="alert alert-success text-center":$color="alert alert-danger text-center";
date_default_timezone_set("America/Mexico_City");
//echo $color;                
$date= (date("H:i"));
$weekday = intval(date('W'));


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="360">

    <title>Relog Checador</title>
    <link rel=" stylesheet" href="css/estilis.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body style="background-color: rgba(87, 150, 252, 0.47);">
    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <img src="img/begstrsom.jpg" alt="Begstrsom" class="img-fluid" style="max-height: 350px; width: 100%;">
        </header>
        <div id="alert" style="display:none;" class="<?php echo $color; ?>" role="alert">
            <h2><strong> <?php echo $success; ?></strong></h2>
        </div>


        <div class="row">
            <div class="col-md-12 mt-2 mb-2" id="registros">
                <div class="form-group">
                    <h2 class="text-center">Bienvenido al sistema de asistencia</h2>
                    <h3 class="text-center">Por favor, utilice el codigo Qr de su tarjeta para registrar su entrada o
                        salida</h3>
                </div>
                <div class="form-group text-center mt-4">
                    <h4 class="text-center">La hora actual es: <span id="time"></span></h4>
                </div>
                <form action="registrarAsistencia.php" method="GET" class="mt-4">
                        <div class="form-group">
                            <label for="cardCode" class="form-label">Ingrese el código QR de su tarjeta:</label>
                            <input type="text" class="form-control" id="cardCode" name="cardCode" minlength="4"
                                maxlength="5" required autofocus>
                        </div>
                        </form>



            </div>
        </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
                crossorigin="anonymous"></script>
</body>

</html>
<script>
document.getElementById("cardCode").addEventListener("input", function() {
    if (this.value.length === 6) {
        this.form.submit();
    }
});


function timerPermin() {
    let time = new Date();
    // Get current hours and minutes in mexico city time zone
    let options = {
        timeZone: "America/Mexico_City",
        hour: "2-digit",
        minute: "2-digit"
    };
    let timeString = time.toLocaleTimeString("en-US", options);
    document.getElementById("time").innerHTML = timeString;


}

setInterval(timerPermin, 50);
window.onload = timerPermin;

let success = "<?php echo $success; ?>";
if (success !== "") {

    document.getElementById("alert").style.display = "block";
    document.getElementById("registros").style.display = "none";
    setTimeout(function() {
        window.location.href = "asistencias.php";
    }, 800);
    clearInterval();

}
</script>