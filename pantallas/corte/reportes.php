<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de cortes </title>
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
        .tab-content-all { display: none; }
        .tab-content-all.active { display: block; }
    </style>
</head>
<body>
            <div class="container-fluid">
                <h1 class="h1 mb-4 text-gray-800 text-center">Reportes de cortes <span class="badge badge-success" id="hora"></span></h1> 
            </div>
            <div class="row">
                <div class="col-12">
               <div class="d-sm-flex align-items-center justify-content-between mb-4"></div>

                <div class="row text-center mb-4">
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary text-white font-weight-bold display-6 py-2">
                                MCUT-1
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center py-4">
                                <h5 class="display-4 font-weight-bold text-primary mb-0"><span id="MCUT-1">0</span></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary text-white font-weight-bold display-6 py-2">
                            MCUT-2
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center py-4">
                                <h5 class="display-4 font-weight-bold text-primary mb-0"><span id="MCUT-2">0</span></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary text-white font-weight-bold display-6 py-2">
                                MCUT-3
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center py-4">
                                <h5 class="display-4 font-weight-bold text-primary mb-0"><span id="MCUT-3">0</span></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary text-white font-weight-bold display-6 py-2">
                               MCUT-4
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center py-4">
                                <h5 class="display-4 font-weight-bold text-primary mb-0"><span id="MCUT-4">0</span></h5>
                            </div>
                        </div>
                    </div>
                     <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary text-white font-weight-bold display-6 py-2">
                                MCUT-5
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center py-4">
                                <h5 class="display-4 font-weight-bold text-primary mb-0"><span id="MCUT-5">0</span></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary text-white font-weight-bold display-6 py-2">
                                MCUT-6
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center py-4">
                                <h5 class="display-4 font-weight-bold text-primary mb-0"><span id="MCUT-6">0</span></h5>
                            </div>
                        </div>
                    </div>
                </div>

            </div>    

</body>
</html>
<script>
    function refrescarPantalla() {
        fetch('app/app_toi.php')
            .then(response => response.json())
            .then(data => {
               console.log('Datos recibidos:', data); // Para depuración
                
                // Actualizar los elementos del DOM con los nuevos datos
                document.getElementById('MCUT-1').textContent = data['M1'] || 0;
                document.getElementById('MCUT-2').textContent = data['M2'] || 0;
                document.getElementById('MCUT-3').textContent = data['M3'] || 0;
                document.getElementById('MCUT-4').textContent = data['M4'] || 0;
                document.getElementById('MCUT-5').textContent = data['M5'] || 0;
                document.getElementById('MCUT-6').textContent = data['M6'] || 0;
            })
            .catch(error => console.error('Error al refrescar la pantalla:', error));
    }
    setInterval(refrescarPantalla, 60000); // Refrescar cada 60 segundos
    

    </script>