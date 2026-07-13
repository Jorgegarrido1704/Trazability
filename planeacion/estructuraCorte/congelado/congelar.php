<?php
/**
 * congelar.php
 * -----------------------------------------------------------------
 * Recibe (por POST, en JSON) el reparto que el usuario ya revisó en
 * pantalla y lo guarda de forma PERMANENTE en la tabla
 * carga_congelada. A partir de ahí, esos WO ya no se vuelven a
 * repartir automáticamente (quedan fijos / congelados).
 *
 * Body esperado (JSON):
 * {
 *   "maquina": "MCUT-4",
 *   "usuario": "juan.perez",          // opcional
 *   "bloques": [                       // un array por cada día
 *       [ { "wo":"12345","pn":"ABC-1","consumo":"1","calibre":"18",
 *           "color":"Negro","tipo":"THHN","terminal1":"...",
 *           "terminal2":"...","urgencia":"3-Alta","minutos":120,
 *           "fecha":"2026-07-13" }, ... ],   // día 1
 *       [ ... ]                                // día 2
 *   ]
 * }
 *
 * IMPORTANTE: ajusta el require de conexion.php a tu archivo real de
 * conexión PDO existente en el proyecto.
 * -----------------------------------------------------------------
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/conexion.php'; // debe exponer un $pdo (PDO) ya conectado

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['maquina']) || empty($input['bloques'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos incompletos (maquina/bloques)']);
    exit;
}

$maquina = $input['maquina'];
$bloques = $input['bloques']; // array de días -> cada día es un array de items
$usuario = $input['usuario'] ?? null;

try {
    $pdo->beginTransaction();

    // ON DUPLICATE KEY: si por error se vuelve a enviar el mismo WO+PN+maquina,
    // se actualiza en vez de duplicar (protección extra, aunque el frontend
    // ya debería excluir lo que está congelado).
    $stmt = $pdo->prepare("
        INSERT INTO carga_congelada
            (maquina, dia_bloque, fecha_asignada, wo, pn, consumo, calibre,
             color, tipo, terminal1, terminal2, urgencia, minutos, orden_en_dia, usuario)
        VALUES
            (:maquina, :dia_bloque, :fecha_asignada, :wo, :pn, :consumo, :calibre,
             :color, :tipo, :terminal1, :terminal2, :urgencia, :minutos, :orden_en_dia, :usuario)
        ON DUPLICATE KEY UPDATE
            dia_bloque      = VALUES(dia_bloque),
            fecha_asignada  = VALUES(fecha_asignada),
            minutos         = VALUES(minutos),
            orden_en_dia    = VALUES(orden_en_dia)
    ");

    foreach ($bloques as $diaIndex => $items) {
        $ordenEnDia = 1;
        foreach ($items as $item) {
            if (empty($item['wo']) || empty($item['pn']) || !isset($item['minutos'])) {
                continue; // se salta filas incompletas
            }
            $stmt->execute([
                ':maquina'        => $maquina,
                ':dia_bloque'     => $diaIndex + 1,
                ':fecha_asignada' => $item['fecha'] ?? null,
                ':wo'             => $item['wo'],
                ':pn'             => $item['pn'],
                ':consumo'        => $item['consumo'] ?? null,
                ':calibre'        => $item['calibre'] ?? null,
                ':color'          => $item['color'] ?? null,
                ':tipo'           => $item['tipo'] ?? null,
                ':terminal1'      => $item['terminal1'] ?? null,
                ':terminal2'      => $item['terminal2'] ?? null,
                ':urgencia'       => $item['urgencia'] ?? null,
                ':minutos'        => $item['minutos'],
                ':orden_en_dia'   => $ordenEnDia,
                ':usuario'        => $usuario,
            ]);
            $ordenEnDia++;
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
