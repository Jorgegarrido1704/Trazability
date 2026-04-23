<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Embarque - Control de Calidad Piezas por caja</title>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
</head>

<body id="body" class="bg-light">
    <style>
        #etiquetas {
            display: none;
            
           
        }
        #etiqueta  {
            width: 114mm;
            height: 30mm;
           
        }
        #separtador {
             width: 114mm;
            height: 15mm;            
        }
        #datosGenerales {
            margin: 1px;
             width: 114mm;
            height: 15mm; 
        }
        #customer{
            margin: 1px;
            font-size: 30px;
            font-weight: bold;
        }
        #partNumber{
            margin: 1px;
            font-size: 24px;
            font-weight: bold;
        }
        #partNumberQty{
            margin: 1px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>

    <div class="container mt-4" id="embarque">
        <div class="card shadow-sm p-4">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-4">Embarque por caja</h1>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="np" class="form-label fw-bold">Número de Parte Maestro</label>
                    <input type="text" name="np" id="np" class="form-control form-control-lg" placeholder="Escanee NP..." autofocus required onchange="addQty()">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="qty" class="form-label fw-bold">Cantidad de piezas</label>
                    <input type="number" name="qty" id="qty" class="form-control form-control-lg" required onchange="registroDePiezas()">
                </div>
            </div>

            <div id="status-area" class="mt-3">
                <p id="error-msg" class="text-danger fw-bold" style="display: none;"></p>
                <button id="btn-imprimir" class="btn btn-success btn-lg w-100" style="display: none;" onclick="imprimirEtiqueta()">
                    <i class="bi bi-printer"></i> Imprimir Etiquetas
                </button>
            </div>

            <hr>

            <div class="row" id="piezas"></div>
        </div>
    </div>
    <div class="container mt-4" id ="etiquetas" >
       
        <div class="etiqueta" id="etiqueta">
            <div id="separtador"></div>
            <div id="datosGenerales">
                <div class="row">
                    <div class="col-8 ml-3">
                        <p id="customer">  <strong><span id="cliente"></span> </strong></p>
                        <p id="partNumber">  <strong><span id="npE"></span> REV <span id="rev"></span></strong></p>
                        <p id="partNumberQty">  <strong>Cantidad:<span id="cantidad"></span> </strong></p>
                    </div>
                    <div class="col-4">
                        <div class="image-container text-center mt-3" id="qr"></div>
                    </div>
                </div>
            </div>    
            
        </div>
    </div>

    <script>
        function addQty() {
           document.getElementById("qty").value=0;
           let np = document.getElementById("np").value;
           const url = "datos.php?np=" + np;
           fetch(url)
            
           .then(response => response.json())
           .then(data => {
               if(data[0]){
                  
                   document.getElementById("cliente").innerText = data[0][1];
                   document.getElementById("rev").innerText = data[0][2];
                   console.log(data);
               }else{
                   alert("Numero de parte no encontrado");
               }
           
           })
           .catch(error => console.error(error));
           


           
           registroDePiezas();
        }
        function registroDePiezas() {
            let qty = parseInt(document.getElementById("qty").value);
            let container = document.getElementById("piezas");
            
            container.innerHTML = ""; // Limpiar previos
            ocultarFeedback();

            if (isNaN(qty) || qty <= 0) return;

            for (let i = 0; i < qty; i++) {
                let div = document.createElement("div");
                div.className = "col-md-4 mb-2";

                let input = document.createElement("input");
                input.type = "text";
                input.id = `pieza_${i}`;
                input.className = "form-control input-pieza";
                input.placeholder = `Escanear pieza ${i + 1}`;
                
                // Ejecutar validación cada vez que se escriba
                input.oninput = validarTodo;

                div.appendChild(input);
                container.appendChild(div);
            }
        }

        function validarTodo() {
            let npMaestro = document.getElementById("np").value;
            let inputs = document.querySelectorAll(".input-pieza");
            let btnImprimir = document.getElementById("btn-imprimir");
            let errorMsg = document.getElementById("error-msg");
            document.getElementById("qr").innerHTML = "";
            let qr="";
            
            let errores = 0;
            let completados = 0;

            inputs.forEach(input => {
                let valor = input.value.trim();
                
                if (valor === "") {
                    input.style.backgroundColor = "white";
                } else if (valor === npMaestro) {
                    input.style.backgroundColor = "#d4edda"; // Verde
                    completados++;
                } else {
                    input.style.backgroundColor = "#f8d7da"; // Rojo
                    errores++;
                }
                if(qr=="")
                    {
                        qr=valor;
                    }else{
                        qr=qr+"-"+valor;
                    }               
            });
            // only one qrcode
            let qrCode = new QRCode(document.getElementById("qr"), {
                text: qr,
                width: 90,
                height: 90,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            })
           

           
            if (completados === inputs.length && inputs.length > 0) {
                btnImprimir.style.display = "block";
                errorMsg.style.display = "none";
            } else {
                btnImprimir.style.display = "none";
                if (errores > 0) {
                    errorMsg.innerText = `⚠️ Atención: Hay ${errores} pieza(s) con número de parte incorrecto.`;
                    errorMsg.style.display = "block";
                } else {
                    errorMsg.style.display = "none";
                }
            }
        }

        function ocultarFeedback() {
            document.getElementById("btn-imprimir").style.display = "none";
            document.getElementById("error-msg").style.display = "none";
        }
        function imprimirEtiqueta(){
        let embarque = document.getElementById("embarque");
        let etiquetas = document.getElementById("etiquetas");
        let np = document.getElementById("np").value;
        let qty = document.getElementById("qty").value;
        let npE = document.getElementById("npE").innerText = document.getElementById("np").value;
        let cantidad = document.getElementById("cantidad").innerText = qty;
        embarque.style.display = "none";
        etiquetas.style.display = "block";
        window.print();
        setTimeout(() => {
            window.location.reload();
        }, 400);

        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>