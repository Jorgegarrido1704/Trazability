
<?php 

session_start();
$user=$_SESSION['usuario']??"Sergio";
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de prueba de pull</title>
<link rel="stylesheet" type="text/css" href="estilo.css">

</head>
<body>
<!--<div><button><small style="float:right"><a href="../log/logout.php" id="logout">Cerrar la sesión</a></small></button></div> -->
<small style="float:right">
    <!--<button><a href="tabla.php" id="logout">tabla de registros</a></button>-->
    <button><a href="http://192.168.10.132/Trazability/PULL/solicitar.php" id="logout">solicitar herrmanetal</a></button></small>

    
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
        <form    method="POST" name="form" id="form" action="conection_bd.php"  >
            <label >Work Order</label>
            <input type="text" id="wo" name="wo" minlength="6" required  onfocus onchange="BuscarInformacion(this.value);" >
            <br><br>
           
        <label >cliente</label>
        <input type="text" id="cliente" name="cliente" readonly>
             <br><br>
            <label >Numero de parte</label>
            <input type="text" id="num_part" name="num_part" readonly>
            <br><br>
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
            <option value="24">24</option>
            <option value="26">26</option>
            <option value="28">28</option>
            <option value="30">30</option>
            </select> 
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
       <label id="inputContainer" name="inputContainer">Terminal</label>
        <input type="text" id="specific" name="specific"  >
        <br><br>
        <label >Validado por</label>
        <span><b>Sergio</b></span>
        <input type="hidden" id="valid" name="valid" value="Sergio">
               
     <input type="submit" value="enviar"  id="envio"  >
 </form> </div>

</body>
</html>

<script>
    const wo = document.getElementById("wo");
    function BuscarInformacion(wo){
        //url=`http://http://mxloficina.corp.internal.bergstrominc.com/Trazability/PULL/consultar.php?wo=${wo}`;
        url=(`consultar.php?wo=${wo}`)
        fetch(url).then(response => response.json()).then(data => {
            if(data[0]){
                document.getElementById("num_part").value = data[0];
                document.getElementById("cliente").value = data[1];
            }else{
                console.log("No data found");
            }
        });
    }
    </script>


z

