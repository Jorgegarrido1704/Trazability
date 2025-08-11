
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
<div><button><small style="float:right"><a href="../log/logout.php" id="logout">Cerrar la sesión</a></small></button></div>
<small style="float:right"><button><a href="tabla.php" id="logout">tabla de registros</a></button><button><a href="http://192.168.10.132/Trazability/PULL/solicitar.php" id="logout">solicitar herrmanetal</a></button></small>

    
<script>

        function updateSecondSelect() {
            var firstSelect = document.getElementById("apply").value; // Get the selected value

            if (firstSelect === "emplame") {
                var labelSentence = "Numero de empalme";
                var label = document.createElement("label");
                label.textContent = labelSentence;

              

                // Append the label and input elements to the container
                var container = document.getElementById("inputContainer");
                container.innerHTML = ''; // Clear previous content
                container.appendChild(label);
                
            } else if (firstSelect !== "emplame"){
                var labelSentence = "Terminal";
                var label = document.createElement("label");
                label.textContent = labelSentence;

               

                // Append the label and input elements to the container
                var container = document.getElementById("inputContainer");
                container.innerHTML = ''; // Clear previous content
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
         <select name="aws" id="aws" required>
         <option value="6">6</option>
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
        <select id="cliente" name="cliente" required>
	<option value="" disabled>Seleciona el cliente</option>
    	<option value="PALFINGER">PALFINGER</option>
        <option value="MORGAN OLSON">MORGAN OLSON</option>
        <option value="JONH DEERE">JONH DEERE</option> 
        <option value="BERGSTROM EUROPE">BERGSTROM EUROPE</option>   
        <option value="OP MOVILITY">OP MOVILITY</option>
        <option value="BROWN">BROWN</option>
        <option value="DUR-A-LIFE">DUR-A-LIFE</option>
            <option value="Nilfisk">Nilfisk</option>
                <option value="ultilimaster">Utilimaster</option>
                <option value="SHYFT">SHYFT</option>
                <option value="Tico">Tico</option>
                <option value="Bergstrom">Bergstrom</option>
                <option value="California">California</option>
                <option value="Atlas">Atlas</option>
                <option value="Kalmar">Kalmar</option>
                <option value="Modine">Modine</option>
                <option value="Blue_bird">Blue_bird</option>
                <option value="Forest">Forest</option>
                <option value="Capacity">Capacity</option>
                <option value="Phoenix">Phoenix </option>
                <option value="Collins">Collins</option>
                <option value="Spartan">Spartan</option>
                <option value="Proterra_California">Proterra_California</option>
                
          </select>
             <br><br>
            <label >Numero de parte</label>
            <input type="text" id="num_part" name="num_part" required>
            <br><br>
            <label >Work Order</label>
            <input type="text" id="wo" name="wo" minlength="6" required>
            <br><br>
            <label >Nivel de presión (Lb)</label>
            <input type="number" id="presion" name="presion" step="0.01" value="0" required>       
          <br><br>
          <label>Personal que registra</label>
          <input type="text" name="persona" id="persona" required>
          <br><br>
          <label >Forma de aplicado</label>
          <select name="apply" id="apply" required onchange="updateSecondSelect()" >
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
        <span><b><?php echo $user; ?></b></span>
        <input type="hidden" id="valid" name="valid" value="<?php echo $user; ?>">
                <br><br>
     <input type="submit" value="enviar"  id="envio"  >
 </form> </div>

</body>
</html>






