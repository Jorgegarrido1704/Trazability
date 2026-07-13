<?php
/**
 * obtenerCongelado.php
 * -----------------------------------------------------------------
 * GET app/pormaquina/obtenerCongelado.php?maquina=MCUT-4
 *
 * Devuelve todo lo ya congelado para esa máquina (o todas), agrupado
 * por día, con su total de minutos. Se usa para:
 *   1) Mostrar el historial de días ya congelados.
 *   2) Que el frontend sepa qué WO+PN excluir del próximo reparto.
 * -----------------------------------------------------------------
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/conexion.php'; // debe exponer un $pdo (PDO) ya conectado

$maquina = $_GET['maquina'] ?? null;

$sql = "SELECT * FROM carga_congelada";
$params = [];
if ($maquina && $maquina !== 'todas') {
    $sql .= " WHERE maquina = :maquina";
    $params[':maquina'] = $maquina;
}
$sql .= " ORDER BY maquina, dia_bloque, orden_en_dia";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupamos por máquina+día para que el frontend no tenga que hacerlo
$agrupado = [];
foreach ($rows as $row) {
    $key = $row['maquina'] . '-' . $row['dia_bloque'];
    if (!isset($agrupado[$key])) {
        $agrupado[$key] = [
            'maquina'        => $row['maquina'],
            'dia_bloque'     => (int) $row['dia_bloque'],
            'fecha_asignada' => $row['fecha_asignada'],
            'items'          => [],
            'total_minutos'  => 0,
        ];
    }
    $agrupado[$key]['items'][] = $row;
    $agrupado[$key]['total_minutos'] += (float) $row['minutos'];
}

echo json_encode(array_values($agrupado));
