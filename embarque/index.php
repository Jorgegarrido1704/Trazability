<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Embarque - Control de Calidad</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    <style>
        #etiquetas { display: none; }
        .etiqueta-container {
            width: 114mm;
            height: 45mm;
            padding: 5mm;
            border: 1px solid #eee;
            background: white;
            margin: 0 auto;
        }
        #customer { font-size: 24px; font-weight: bold; margin: 0; }
        #partNumber { font-size: 20px; font-weight: bold; margin: 0; }
        #partNumberQty { font-size: 18px; margin: 0; }
        #qr { width: 35mm; height: 35mm; display: flex; justify-content: center; align-items: center; }
        #qr img { max-width: 100%; height: auto; }

        @media print {
            body * { visibility: hidden; }
            #etiquetas, #etiquetas * { visibility: visible; }
            #etiquetas { position: absolute; left: 0; top: 0; display: block !important; width: 114mm; }
            #embarque { display: none !important; }
            @page { size: 114mm 45mm; margin: 0; }
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-4" id="embarque">
        <div class="card shadow-sm p-4">
            <h1 class="mb-4">Embarque por Lotes</h1>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="np" class="form-label fw-bold">Número de Parte Maestro</label>
                    <input type="text" id="np" class="form-control form-control-lg" placeholder="Escanee NP..." autofocus onchange="addQty()">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="qty" class="form-label fw-bold">Cantidad Total</label>
                    <input type="number" id="qty" class="form-control form-control-lg" onchange="registroDePiezas()">
                </div>
            </div>

            <div id="status-area" class="mt-3">
                <p id="error-msg" class="text-danger fw-bold" style="display: none;"></p>
                <button id="btn-imprimir" class="btn btn-success btn-lg w-100" style="display: none;" onclick="imprimirEtiqueta()">
                    Imprimir Etiqueta con QR Agrupado
                </button>
            </div>

            <hr>
            <div class="row" id="piezas"></div>
        </div>
    </div>

    <div id="etiquetas">
        <div class="etiqueta-container">
            <div class="row align-items-center">
                <div class="col-8">
                    <p id="customer"><span id="cliente">CLIENTE</span></p>
                    <p id="partNumber"><span id="npE">P/N</span> REV <span id="rev">00</span></p>
                    <p id="partNumberQty">Cantidad Total: <span id="cantidad">0</span></p>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <div id="qr"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addQty() {
            let np = document.getElementById("np").value;
            if(!np) return;
            fetch("datos.php?np=" + encodeURIComponent(np))
                .then(response => response.json())
                .then(data => {
                    if(data[0]){
                        document.getElementById("cliente").innerText = data[0][1];
                        document.getElementById("rev").innerText = data[0][2];
                        registroDePiezas();
                    } else { alert("NP no encontrado"); }
                }).catch(error => console.error(error));
        }

        function registroDePiezas() {
            let qty = parseInt(document.getElementById("qty").value);
            let container = document.getElementById("piezas");
            container.innerHTML = "";
            document.getElementById("btn-imprimir").style.display = "none";
            if (isNaN(qty) || qty <= 0) return;

            for (let i = 0; i < qty; i++) {
                let div = document.createElement("div");
                div.className = "col-md-3 mb-2";
                div.innerHTML = `<input type="text" class="form-control input-pieza" placeholder="Pieza ${i + 1}" oninput="validarTodo()">`;
                container.appendChild(div);
            }
        }

        function validarTodo() {
            let npMaestro = document.getElementById("np").value;
            let inputs = document.querySelectorAll(".input-pieza");
            let btnImprimir = document.getElementById("btn-imprimir");
            
            let conteoValido = 0;
            let errores = 0;
            let itemsParaQR = [];

            // 1. Validar colores y contar
            inputs.forEach(input => {
                let valor = input.value.trim();
                if (valor !== "") {
                    if (valor === npMaestro) {
                        input.style.backgroundColor = "#d4edda";
                        conteoValido++;
                        itemsParaQR.push(valor);
                    } else {
                        input.style.backgroundColor = "#f8d7da";
                        errores++;
                    }
                }
            });

            // 2. Lógica de AGRUPACIÓN (Ej: 12 piezas -> NP*10-NP*2-)
            let stringQR = "";
            if (itemsParaQR.length > 0) {
                let total = itemsParaQR.length;
                let npActual = itemsParaQR[0];
                
                let paquetesDe10 = Math.floor(total / 10);
                let residuo = total % 10;

                // Añadir grupos de 10
                for(let i=0; i < paquetesDe10; i++) {
                    stringQR += `${npActual}*10-`;
                }
                // Añadir el resto si existe
                if(residuo > 0) {
                    stringQR += `${npActual}*${residuo}-`;
                }
            }

            // 3. Generar QR
            const qrContainer = document.getElementById("qr");
            qrContainer.innerHTML = "";
            if (stringQR !== "") {
                new QRCode(qrContainer, {
                    text: stringQR,
                    width: 130, height: 130,
                    correctLevel: QRCode.CorrectLevel.M
                });
            }

            // 4. Mostrar botón si terminó
            if (conteoValido === inputs.length && inputs.length > 0 && errores === 0) {
                btnImprimir.style.display = "block";
            } else {
                btnImprimir.style.display = "none";
            }
        }

        function imprimirEtiqueta(){
            document.getElementById("npE").innerText = document.getElementById("np").value;
            document.getElementById("cantidad").innerText = document.getElementById("qty").value;
            window.print();
            setTimeout(() => { location.reload(); }, 500);
        }
    </script>
</body>
</html>