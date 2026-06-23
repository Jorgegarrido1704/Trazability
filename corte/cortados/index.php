<?php
require "../../app/conection.php";

try {
    // Consulta optimizada con suma condicional y GROUP BY
    $query = "
        SELECT 
            np,
            wo,
            qty,
            COUNT(*) as total_cortes,
            SUM(CASE WHEN cutStatus = 'Activo' THEN 1 ELSE 0 END) as activos,
            ROUND((SUM(CASE WHEN cutStatus != 'Activo' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as porcentaje_activos
        FROM `corte` 
        GROUP BY np, wo
        ORDER BY porcentaje_activos DESC
    ";

    $cortes = mysqli_query($con, $query);

    if (!$cortes) {
        throw new Exception("Error en la consulta: " . mysqli_error($con));
    }

} catch(Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo de Cortes y Órdenes (WO)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background: white;
        }
        .badge-np {
            background-color: #6c5ce7;
            color: white;
            font-weight: 600;
        }
        .badge-wo {
            background-color: #00cec9;
            color: #2d3436;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .badge-wo:hover {
            background-color: #00b5b2;
            transform: scale(1.05);
        }
        .progress {
            height: 18px;
            border-radius: 10px;
            background-color: #dfe6e9;
        }
        .progress-bar {
            font-size: 11px;
            font-weight: bold;
            line-height: 18px;
        }

        /* --- CONTENEDOR CON SCROLL Y ENCABEZADO FIJO --- */
        .table-sticky-container {
            max-height: 650px;
            overflow-y: auto;
            border-radius: 15px;
        }
        
        .table-sticky-container thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #212529 !important;
            color: #ffffff;
            box-shadow: inset 0 -1px 0 rgba(255,255,255,0.15);
        }
    </style>
</head>
<body>

<div class="container-fluid py-5 px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between bg-white p-4 rounded-3 shadow-sm">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">
                        <i class="fa-solid fa-scissors text-primary me-2"></i> Panel de Monitoreo de Cortes
                    </h1>
                    <p class="text-muted mb-0">Estado de producción agrupado por Número de Parte (NP) y Orden de Trabajo (WO)</p>
                </div>
                <span class="badge bg-primary px-3 py-2 rounded-pill">Visualización en Tiempo Real</span>
            </div>
        </div>
    </div>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2 fs-4"></i>
            <div><strong>¡Error del sistema!</strong> <?php echo htmlspecialchars($error_message); ?></div>
        </div>
    <?php else: ?>

    <div class="card card-custom">
        <div class="card-body p-0">
            <div class="table-responsive table-sticky-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3">Número de Parte (NP)</th>
                            <th class="py-3">Orden de Trabajo (WO)</th>
                            <th class="py-3 text-center">Cantidad Orden</th>
                            <th class="py-3 text-center">Total Cortes</th>
                            <th class="py-3 text-center">Cortes Activos</th>
                            <th class="py-3 pe-4" style="width: 30%;">Progreso de Cortes Activos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while ($corte = mysqli_fetch_assoc($cortes)): 
                           
                            $porcentaje = $corte['porcentaje_activos'];
                           if($porcentaje <= 100){
                               
                           
                            if ($porcentaje < 40) {
                                $bg_color = 'bg-danger';
                            } elseif ($porcentaje < 80) {
                                $bg_color = 'bg-warning text-dark';
                            } else {
                                $bg_color = 'bg-success';
                            }
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="badge badge-np px-3 py-2 rounded">
                                        <i class="fa-solid fa-layer-group me-1"></i> <?php echo htmlspecialchars($corte['np']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="detalle_corte.php?wo=<?php echo urlencode($corte['wo']); ?>" class="text-decoration-none">
                                        <span class="badge badge-wo px-3 py-2 rounded-pill" style="cursor: pointer;">
                                            <i class="fa-solid fa-eye me-1 small"></i> <?php echo htmlspecialchars($corte['wo']); ?>
                                        </span>
                                    </a>
                                </td>
                                <td class="text-center fw-semibold text-secondary">
                                    <?php echo number_format($corte['qty']); ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-2 py-1 fs-6">
                                        <?php echo $corte['total_cortes']; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark px-2 py-1 fs-6">
                                        <?php echo $corte['activos']; ?>
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex align-items-center">
                                        <div class="progress w-100 me-2 shadow-sm">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated <?php echo $bg_color; ?>" 
                                                 role="progressbar" 
                                                 style="width: <?php echo $porcentaje; ?>%;" 
                                                 aria-valuenow="<?php echo $porcentaje; ?>" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                <?php echo $porcentaje; ?>%
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php }endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>