

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
                    let tiempo_del_trabajo = 450;
                    
                    document.getElementById('totalCables').textContent = Object.keys(cables).length;
                    document.getElementById('totalterminales').textContent = Object.keys(terminals).length;
                    
                    tbodyCables.innerHTML = '';
                    Object.keys(cables).forEach(key => {
                        const minutos = parseInt(cables[key]);
                        tiempo_del_trabajo
                        ulhh = (tiempo_del_trabajo/60).toFixed(3).split('.')[0];
                        if(ulhh.length == 1) ulhh = '0'+ulhh;
                        ulmm = (tiempo_del_trabajo%60).toFixed(0);
                        if(ulmm.length == 1) ulmm = '0'+ulmm;
                        let horaAcutal = minutos+tiempo_del_trabajo;
                        hh = (horaAcutal/60).toFixed(3).split('.')[0];
                        if(hh.length == 1) hh = '0'+hh;
                        mm = (horaAcutal%60).toFixed(0);
                        if(mm.length == 1) mm = '0'+mm;
                        const tiempoCalculado = `${ulhh}:${ulmm} - ${hh}:${mm}`;

                        tbodyCables.innerHTML += `<tr><td>${key}</td><td>${tiempoCalculado}</td></tr>`;
                        tiempo_del_trabajo += parseInt(cables[key]);
                    });
                    
                    const tbodyTerminals = document.getElementById('terminalspormaquina');
                    tbodyTerminals.innerHTML = '';
                    Object.keys(terminals).forEach(key => {
                        tbodyTerminals.innerHTML += `<tr><td>${key}</td></tr>`;
                    });
                });
        }
        

       window.addEventListener('load', () => {
            const maquina = document.getElementById('maquina_material').value;
            materialista(maquina);
        });
        setInterval(() => {

            let maquina = document.getElementById('maquina_material').value;
            switch(maquina) {
                case 'MCUT-1':
                    maquina = 'MCUT-2';
                    break;
                case 'MCUT-2':
                    maquina = 'MCUT-3';
                    break;
                case 'MCUT-3':
                    maquina = 'MCUT-4';
                    break;
                case 'MCUT-4':
                    maquina = 'MCUT-5';
                    break;
                case 'MCUT-5':
                    maquina = 'MCUT-6';
                    break;
                case 'MCUT-6':
                    maquina = 'MCUT-7';
                    break;
                case 'MCUT-7':
                    window.location.reload();
                    break;
              
                   
                    break;
                default:
                    console.warn(`Maquina desconocida: ${maquina}`);
            }
            document.getElementById('maquina_material_name').textContent = maquina;
            document.getElementById('maquina_material').value = maquina;
            materialista(maquina);
        }, 1000*2*60); // Actualizar cada 5 minutos 1000*5*60
</script>