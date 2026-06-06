
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listas de Corte - Filtros Básicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1"><i class="bi bi-scissors me-2"></i>Control de Listas de Corte</span>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <div class="row g-4">
            
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 text-primary"><i class="bi bi-funnel me-2"></i>Filtros de Búsqueda</h5>
                    </div>
                    <div class="card-body">
                        <form id="filterForm" class="row g-3">
                            
                            <div class="col-md-5">
                                <label for="wireGauge" class="form-label fw-semibold">Calibre de Cable (Gauge / AWS)</label>
                                <select class="form-select" id="wireGauge" onchange="fetchcolors()">
                                    <option value="">Todos los calibres...</option>
                                </select>
                            </div>

                            <div class="col-md-5" id ="colorSelect">
                                <label for="wireColor" class="form-label fw-semibold">Color del Cable</label>
                                <select class="form-select" id="wireColor" onchange="fetchCuttingLists()">
                                    <option value="">Todos los colores...</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                    <i class="bi bi-eraser me-2"></i>Limpiar
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 text-secondary"><i class="bi bi-list-task me-2"></i>Órdenes de Corte</h5>
                        <span class="badge bg-primary rounded-pill" id="totalRecords">0 registros</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nº Parte</th>
                                        <th>Consecutivo</th>
                                        <th>Calibre (AWS)</th>
                                        <th>Color</th>
                                    </tr>
                                </thead>
                                <tbody id="resultsTableBody">
                                    <tr id="loadingRow">
                                        <td colspan="4" class="text-center text-muted py-4" >
                                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                            Cargando datos...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('colorSelect').style.display = 'none'; // Hide color filter until gauge is selected
            fecthgetgauges();
        });

        function fecthgetgauges() {
            fetch('api/get-gauges.php')
                .then(response => response.json())
                .then(data => {
                    let selectHTML = '<option value="">Todos los calibres...</option>';
                    data.forEach(gauge => {
                        selectHTML += `<option value="${gauge}">${gauge}</option>`;
                    });
                    document.getElementById('wireGauge').innerHTML = selectHTML;
                });
        }
       function fetchcolors() {
                const gauge = document.getElementById('wireGauge').value;
                fetch(`api/get-colors.php?awg=${gauge}`)
                    .then(response => response.json())
                    .then(data => {
                        // 1. Check if the backend returned an error object
                        if (data.error || !Array.isArray(data)) {
                            console.error("Backend Error:", data.error || "Data is not an array");
                            alert("Error loading colors: " + (data.error || "Unknown error"));
                            return; // Stop execution
                        }

                        // 2. Safely proceed if it's a valid array
                        let selectHTML = '<option value="">Todos los colores...</option>';
                        data.forEach(color => {
                            selectHTML += `<option value="${color}">${color}</option>`;
                        });
                        
                        document.getElementById('wireColor').innerHTML = selectHTML;
                        document.getElementById('colorSelect').style.display = 'block';
                    })   
                    .catch(err => console.error("Fetch error:", err)); // Catch network/JSON parsing errors
            }
         function clearFilters() {
           window.location.href = 'listas.php'; // Simple page reload to reset all filters and results

        }
        function fetchCuttingLists() {
            const gauge = document.getElementById('wireGauge').value;
            const color = document.getElementById('wireColor').value;
            let query = '?';
            if (gauge) query += `awg=${encodeURIComponent(gauge)}&`;
            if (color) query += `color=${encodeURIComponent(color)}`;
            
            fetch(`api/get-cutting-lists.php${query}`)
                .then(response => response.json())
                .then(data => {
                   //table in  resultsTableBody
                     const tbody = document.getElementById('resultsTableBody');
                    tbody.innerHTML = ''; // Clear previous results
                    let totalRecords = 0;
                    data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.pn}</td>
                            <td>${item.cons}</td>
                            <td>${item.aws}</td>
                            <td>${item.color}</td>
                        `;
                        tbody.appendChild(row);
                        totalRecords++;
                    });
                    document.getElementById('totalRecords').textContent = totalRecords + ' registros';
                })
                .catch(err => console.error("Fetch error:", err));
        }

       
    </script>
</body>
</html>