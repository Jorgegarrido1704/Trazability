    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reporte de cortes </title>   
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">          
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
                                <div class="card-header bg-primary text-white font-weight-bold display-8 py-2">
                                    MCUT-1
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center py-4">
                                    <h5 class="display-6 font-weight-bold text-primary mb-0"><span id="MCUT-1">0</span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-primary text-white font-weight-bold display-8 py-2">
                                MCUT-2
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center py-4">
                                    <h5 class="display-6 font-weight-bold text-primary mb-0"><span id="MCUT-2">0</span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-primary text-white font-weight-bold display-8 py-2">
                                    MCUT-3
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center py-4">
                                    <h5 class="display-6 font-weight-bold text-primary mb-0"><span id="MCUT-3">0</span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-primary text-white font-weight-bold display-8 py-2">
                                MCUT-4
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center py-4">
                                    <h5 class="display-6 font-weight-bold text-primary mb-0"><span id="MCUT-4">0</span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-primary text-white font-weight-bold display-8 py-2">
                                    MCUT-5
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center py-4">
                                    <h5 class="display-6 font-weight-bold text-primary mb-0"><span id="MCUT-5">0</span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-primary text-white font-weight-bold display-8 py-2">
                                    MCUT-6
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center py-4">
                                    <h5 class="display-6 font-weight-bold text-primary mb-0"><span id="MCUT-6">0</span></h5>
                                </div>
                            </div>
                        </div>
                         <div class="col-xl-12 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-primary text-white font-weight-bold display-8 py-2">
                                    Total de cortes en turno
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center py-4">
                                    <h5 class="display-6 font-weight-bold text-primary mb-0"><span id="totales">0</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>    
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
<script>
    const INTERVALO_MS = 6000;

    function setTexto(id, valor) {
        const el = document.getElementById(id);
        if (el) el.textContent = valor ?? 0;
    }

    async function refrescarPantalla() {
        try {
            // El parámetro t evita el caché; cache:'no-store' lo refuerza
            const response = await fetch('app/app_toi.php?t=' + Date.now(), { cache: 'no-store' });
            if (!response.ok) throw new Error('HTTP ' + response.status);

            const texto = await response.text();       // leemos como texto para detectar basura en el JSON
            let data;
            try {
                data = JSON.parse(texto);
            } catch (e) {
                console.error('Respuesta no es JSON válido:', texto);
                return;
            }

            console.log('Datos recibidos:', data);
            const m = data.maquinas || {};

            setTexto('MCUT-1', m.M1);
            setTexto('MCUT-2', m.M2);
            setTexto('MCUT-3', m.M3);
            setTexto('MCUT-4', m.M4);
            setTexto('MCUT-5', m.M5);
            setTexto('MCUT-6', m.M6);
            setTexto('totales', m.totales);
            setTexto('hora', new Date().toLocaleTimeString());
        } catch (error) {
            console.error('Error al refrescar la pantalla:', error);
        } finally {
            setTimeout(refrescarPantalla, INTERVALO_MS); // evita peticiones encimadas
        }
    }

    window.addEventListener('load', refrescarPantalla);
    </script>

    </body>
    </html>
   