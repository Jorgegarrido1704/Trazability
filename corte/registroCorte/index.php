<?php
require "../../app/conection.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros Corte</title>

    <!-- Bootstrap JS & Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

    <!-- Bootstrap CSS (faltaba) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>
    <div class="container">
        <div class="row text-center">

            <div class="col-md-12 mt-3 " id=Datos>
                <h2>Registra tu codigo</h2>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Codigo</span>
                    </div>

                    <input type="text" class="form-control" id="codigo" name="codigo" autofocus placeholder="Ingrese su codigo" onchange="Registros()">
                </div>
            </div>
            <div class="col-md-12 mt-3" id="registros" style="display: none;">
                <div class="row">
                    <div class=" col-md-3 mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Numero de parte</span>
                        </div>
                        <input type="text" class="form-control" id="np" name="np" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Cliente</span>
                        </div>
                        <input type="text" class="form-control" id="cli" name="cli" readonly>
                    </div>
                    <div class=" col-md-2 mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Revision</span>
                        </div>
                        <input type="text" class="form-control" id="rev" name="rev" readonly>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Work Order</span>
                        </div>
                        <input type="text" class="form-control" id="wo" name="wo" readonly>
                    </div>
                    <div class=" col-md-2 mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Consecutivo</span>
                        </div>
                        <input type="text" class="form-control" id="cons" name="cons" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Estampado</span>
                        </div>
                        <input type="text" class="form-control" id="estamp" name="estamp" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Datos de corte</span>
                        </div>
                        <input type="text" class="form-control" id="datosCorte" name="datosCorte" readonly>
                    </div>
                    <form action="archivosJs.php" method="POST">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Selecciona unidad de medida</span>
                                </div>
                                <select class="form-control" name="udm" id="udm" required>
                                    <option value="" disabled selected></option>
                                    <option value="MM">Milimetros</option>
                                    <option value="IN">Pulgadas</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Medida registrada</span>
                                </div>
                                <input type="number" class="form-control" id="largo" name="largo" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Escanea tu codigo</span>
                                </div>
                                <input type="text" class="form-control" id="control" name="control" required>
                            </div>
                            <input type="hidden" name="code" id="code">
                            <input type="hidden" name="consec" id="consec">
                            <input type="hidden" name="work" id="work">
                            <input type="hidden" name="origlargo" id="origlargo">
                            <div class="col-md-2 mb-3"><input type="submit" value="Guardar" class="btn btn-primary"></div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    function Registros() {
        var codigo = document.getElementById("codigo").value;

        var url = "registros.php?codigo=" + codigo;
        fetch(url).then(response => response.json()).then(data => {
            if (data[0]) {

                document.getElementById("np").value = data[0];
                document.getElementById("cli").value = data[1];
                document.getElementById("rev").value = data[2];
                document.getElementById("wo").value = data[3];
                document.getElementById("cons").value = data[4];
                document.getElementById("datosCorte").value = data[7] + " " + data[6] + " " + data[5] + " " + data[14] + "MM(" + (data[14] / 25.4).toFixed(2) + "IN)";
                document.getElementById("estamp").value = data[15];
                document.getElementById("code").value = data[8];
                document.getElementById("consec").value = data[4];
                document.getElementById("work").value = data[3];
                document.getElementById("origlargo").value=data[14];
                document.getElementById("Datos").style.display = "none";
                document.getElementById("registros").style.display = "block";
            } else {
                alert("No se encontraron registros o ya fue registrado");
                window.location.href = "index.php";
            }
        });
    }
</script>