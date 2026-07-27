

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materialistas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        /* Configuración de página para etiquetas */
        @page {
            size: 104mm 57.5mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            background-color: white;
        }

        /* Salto de página para cada etiqueta */
        .label-container {
            width: 104mm;
            height: 57.5mm;
            box-sizing: border-box;
            padding: 3mm;
            padding-top: 35px;
            display: flex; /* Dividimos en Izquierda (Barcode) y Derecha (Info) */
            overflow: hidden;
            page-break-after: always;
            border: 0.5mm dashed #eee; /* Solo para visualización, se puede quitar */
        }

        /* Contenedor del código de barras vertical */
        .barcode-side {
            width: 12mm;
            display: flex;
            align-items: center;
            justify-content: center;
           
        }

      /*  .barcode-vertical {
            transform: rotate(-90deg); 
            transform-origin: center;
            border: 1px dashed #000;0
        } 
        */

        
        .info-side {
            flex: 1;
            padding-left: 2mm;
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #000;
            margin-bottom: 2px;
            padding-bottom: 2px;
        }

        .logo {
            width: 50px;
            height: auto;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr ;
            gap: 1px;
            line-height: 1.1;
        }

        .full-width {
            grid-column: span 2;
        }

        .label-bold {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;

        }

        .footer-barcode {
           
            text-align: center;
        }

        #bcode-canvas {
            max-width: 100%;
            height: 6mm;
        }
   
        /* Corrección para evitar conflictos en vistas Bootstrap */
        .bootstrap-scope img, .bootstrap-scope svg { display: inline; }
       
    </style>
</head>
<body class="bg-light">
<div id="app">

 <div class="container-fluid px-4 tab-content-all" id="materialistas">
            <nav class="navbar navbar-expand bg-secondary mb-4 rounded px-2">
                <input type="hidden" id="maquina_material" value="MCUT-1">
                <div class="navbar-nav me-auto">
                    <span class="navbar-text text-white"><h2>Materiales para Maquina <span id="maquina_material_name">MCUT-1</span><h2></span>
            </nav>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0 text-secondary">Cables</h5>
                            <span class="badge bg-primary rounded-pill" id="totalCables">0</span>
                        </div>
                        <div class="card-body p-0"><table class="table mb-0"><tbody id="cablespormaquina"></tbody></table></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0 text-secondary">Terminales y sellos</h5>
                            <span class="badge bg-primary rounded-pill" id="totalterminales">0</span>
                        </div>
                        <div class="card-body p-0"><table class="table mb-0"><tbody id="terminalspormaquina"></tbody></table></div>
                    </div>
                </div>
               <!-- <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0 text-secondary">Herramentales</h5>
                        </div>
                        <div class="card-body p-0"><table class="table mb-0"><tbody id="herramentales"></tbody></table></div>
                    </div>
                </div>-->
            </div>
        </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function materialista(material) {
      fetch(`app/materialista.php?maquina=${encodeURIComponent(material)}`)
        .then(response => response.json())
        .then(data => {
            const cables = data.cables;
            const terminals = data.terminales;
            const tbodyCables = document.getElementById('cablespormaquina');

            document.getElementById('totalCables').textContent = Object.keys(cables).length;
            document.getElementById('totalterminales').textContent = Object.keys(terminals).length;

            // ---- Helpers de tiempo ----
            function pad(n) { return n < 10 ? '0' + n : '' + n; }
            function formatoHora(fecha) {
                return `${pad(fecha.getHours())}:${pad(fecha.getMinutes())}`;
            }

            // Devuelve el siguiente día hábil (si cae domingo, brinca a lunes) a las 7:40am
            function siguienteDiaHabil(fecha) {
                let nueva = new Date(fecha);
                nueva.setDate(nueva.getDate() + 1);
                if (nueva.getDay() === 0) { // 0 = domingo
                    nueva.setDate(nueva.getDate() + 1);
                }
                nueva.setHours(7, 40, 0, 0);
                return nueva;
            }

            // Si el punto de arranque ya es domingo o ya pasó las 5:30pm, lo reubica
            function inicioValido(fecha) {
                let f = new Date(fecha);
                const limite = new Date(f);
                limite.setHours(17, 30, 0, 0);
                if (f.getDay() === 0 || f > limite) {
                    return siguienteDiaHabil(f);
                }
                return f;
            }

            // Punto de partida: el momento actual, ya validado
            let tiempoActual = inicioValido(new Date());
            let ultimoDiaMostrado = tiempoActual.toDateString();

            tbodyCables.innerHTML = '';
            Object.keys(cables).forEach(key => {
                const minutos = parseInt(cables[key]);

                let inicio = new Date(tiempoActual);
                let fin = new Date(inicio.getTime() + minutos * 60000);

                // Límite de las 5:30pm del día de "inicio"
                const limite = new Date(inicio);
                limite.setHours(17, 30, 0, 0);

                // Si esta tarea se pasaría de las 5:30pm, se recorre al siguiente día hábil
                if (fin > limite) {
                    inicio = siguienteDiaHabil(inicio);
                    fin = new Date(inicio.getTime() + minutos * 60000);
                }

                // Etiqueta de día, solo si cambió respecto al renglón anterior
                let etiquetaDia = '';
                const diaActual = inicio.toDateString();
                if (diaActual !== ultimoDiaMostrado) {
                    etiquetaDia = ` (${inicio.toLocaleDateString('es-MX', { weekday: 'short', day: '2-digit', month: '2-digit' })})`;
                    ultimoDiaMostrado = diaActual;
                }

                const tiempoCalculado = `${formatoHora(inicio)} - ${formatoHora(fin)}${etiquetaDia}`;

                tbodyCables.innerHTML += `<tr><td>${key}</td><td>${tiempoCalculado}</td></tr>`;

                tiempoActual = fin;
            });

            const tbodyTerminals = document.getElementById('terminalspormaquina');
            tbodyTerminals.innerHTML = '';
            Object.keys(terminals).forEach(key => {
                tbodyTerminals.innerHTML += `<tr><td>${key}</td></tr>`;
            });
        });
}
</script>