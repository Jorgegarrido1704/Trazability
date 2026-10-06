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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
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