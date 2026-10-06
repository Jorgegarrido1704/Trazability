<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de cortes</title>
    <!-- Self-hosted: put the file in /css/ -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid py-3">
        <h1 class="text-center mb-4">
            Reportes de cortes
            <span class="badge text-bg-success fs-6" id="hora"></span>
        </h1>

        <div class="row text-center g-4" id="cortes"></div>
    </div>

    <script>
        const MAQUINAS = ['M1', 'M2', 'M3', 'M4', 'M5', 'M6'];

        // Build the cards once instead of repeating the HTML six times
        document.getElementById('cortes').innerHTML = MAQUINAS.map((m, i) => `
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold fs-4 py-2">MCUT-${i + 1}</div>
                    <div class="card-body d-flex align-items-center justify-content-center py-4">
                        <span class="display-4 fw-bold text-primary" id="${m}">0</span>
                    </div>
                </div>
            </div>`).join('');

        async function refrescarPantalla() {
            try {
                const response = await fetch('app/app_toi.php', { cache: 'no-store' });
                if (!response.ok) throw new Error('HTTP ' + response.status);
                const data = await response.json();

                MAQUINAS.forEach(m => {
                    document.getElementById(m).textContent = data[m] ?? 0;
                });
                document.getElementById('hora').textContent =
                    new Date().toLocaleTimeString('es-MX');
            } catch (error) {
                console.error('Error al refrescar la pantalla:', error);
            }
        }

        refrescarPantalla();                    // load immediately
        setInterval(refrescarPantalla, 60000);  // then every 60 seconds
    </script>
</body>
</html>