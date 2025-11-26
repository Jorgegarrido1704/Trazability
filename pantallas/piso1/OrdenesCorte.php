<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listas de cortes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<style>
body {
    background-color: rgba(87, 230, 252, 0.47);
}

#tableBody {
    scroll-behavior: smooth;
    overflow-y: scroll;
    height: 400px;
}
</style>

<body>
    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 border-bottom">
            <img src="img/begstrsom.jpg" alt="Begstrsom" class="img-fluid" style="max-height: 70px; width: 30%;">
        </header>
        <div class="row">
            <div class="col-md-12 " id="infor">
                <table class="table table-bordered table-striped table-hover table-responsive"
                    style="width: 100%; height: auto;">
                    <thaed>
                        <tr>
                            <th class="text-center">Zona</th>
                            <th class="text-center">Lider</th>

                        </tr>
                    </thaed>
                    <tbody>
                        <tr>
                            <td class="text-center"><span id="zona"></span></td>
                            <td class="text-center"><span id="lider"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 mt-2 mb-2 " id="registros">
                <div class="form-group text-center  flex-column">
                    <table class="table table-bordered table-striped table-hover table-responsive"
                        style="width: 100% ; max-height: 300px;">
                        <thead>
                            <tr>
                                <th class="text-center">Cliente</th>
                                <th class="text-center">Número de parte</th>
                                <th class="text-center">Order de trabajo</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Fecha solicitada</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider" id="tableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>
</body>

</html>
<script>
function scrollToBottom() {
    // Scroll down by 200px from current position
    window.scrollBy({
        top: 10,
        left: 0,
        behavior: 'smooth'
    });
}

function timerPermin() {

    fetch('registrosPrevios.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('zona').innerText = data.zona;
            document.getElementById('lider').innerText = data.lider;
            document.getElementById('tableBody').innerHTML = data.tableRows;
        })
        .catch(error => console.error('Error fetching data:', error));
}
window.onload = timerPermin;
setInterval(scrollToBottom, 330);
setInterval(timerPermin, 10000);
</script>