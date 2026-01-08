<?php
$success = isset($_GET['success']) ? $_GET['success'] : "";
date_default_timezone_set("America/Mexico_City");
                            
$date= (date("H:i"));
                          //  echo $date;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="360">

    <title>Registros de tiempos de operaciones</title>
    <link rel=" stylesheet" href="../../asistencia/css/estilis.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body style="background-color: rgba(87, 150, 252, 0.47);">
    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <img src="../../asistencia/img/begstrsom.jpg" alt="Begstrsom" class="img-fluid" style="max-height: 350px; width: 100%;">
        </header>
        <div id="alert" style="display:none;" class="alert alert-success text-center" role="alert">
            <h2><strong> <?php echo $success; ?></strong></h2>
        </div>


        <div class="row">
            <div class="col-md-5 mt-2 mb-2" >
               <form action="registroTiempos.php" method="GET" class="mt-4" id="formQrTiempos">
                   <!-- <div class="form-group">
                        <label for="pn" class="form-label">Escanee su numero de parte</label>
                        <input type="text" class="form-control" id="pn" name="pn"  required autofocus >
                    </div>-->
                    <div class="form-group">
                        <label for="cardCode" class="form-label">Escanee el codigo QR de su tarjeta</label>
                        <input type="text" class="form-control" id="cardCode" name="cardCode" maxlength="5"   required autofocus onchange="revisar();">
                    </div>
                    <div class="form-group">
                        <label for="funcion" class="form-label">Proceso de trabajo</label>
                        <input type="text" class="form-control" id="funcion" name="funcion"   required onchange="tiempos()">
                    </div>
                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-primary">Registrar Tiempos</button>
                    </div>
               </form>
            </div>
             <div class="col-md-7 mt-2 mb-2" id="registros" maxheight="500px" style="overflow-y: scroll; border: 1px solid #a8b7e1ff;">
                <table class="table table-striped table-hover table-responsive" style="width: 100% ; max-height: 300px;"> 
                    <thead>
                        <tr>
                            <th scope="col">Operador</th>
                            <th scope="col">Numero de parte</th>  
                            <th scope="col">Inicio</th>
                            <th scope="col">Estatus</th>
                        </tr>
                    </thead>
                    <tbody id ="tableBodyTiempos"></tbody>
                </table>

            </div>
        </div>    

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
                crossorigin="anonymous"></script>
</body>

</html>
<script>
    function revisar() {
    var codigo = document.getElementById("cardCode").value;
    // if coode is have an i value continue to funcion input
   if (codigo.includes("i")) {
            document.getElementById("funcion").focus();
          }else{
            //screen reload
            location.reload();
          }

        }


function tiempos() {
    var funcion = document.getElementById("funcion").value;
  //  var pn = document.getElementById("pn").value;
    var codigo = document.getElementById("cardCode").value;

    if (funcion !== ""  && codigo !== "") {
        document.getElementById("formQrTiempos").submit();

          }
}

function registrosPrevios(){
    fetch('data.php')
    .then(response => response.json())
    .then(data => {
        const tableBody = document.getElementById('tableBodyTiempos');
        tableBody.innerHTML = '';

        data.forEach(row => {
            const tr = document.createElement('tr');

            const operadorTd = document.createElement('td');
            operadorTd.textContent = row.employeeNumber;
            tr.appendChild(operadorTd);

            const pnTd = document.createElement('td');
            pnTd.textContent = row.partnumber;
            tr.appendChild(pnTd);

            const inicioTd = document.createElement('td');
            inicioTd.textContent = row.initTime;
            tr.appendChild(inicioTd);

            const estatusTd = document.createElement('td');
            estatusTd.textContent = row.estatus;
            tr.appendChild(estatusTd);

            tableBody.appendChild(tr);
        });
    })
    .catch(error => console.error('Error fetching data:', error));
}
setInterval(registrosPrevios, 700);

</script>