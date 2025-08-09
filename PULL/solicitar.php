<?php 
    require "app.php";  // Ensure the correct path to your connection file
    session_start();
    date_default_timezone_set('America/Mexico_City');
?> 
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Requerimiento Herramental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div><small><button><a href="registro.php">registro pull</a></button></small></div>
    <div align="center"><h1>Introduzca los Datos para solicitar aplicador</h1></div>
    
      <div class="input-group mb-6">
        <span class="input-group-text" id="basic-addon1">Terminal A solicitar</span>
        <select class="form-control" id="aplicador" aria-label="Username" aria-describedby="basic-addon1" required>
          <option value="">  </option>
          <?php 
          $busqueda = mysqli_query($con, "SELECT  DISTINCT terminal FROM mant_golpes_diarios ORDER BY terminal DESC");
          while ($row = mysqli_fetch_array($busqueda)) { ?> 
            <option value="<?php echo $row['terminal']; ?>"><?php echo $row['terminal']; ?></option>
          <?php } ?>
          <option value="No esta(preguntar y agregar)">No esta en la lista</option>
        </select>
      </div>
      <br>
      <div class="input-group mb-3">
        <span class="input-group-text" id="maq">Maquina para aplicar</span>
        <select class="form-control" id="maquina" aria-label="Username" aria-describedby="basic-addon1" required>
          <option value="">  </option>
          <option value="maquina">maquina</option>
        </select>
      </div>
      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text" id="basic-addon3">Trabajo solicitado</span>
          <select type="text" class="form-control" id="trabajo" aria-describedby="basic-addon3 basic-addon4" required>
            <option value=""> </option>
            <option value="Cambio de aplicador">Cambio de aplicador</option>
            <option value="Ajuste de presion">Ajuste de presion</option>
          </select>
        </div>
        <div class="form-text" id="basic-addon4">Ejemplo: Cambio de aplicador</div>
      </div>
      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text" id="basic-addon3">Operador que solicita</span>
          <input type="text" class="form-control" id="quien" aria-describedby="basic-addon3 basic-addon4" required>
        </div>
        <div class="form-text" id="basic-addon4">Ejemplo: Juan Ornelas</div>
      </div>
      <div align="center">
      <form id="aplicadorForm" method="GET" action="app.php">
        <input type="hidden" name="aplicador1" id="aplicador1">
        <input type="hidden" name="maquina1" id="maquina1">
        <input type="hidden" name="trabajo1" id="trabajo1">
        <input type="hidden" name="quien1" id="quien1">
        <button type="button" class="btn btn-primary" onclick="submitForm()">Solicitar</button>
        </form>
      </div>
    
    <div id="terminal"></div>
    
    <script>
      function submitForm() {
        var aplicador = document.getElementById('aplicador').value.trim();
        var maquina = document.getElementById('maquina').value.trim();
        var trabajo = document.getElementById('trabajo').value.trim();
        var quien = document.getElementById('quien').value.trim();

        if (aplicador === "" || maquina === "" || trabajo === "" || quien === "") {
          alert("Por favor, complete todos los campos.");
          return false;
        }else{
         console.log(aplicador, maquina, trabajo, quien);
         document.getElementById("aplicador1").value = aplicador;
         document.getElementById("maquina1").value = maquina;
         document.getElementById("trabajo1").value = trabajo;
         document.getElementById("quien1").value = quien;
         document.getElementById("aplicadorForm").submit();
        }
        
    }

        
    </script>
  </body>
</html>
