<?php
require "../../app/conection.php";

// Validar parámetro WO
if (!isset($_GET['wo']) || empty($_GET['wo'])) {
    die("Error: Orden de Trabajo (WO) no especificada.");
}

$wo = mysqli_real_escape_string($con, $_GET['wo']);

try {
    // 1. Información general del WO
    $queryGeneral = "SELECT np, cliente, qty FROM `corte` WHERE wo = '$wo' LIMIT 1";
    $resGeneral = mysqli_query($con, $queryGeneral);
    $infoGeneral = mysqli_fetch_assoc($resGeneral);

    if (!$infoGeneral) {
        throw new Exception("No se encontraron registros para la orden de trabajo: " . $wo);
    }

    // 2. Desglose detallado de cortes
    $queryCortes = "SELECT cons, color, tipo, aws, tamano, cutStatus, codigo FROM `corte` WHERE wo = '$wo' ORDER BY cons ASC";
    $resultCortes = mysqli_query($con, $queryCortes);

    $cortesListos = [];
    $cortesFaltantes = [];

    while ($row = mysqli_fetch_assoc($resultCortes)) {
        if ($row['cutStatus'] === 'Cortado') {
            $cortesListos[] = $row;
        } else {
            $cortesFaltantes[] = $row;
        }
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
    <title>Detalle WO: <?php echo htmlspecialchars($wo); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .card-info { border-left: 5px solid #00cec9; }
        .table-sticky { max-height: 450px; overflow-y: auto; }
        .table-sticky thead th { position: sticky; top: 0; z-index: 5; background-color: #f8f9fa !important; }
    </style>
</head>
<body>

<div class="container py-5">
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php else: ?>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-bold mb-0 text-dark">
                <i class="fa-solid fa-circle-info text-secondary me-2"></i> Desglose de Orden de Trabajo
            </h2>
            <a href="index.php" class="btn btn-outline-secondary btn-sm shadow-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Regresar al Monitoreo
            </a>
        </div>

        <div class="card shadow-sm card-info mb-4 bg-white">
            <div class="card-body">
                <div class="row text-center text-md-start">
                    <div class="col-md-3 border-end">
                        <span class="text-muted d-block text-uppercase small fw-bold">Orden de Trabajo</span>
                        <h3 class="text-primary fw-bold mb-0"><?php echo htmlspecialchars($wo); ?></h3>
                    </div>
                    <div class="col-md-3 border-end">
                        <span class="text-muted d-block text-uppercase small fw-bold">Número de Parte</span>
                        <h4 class="text-dark fw-semibold mb-0"><?php echo htmlspecialchars($infoGeneral['np']); ?></h4>
                    </div>
                    <div class="col-md-3 border-end">
                        <span class="text-muted d-block text-uppercase small fw-bold">Cliente</span>
                        <h4 class="text-dark fw-semibold mb-0"><?php echo htmlspecialchars($infoGeneral['cliente'] ?? 'N/A'); ?></h4>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted d-block text-uppercase small fw-bold">Cantidad Solicitada</span>
                        <h4 class="text-dark fw-semibold mb-0"><?php echo number_format($infoGeneral['qty']); ?> pzas</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-danger text-white d-flex align-items-center justify-content-between py-3">
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-clock me-2"></i> Cortes por Hacer / Faltantes</h5>
                        <span class="badge bg-white text-danger px-3 py-1 rounded-pill fw-bold">
                            <?php echo count($cortesFaltantes); ?> pendientes
                        </span>
                    </div>
                    <div class="card-body p-0 table-responsive table-sticky">
                        <?php if (empty($cortesFaltantes)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-circle-check text-success display-4 d-block mb-2"></i>
                                ¡Todos los cortes de esta orden están completados!
                            </div>
                        <?php else: ?>
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Cons</th>
                                        <th>Código</th>
                                        <th>Especificación</th>
                                        <th class="text-end preg-3">Longitud</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cortesFaltantes as $item): ?>
                                        <tr>
                                            <td class="ps-3 fw-bold text-danger"><?php echo htmlspecialchars($item['cons']); ?></td>
                                            <td><code class="text-dark fw-semibold"><?php echo htmlspecialchars($item['codigo']); ?></code></td>
                                            <td>
                                                <span class="small d-block text-secondary"><?php echo htmlspecialchars($item['tipo']); ?></span>
                                                <strong><?php echo htmlspecialchars($item['aws']); ?></strong> | <?php echo htmlspecialchars($item['color']); ?>
                                            </td>
                                            <td class="text-end pe-3 fw-bold text-secondary"><?php echo number_format($item['tamano'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-success text-white d-flex align-items-center justify-content-between py-3">
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-circle-check me-2"></i> Cortes Completados (Cortados)</h5>
                        <span class="badge bg-white text-success px-3 py-1 rounded-pill fw-bold">
                            <?php echo count($cortesListos); ?> listos
                        </span>
                    </div>
                    <div class="card-body p-0 table-responsive table-sticky">
                        <?php if (empty($cortesListos)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-scissors text-danger display-4 d-block mb-2"></i>
                                Aún no se han completado piezas en esta orden.
                            </div>
                        <?php else: ?>
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Cons</th>
                                        <th>Código</th>
                                        <th>Especificación</th>
                                        <th class="text-end pe-3">Longitud</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cortesListos as $item): ?>
                                        <tr style="background-color: rgba(25, 135, 84, 0.03);">
                                            <td class="ps-3 fw-bold text-success">
                                                <i class="fa-solid fa-check-double me-1 small"></i><?php echo htmlspecialchars($item['cons']); ?>
                                            </td>
                                            <td><code class="text-muted text-decoration-line-through"><?php echo htmlspecialchars($item['codigo']); ?></code></td>
                                            <td class="text-muted">
                                                <span class="small d-block text-decoration-line-through"><?php echo htmlspecialchars($item['tipo']); ?></span>
                                                <del><?php echo htmlspecialchars($item['aws']); ?> | <?php echo htmlspecialchars($item['color']); ?></del>
                                            </td>
                                            <td class="text-end pe-3 text-success fw-semibold"><?php echo number_format($item['tamano'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>