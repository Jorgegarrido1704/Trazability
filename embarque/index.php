<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>
  <style>
    .bien{
       background: green; 
  }
  .mal{
      background: red;
  }
  </style>
  <body id="body">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-5" id="Datos">
                <h2>Cotejo de arnes y cajas</h2>
                <form action="#" method="GET">
                    <div class="mb-3 mt-3">
                        <label for="partNumber" class="form-label">Numero de parte arnes</label>
                        <input type="text" class="form-control" id="partNumber" name="partNumber" required autofocus onchange="revisar()">
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="boxpn" class="form-label">Numero de parte caja </label>
                        <input type="text" class="form-control" id="boxpn" name="boxpn" required onchange="revisardatos()">
                    </div>
                    </form>
            </div>
            <div class="col-md-12 mt-5" id="registros" style="display: none; height: 650px width: 100%;">
                <div class="mb-3 mt-3" >
                    <h1 id="datosRegistro"></h1>
                </div>
            </div>
        </div>
    </div>
<script>
    function hablarTexto(texto) {
    if ('speechSynthesis' in window) {
        const mensaje = new SpeechSynthesisUtterance(texto);
        mensaje.lang = 'es-MX'; // español
        mensaje.rate = 1;       // velocidad normal
        mensaje.pitch = 1;      // tono normal

        window.speechSynthesis.cancel(); // evita duplicados
        window.speechSynthesis.speak(mensaje);
    }
}
    function revisar() {
        var partNumber = document.getElementById("partNumber").value;
       if (partNumber.length > 5) {
            document.getElementById("boxpn").focus();
       }
    }
    function revisardatos() {
    var boxpn = document.getElementById("boxpn").value;
    var partNumber = document.getElementById("partNumber").value;

    var registros = document.getElementById("registros");
    document.getElementById("Datos").style.display = "none";
    registros.style.display = "block";

    if (boxpn == partNumber) {
        registros.classList.add("bien");
        var texto = "Datos registrados correctamente";
        document.getElementById("datosRegistro").innerHTML = texto;
        hablarTexto(texto);
        interval(4000);
    } else {
        registros.classList.add("mal");
        var texto = "Datos no registrados, Favor de revisarlos";
        document.getElementById("datosRegistro").innerHTML = texto;
        hablarTexto(texto);
        interval(6000);
    }
}
    function interval(interval) {
        setTimeout(function() {
            window.location.href = "index.php";
        }, interval);
    }
    
   
</script>
  


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
 
</body>
</html>