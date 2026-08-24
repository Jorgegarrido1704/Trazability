<?php
/**
 * obtenerCongelado.php
 * -----------------------------------------------------------------
 * GET app/pormaquina/obtenerCongelado.php?maquina=MCUT-4
 * -----------------------------------------------------------------
 */
header('Content-Type: application/json; charset=utf-8');

require '../app/conection.php'; // debe exponer un $pdo (PDO) ya conectado

$maquina = $_GET['maquina'] ?? null;

$sql = "SELECT 
            cc.id            AS cc_id,
            cc.maquina,
            cc.dia_bloque,
            cc.fecha_asignada,
            cc.minutos       AS min,
            cc.id_corte,
            c.np              AS pn,
            c.color,
            c.wo,
            c.codigo,
            c.aws,
            c.cons,
            c.tipo,
            c.dist_stamp,
            c.tamano,
            c.term1,
            c.term2,
            c.strip1,
            c.strip2,
            c.tintaColor,
            c.qty,
            c.time_ruteo,
            c.conector,
            c.cutStatus
        FROM carga_congelada cc
        JOIN corte c ON c.id = cc.id_corte";

$params = [];
if ($maquina && $maquina !== 'todas') {
    $sql .= " WHERE cc.maquina = :maquina";
    $params[':maquina'] = $maquina;
}
$sql .= " ORDER BY cc.maquina, cc.dia_bloque, cc.id_corte ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupamos por máquina+día
$agrupado = [];
foreach ($rows as $row) {
    $key = $row['maquina'] . '-' . $row['dia_bloque'];
    if (!isset($agrupado[$key])) {
        $agrupado[$key] = [
            'maquina'        => $row['maquina'],
            'dia_bloque'     => (int) $row['dia_bloque'],
            'fecha_asignada' => $row['fecha_asignada'],
            'items'          => [],
            'min'            => 0, // total de minutos del día
        ];
    }
    $setup=3;
    $agrupado[$key]['items'][] = $row;
    $agrupado[$key]['min'] += (float) $row['min']+$setup;
}

echo json_encode(array_values($agrupado));