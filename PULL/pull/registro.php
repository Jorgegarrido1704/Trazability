
<?php 

session_start();
$user=$_SESSION['usuario'];
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de prueba de pull</title>
<link rel="stylesheet" type="text/css" href="estilo.css">

</head>
<body>
<small style="float:right"><a href="log_out.php" id="logout">Cerrar la sesión</a></small>
    <a href="graficas.php"><button>Graficas</button></a>
<script>

        function updateSecondSelect() {
            var firstSelect = document.getElementById("apply").value; 

            if (firstSelect === "emplame") {
                var labelSentence = "Numero de empalme";
                var label = document.createElement("label");
                label.textContent = labelSentence;

              

                
                var container = document.getElementById("inputContainer");
                container.innerHTML = ''; 
                container.appendChild(label);
                
            } else if (firstSelect !== "emplame"){
                var labelSentence = "Terminal";
                var label = document.createElement("label");
                label.textContent = labelSentence;

               

                
                var container = document.getElementById("inputContainer");
                container.innerHTML = ''; 
                container.appendChild(label);
                
            }
        }
        function validation() {
            var pressure = document.getElementById("presion").value;
            var pn = document.getElementById("num_part").value;
            var wo = document.getElementById("wo").value;
            var pers = document.getElementById("persona").value;
            var forma = document.getElementById("apply").value;
            var especif = document.getElementById("specific").value;
            var valid = document.getElementById("valid").value;


            if(pressure!="0" & pn!="" & wo!="" & pers!="" & forma!="" & especif!="" & valid!=""){
                return true; 
            }
                alert("Falta inforamcion por ingresar");
                return false;
        }

    </script>
    <div align="center">
        <h1>Registro prueba pull</h1>
    </div>
        <br>
    <div align="center">
        <form    method="POST" name="form" id="form" action="conection_bd.php" onsubmit = "return validation()" >
            <label id="calibre">Calibre</label>
         <select name="aws" id="aws">
            <option value="10">10</option>
            <option value="12">12</option>
            <option value="14">14</option>
            <option value="16">16</option>
            <option value="18">18</option>
            <option value="20">20</option>
            <option value="22">22</option>
            </select> 
            <br><br>
        <label >cliente</label>
        <select id="cliente" name="cliente">
                <option value="ultilimaster">Utilimaster</option>
                <option value="SHYFT">SHYFT</option>
                <option value="Tico">Tico</option>
                <option value="Mabe">Mabe</option>
                <option value="Plastic_omniun">Plastic omniun</option>
                <option value="Bergstrom">Bergstrom</option>
                <option value="California">California</option>
                <option value="Atlas">Atlas</option>
                <option value="Kalmar">Kalmar</option>
                <option value="Modine">Modine</option>
                <option value="Blue_bird">Blue_bird</option>
                <option value="Forest">Forest</option>
                <option value="Capacity">Capacity</option>
                <option value="Proterra">Proterra</option>
                <option value="Collins">Collins</option>
                <option value="Spartan">Spartan</option>
                <option value="Proterra_California">Proterra_California</option>
                <option value="Pantros">Pantros</option>
          </select>
             <br><br>
            <label >Numero de parte</label>
            <input type="text" id="num_part" name="num_part">
            <br><br>
            <label >Work Order</label>
            <input type="text" id="wo" name="wo">
            <br><br>
            <label >Nivel de presión (Lb)</label>
            <input type="number" id="presion" name="presion" step="0.01" value="0">       
          <br><br>
          <label>Personal que registra</label>
          <input type="text" name="persona" id="persona">
          <br><br>
          <label >Forma de aplicado</label>
          <select name="apply" id="apply" onchange="updateSecondSelect()">
            <option value=""></option>
            <option value="banco">Banco</option>
            <option value="pinza">Pinza</option>
            <option value="Canon">Cañon</option>
            <option value="corte">Corte</option>
            <option value="emplame">Empalme</option>
        </select>
        <br>
       <label id="inputContainer" name="inputContainer"></label>
        <input type="text" id="specific" name="specific"  >
        <br><br>
        <label >Validado por</label>
        <input type="text" id="valid" name="valid" value="<?php echo $user; ?>">
                <br><br>
     <input type="submit" value="enviar"  id="envio"  >
 </form> </div>

</body>
</html>

<?php ; ?>




