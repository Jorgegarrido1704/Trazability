<?php
 require "../Registros/index.php";
    $corte= isset($_GET['corte'])?$_GET['corte']:"";
    $corte = explode(";", $corte);
    $creador= $corte[0];
    $cliente= $corte[1];
    $pn= $corte[2];
    $rev= $corte[3];
    
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario para Registrar Datos</title>
    <!-- Link to Bootstrap CSS (from a CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .input-row {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Consecutivos Normales</h1>

        <!-- Formulario de entrada de datos -->
        <form id="dataForm">
            <div id="inputRows">
                <!-- Fila de entradas de datos -->

                <div class="input-row row" id="row1">
                    <div class="col-md-2">
                        <label for="creador" class="form-label">creador lista:</label>
                        <input type="text" name="creador" id="creador" class="form-control" value="<?php echo $creador; ?>" readonly><br>
                    </div>
                    <div class="col-md-4">
                        <label for="cliente" class="form-label">Cliente:</label>
                        <input type="text" name="cliente" id="cliente" class="form-control" value="<?php echo $cliente; ?>" readonly><br>
                    </div>
                    <div class="col-md-4">
                        <label for="pn" class="form-label">Numero de parte:</label>
                        <input type="text" name="pn" id="pn" class="form-control" value="<?php echo $pn; ?>"  readonly><br>
                    </div>
                    <div class="col-md-2">
                        <label for="rev" class="form-label">REV:</label>
                        <input type="text" name="rev" id="rev" class="form-control" value="<?php echo $rev; ?>" readonly><br>
                    </div>
                  
                
                    <div class="col-md-1">
                        <label for="corte1" class="form-label">Corte:</label>
                        <input type="text" name="corte" id="corte1" class="form-control" value ="-" ><br>
                    </div>
                    <div class="col-md-1">
                        <label for="cons1" class="form-label">Cons:</label>
                        <input type="number" name="cons" id="cons1" class="form-control" required><br>
                    </div>
                    <div class="col-md-1">
                        <label for="tipo" class="form-label">Tipo:</label>
                        <select name="tipo" id="tipo" class="form-control" required>
                            <option value="" disabled selected>cable</option>
                            <option value="GXL">gxl</option>
                            <option value="TXL">txl</option>
                            <option value="SGX">sgx</option>
                        </select>
                        
                    </div>
                    <div class="col-md-1">
                        <label for="calibre1" class="form-label">Calibre:</label>
                        <input type="number" name="calibre" id="calibre1" class="form-control" required><br>
                    </div>
                    <div class="col-md-1">
                        <label for="color1" class="form-label">Color:</label>
                        <input type="text" name="color" id="color1" class="form-control" step="any" required><br>
                    </div>
                    <div class="col-md-1">
                        <label for="longuitud1" class="form-label">Longitud:</label>
                        <input type="number" name="longuitud" id="longuitud1" class="form-control" step="any" required><br>
                    </div>
                    <div class="col-md-2">
                        <label for="term1_1" class="form-label">Terminal 1:</label>
                        <textarea name="term1" id="term1_1" class="form-control" required></textarea><br>
                    </div>
                    <div class="col-md-2">
                        <label for="term2_1" class="form-label">Terminal 2:</label>
                        <textarea name="term2" id="term2_1" class="form-control" required></textarea><br>
                    </div>
                    <div class="col-md-2">
                        <label for="estamp1" class="form-label">Estampado:</label>
                        <input type="text" name="estamp" id="estamp1" class="form-control" step="any" required><br>
                    </div>
                
                    <div class="col-md-2">
                        <label for="fromPos1" class="form-label">From:</label>
                        <input type="text" name="fromPos" id="fromPos1" class="form-control" required><br>
                    </div>
                    <div class="col-md-2">
                        <label for="toPos1" class="form-label">To:</label>
                        <input type="text" name="toPos" id="toPos1" class="form-control" required><br>
                    </div>
                    <div class="col-md-5">
                        <label for="comment1" class="form-label">Comentarios:</label>
                        <input type="text" name="comment" id="comment1" class="form-control" ><br>
                    </div>
                    <div class="col-md-2">
                        <label for="categoria1" class="form-label">Categoria:</label>
                        <select name="categoria" id="categoria1" class="form-control" required>
                            <option value="" disabled selected>tipo de consecutivo</option>
                            <option value="1">Normal</option>
                            <option value="2">Trenzado</option>
                            <option value="3">Jumper</option>   
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Button to add another row -->
            <button type="button" id="addRowButton" class="btn btn-secondary mb-3">Añadir otra fila</button><br>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">Guardar Registro</button>
        </form>   
    </div>

    <!-- Bootstrap JS and its dependencies (Popper.js and Bootstrap.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
// Also trigger the function on page load to show the data of the selected category

document.getElementById("dataForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evitar el envío del formulario tradicional
   
    const creadorInput = document.getElementById("creador").value;
    const clienteInput = document.getElementById("cliente").value;
    const pnInput = document.getElementById("pn").value;
    const revInput = document.getElementById("rev").value;

    // Validate creador field
    if (!creadorInput) {
        alert('El campo creador es obligatorio');
        return; // Stop form submission if 'creador' is empty
    }
    
    const inputRows = document.querySelectorAll(".input-row");
    const newData = [];

    inputRows.forEach((row) => {
        const corteInput = row.querySelector("[name='corte']");
        const consInput = row.querySelector("[name='cons']");
        const tipoInput = row.querySelector("[name='tipo']");
        const calibreInput = row.querySelector("[name='calibre']");
        const colorInput = row.querySelector("[name='color']");
        const longitudInput = row.querySelector("[name='longuitud']");
        const term1Input = row.querySelector("[name='term1']");
        const term2Input = row.querySelector("[name='term2']");
        const estampInput = row.querySelector("[name='estamp']");
        const fromPosInput = row.querySelector("[name='fromPos']");
        const toPosInput = row.querySelector("[name='toPos']");
        const commentInput = row.querySelector("[name='comment']");
        const categoriaInput = row.querySelector("[name='categoria']");

        // Check if required fields exist and are filled
        if (corteInput && consInput && tipoInput && calibreInput && colorInput && longitudInput && term1Input && term2Input && estampInput && fromPosInput && toPosInput && commentInput && categoriaInput) {
            const data = {
                creador: creadorInput,
                cliente: clienteInput,
                pn: pnInput,
                rev: revInput,
                corte: corteInput.value,
                cons: consInput.value,
                tipo: tipoInput.value,
                calibre: calibreInput.value,
                color: colorInput.value,
                longitud: parseFloat(longitudInput.value),
                term1: term1Input.value,
                term2: term2Input.value,
                estamp: estampInput.value,
                fromPos: fromPosInput.value,
                toPos: toPosInput.value,
                comment: commentInput.value,
                categoria: categoriaInput.value,
            };

            newData.push(data);
        }
    });

    if (newData.length > 0) {
        // Send the data to the server
        fetch('../app/registrolista.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(newData)
        })
        .then(response => response.text())  // Get the response as text
        .then(data => {
            try {
                // Try to parse the response as JSON
                const jsonResponse = JSON.parse(data);
                if (jsonResponse.status === 'success') {
                    alert(jsonResponse.message);
                    document.getElementById("dataForm").reset();
                //    displayData(jsonResponse); // If needed, show the response
                } else {
                    alert(jsonResponse.message);
                }
            } catch (e) {
                console.error('Error parsing response:', e);
                console.log('Raw response:', data);
                alert('There was an error with the response from the server. Please check the server log.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error while saving the data');
        });
    } else {
        alert('Por favor complete todos los campos');
    }
});

    

// Agregar una nueva fila de entradas cuando se hace clic en el botón "Añadir otra fila"
document.getElementById("addRowButton").addEventListener("click", function() {
    const inputRowsContainer = document.getElementById("inputRows");

    // Contar el número de filas para asignar un id único a cada nueva fila
    const rowCount = inputRowsContainer.children.length + 1; 

    const newRow = document.createElement("div");
    newRow.classList.add("input-row", "row");
    newRow.id = `row${rowCount}`; // Generar id único para la nueva fila

    newRow.innerHTML = `
        <hr>
        <div class="col-md-1">
            <label for="corte${rowCount}" class="form-label">Corte:</label>
            <input type="text" name="corte" id="corte${rowCount}" class="form-control" value ="-"><br>
        </div>
        <div class="col-md-1">
            <label for="cons${rowCount}" class="form-label">Cons:</label>
            <input type="number" name="cons" id="cons${rowCount}" class="form-control" required><br>
        </div>
        <div class="col-md-1">
            <label for="tipo${rowCount}" class="form-label">Tipo:</label>
             <select name="tipo" id="tipo${rowCount}" class="form-control" required>
                            <option value="" disabled selected>cable</option>
                            <option value="GXL">gxl</option>
                            <option value="TXL">txl</option>
                            <option value="SGX">sgx</option>
            </select>
        </div>
        </div>
        <div class="col-md-1">
            <label for="calibre${rowCount}" class="form-label">Calibre:</label>
            <input type="number" name="calibre" id="calibre${rowCount}" class="form-control" required><br>
        </div>
        <div class="col-md-1">
            <label for="color${rowCount}" class="form-label">Color:</label>
            <input type="text" name="color" id="color${rowCount}" class="form-control" step="any" required><br>
        </div>
        <div class="col-md-1">
            <label for="longuitud${rowCount}" class="form-label">Longitud:</label>
            <input type="number" name="longuitud" id="longuitud${rowCount}" class="form-control" step="any" required><br>
        </div>
        <div class="col-md-2">
            <label for="term1_${rowCount}" class="form-label">Terminal 1:</label>
            <textarea name="term1" id="term1_${rowCount}" class="form-control" required></textarea><br>
        </div>
        <div class="col-md-2">
            <label for="term2_${rowCount}" class="form-label">Terminal 2:</label>
            <textarea name="term2" id="term2_${rowCount}" class="form-control" required></textarea><br>
        </div>
        <div class="col-md-2">
            <label for="estamp${rowCount}" class="form-label">Estampado:</label>
            <input type="text" name="estamp" id="estamp${rowCount}" class="form-control" step="any" required><br>
        </div>
        <div class="col-md-2">
            <label for="fromPos${rowCount}" class="form-label">From:</label>
            <input type="text" name="fromPos" id="fromPos${rowCount}" class="form-control" required><br>
        </div>
        <div class="col-md-2">
            <label for="toPos${rowCount}" class="form-label">To:</label>
            <input type="text" name="toPos" id="toPos${rowCount}" class="form-control" required><br>
        </div>
        <div class="col-md-5">
            <label for="comment${rowCount}" class="form-label">Comentarios:</label>
            <input type="text" name="comment" id="comment${rowCount}" class="form-control"><br>
        </div>
        <div class="col-md-2">
            <label for="categoria${rowCount}" class="form-label">Categoria:</label>
            <select name="categoria" id="categoria${rowCount}" class="form-control" required>
                <option value="" disabled selected>tipo de consecutivo</option>
                <option value="1">Normal</option>
                <option value="2">Trenzado</option>
                <option value="3">Jumper</option>   
            </select>
    `;

    inputRowsContainer.appendChild(newRow);
});

    </script>
</body>
</html>
