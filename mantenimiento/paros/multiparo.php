<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="style.css">
   
   

        
        <title>Paro Mantenimiento</title>
    </head>
    <body >
<div align="center">
    <img src="img/fth.jpg" alt="" width="230px" height="300px">
</div>

        <div align="center"> 
        <h1 >Paro por Mantenimiento</h1>
            
            
        </div>
        <table>
            <thead>
                <tr>
                   
                    <th>Equipo</th>
                    <th>Nombre del equipo dañado O mantenimiento</th>
                    <th>Daño</th>
                    <th>Quien solicita</th>
                    <th>Area</th>
                    <th>guardar datos</th>
                 </tr>
            </thead>
            <tbody> 
            <tr>               
      <form action="conectio_bd.php" method="POST" name="registro" id="form">
       
       
       <td align="center"><select name="equipo" id="equipo" onchange="updateSecondSelect()" >
      <option selected="selected"> </option>
     <option value="Maquinas"  >Maquinas de corte</option>
     <option value="Bancos para terminales">Bancos para terminales</option>
     <option value="Empalmadora">Empalmadora</option>
      <option value="Pull">Pull</option>
      <option value="otro">Otro</option>
     </select></td>
                    <td align="center">
                       <input type="text" name="nom_equipo" id="nom_equipo">
                    <td align="center">
                        <select name="dano" id="dano"   > </select>
                    </td>
                    <td align="center">
                        <input type="text" id="espec" name="espec">
                    </td>
                    <td><select name="area" id="area">
                        <option value=""></option>
                        <option value="Saul_tab">Tableros Saul</option>
                        <option value="David_tab">Tableros David</option>
                        <option value="Angel_lib">Liberacion</option>
                        <option value="Fernando_cort">Corte</option>
                        <option value="Andrea_alm">Alamacen</option>
                        <option value="Brandon_tab">Tableros Brandon</option>
                        <option value="Alejandra_tab">Tableros Alejenadra</option>
                        <option value="Zamarripa_tab">Tableros Zamarripa</option>
                        <option value="Miguel_tab">Loom</option>
        
                    </select></td>
                
                       <td align="center"><button type="submit" value="save" id="guardar" name="guardar"  >Guardar</button> </td>
                       <input type="hidden" id="timeInput" name="time" value="">
</tr>
                </form>
            </tbody>
        </table>
        
        <br><br><br><br><br><br><br>
        <div>
            
        </div>






    <script>
    


    function updateSecondSelect() {
        var firstSelect = document.getElementById("equipo");
        var secondSelect = document.getElementById("dano");
      
        // Clear existing options
        secondSelect.innerHTML = "";

        // Populate second select based on the selected option from the first select
        if (firstSelect.value === "Maquinas") {
            
            var nulo = new Option("","");
            var option1 = new Option("Impresora", "Impresora");
            var option2 = new Option("Banda", "Banda");
            var option_3 = new Option("Falla electrica", "Falla electrica");
            secondSelect.appendChild(nulo);
            secondSelect.appendChild(option1);
            secondSelect.appendChild(option2);
            secondSelect.appendChild(option_3);
           
        } else if (firstSelect.value === "Bancos para terminales") {
            var option3 = new Option("Cambio de aplicador", "Cambio de aplicador");
            var option4 = new Option("Falla Electrica", "Falla electrica");
            var option_a = new Option("Ajuste de aplicador", "Ajuste de aplicador");
            var option_b = new Option("Ajuste de presión", "Ajuste de presión");
            secondSelect.appendChild(option4);
            secondSelect.appendChild(option3);
            secondSelect.appendChild(option_a);
            secondSelect.appendChild(option_b);
        } else if (firstSelect.value === "Empalmadora") {
            var option5 = new Option("Falla electrica", "Falla electrica");
            var option6 = new Option("Error en maquina", "Error en maquina");
            secondSelect.appendChild(option5);
            secondSelect.appendChild(option6);
        }
     else if (firstSelect.value === "Pull") {
            var optionz1 = new Option("calibración", "Calibración");
            var optionz2 = new Option("Falla Mayor", "Falla Mayor");
            secondSelect.appendChild(optionz1);
            secondSelect.appendChild(optionz2);
    
         }    }
    // Initial population of the second select based on the default value of the first select
    updateSecondSelect();
</script>

<script>
    function updatethirdSelect() {
        
    var secondSelect = document.getElementById("dano");
    var thirdSelect = document.getElementById("espec");
        // Clear existing options
        thirdSelect.innerHTML = "";
        if( secondSelect.value ==="Impresora"){
            var nulo = new Option("","");
                var option1 = new Option("Tinta tapada","Tinta tapada");
                var option2 = new Option("Falta Tinta","Falta Tinta");
                thirdSelect.appendChild(nulo);
                thirdSelect.appendChild(option1);
                thirdSelect.appendChild(option2);
            }else if(secondSelect.value ==="Banda"){
                var optiona = new Option("No gira","No gira");
                thirdSelect.appendChild(optiona)  
             }else if( secondSelect.value ==="Cambio de aplicador"){
                var nulo = new Option("","");
                var option1_2 = new Option("DT1-27","DT1-27");
                var option2_2 = new Option("TT2-235","TT2-235");
                thirdSelect.appendChild(nulo);
                thirdSelect.appendChild(option1_2);
                thirdSelect.appendChild(option2_2);
            }else if( secondSelect.value ==="Ajuste de aplicador"){
                var optiona1 = new Option("Terminal girada","Terminal girada");
                thirdSelect.appendChild(optiona1);
            }else if( secondSelect.value ==="Error en maquina"){
                var nulo = new Option("","");
                var option_a= new Option("Sistema no funciona","Sistema no funciona");
                thirdSelect.appendChild(nulo);
                thirdSelect.appendChild(option_a);

            }
            
            else{
                var nulo= new Option("","");
                thirdSelect.appendChild(nulo);
            }}
            updatethirdSelect();

      
        function generateForms() {
            var veces = parseInt(document.getElementById('cuantos').value);
            var formContainer = document.getElementById('formContainer');
            formContainer.innerHTML = ''; // Clear existing content

            for (var i = 0; i < veces; i++) {
                var form = document.createElement('form');
                form.action = 'conection_bd.php';
                form.method = 'POST';
                form.name = 'registro';

                // Create and append other form elements here

                formContainer.appendChild(form);
            }
        }
    
              
</script>

<div class="table" id="table" align="center">
    <button><a href="tabla.php">Registros</a></button>
    </div>

</body>


</html>