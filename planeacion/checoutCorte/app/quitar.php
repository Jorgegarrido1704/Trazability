<?php
require 'conection.php';
header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $wo   = $_GET['wo']   ?? null;
    $cons = $_GET['cons'] ?? null;

    if ($wo === null || $cons === null || $wo === '' || $cons === '') {
        throw new Exception("Faltan wo o cons.");
    }

    $fechaCorte     = date('Y-m-d H:i:s');
    $nuevafechaCutF = date('d-m-Y H:i');

    // 1. Marcar como Cortado
    $stmt = $con->prepare(
        "UPDATE corte SET cutStatus='Cortado', fechaCorte=? 
         WHERE wo=? AND cons=? AND cutStatus='Activo'"
    );
    $stmt->bind_param('sss', $fechaCorte, $wo, $cons);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("No se encontró un registro Activo con wo=$wo y cons=$cons");
    }

    // 2. Quitar de congelados
    $stmt = $con->prepare("DELETE FROM carga_congelada WHERE wo=? AND consumo=?");
    $stmt->bind_param('ss', $wo, $cons);
    $stmt->execute();

    // 3. Si ya no quedan activos en esa WO, avanzar la WO
    $stmt = $con->prepare("SELECT 1 FROM corte WHERE wo=? AND cutStatus='Activo' LIMIT 1");
    $stmt->bind_param('s', $wo);
    $stmt->execute();

    if ($stmt->get_result()->num_rows === 0) {
        $stmt = $con->prepare("UPDATE registro SET count='4', donde='En espera de liberacion' WHERE wo=?");
        $stmt->bind_param('s', $wo);
        $stmt->execute();

        $stmt = $con->prepare("UPDATE registroparcial SET libePar=libePar+cortPar, cortPar='0' WHERE wo=?");
        $stmt->bind_param('s', $wo);
        $stmt->execute();

        $stmt = $con->prepare("UPDATE timesharn SET cutF=? WHERE wo=?");
        $stmt->bind_param('ss', $nuevafechaCutF, $wo);
        $stmt->execute();
    }

    echo json_encode(["status" => "success"]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}