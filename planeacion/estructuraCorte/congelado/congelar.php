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
 * IMPORTANTE:
 * - Requiere un UNIQUE KEY en carga_congelada, por ejemplo:
 *     ALTER TABLE carga_congelada
 *       ADD UNIQUE KEY uq_congelado (maquina, wo, pn, dia_bloque);
 *   Sin eso, el ON DUPLICATE KEY UPDATE de abajo nunca se activa.
 * -----------------------------------------------------------------
 */

header('Content-Type: application/json; charset=utf-8');

require '../app/conection.php'; // debe exponer un $pdo (PDO) ya conectado

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
    // se actualiza en vez de duplicar. Requiere un UNIQUE KEY real en la tabla
    // (ver nota arriba); si no existe, esta cláusula no tiene efecto.
    $stmt = $pdo->prepare("
        INSERT INTO carga_congelada
            (maquina, dia_bloque, fecha_asignada, wo, pn, consumo, calibre,
             color, tipo, terminal1, terminal2, urgencia, minutos, orden_en_dia, usuario,id_corte)
        VALUES
            (:maquina, :dia_bloque, :fecha_asignada, :wo, :pn, :consumo, :calibre,
             :color, :tipo, :terminal1, :terminal2, :urgencia, :minutos, :orden_en_dia, :usuario,:id_corte)
        ON DUPLICATE KEY UPDATE
            dia_bloque      = VALUES(dia_bloque),
            fecha_asignada  = VALUES(fecha_asignada),
            minutos         = VALUES(minutos),
            orden_en_dia    = VALUES(orden_en_dia),
            usuario         = VALUES(usuario)
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
                ':minutos'        => $item['minutos'], // antes decía $item['min'] (bug)
                ':orden_en_dia'   => $ordenEnDia,
                ':usuario'        => $usuario,
                ':id_corte'       => $item['id_corte']
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