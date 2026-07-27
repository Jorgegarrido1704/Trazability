<?php

require 'app.php';

try {
    $maquina = isset($_GET['maquina']) ? $_GET['maquina'] : 'todas';
    $cables = $terminales = $herramental = [];

    if ($maquina == 'todas') {
        $qry = "SELECT * FROM carga_congelada c
                ORDER BY
                    c.fecha_asignada, c.dia_bloque ASC,
                    c.color ASC,
                    c.calibre DESC,
                    c.tipo ASC,
                    CASE 
                        WHEN c.terminal2 LIKE CONCAT('%',c.terminal1,'%') THEN 0 
                        ELSE 1
                    END";
        $result = mysqli_query($con, $qry);
    } else {
        $qry = "SELECT * FROM carga_congelada c
                WHERE c.maq_asignada = ?
                ORDER BY
                    c.fecha_asignada, c.dia_bloque ASC,
                    c.color ASC,
                    c.calibre DESC,
                    c.tipo ASC,
                    CASE 
                        WHEN c.terminal2 LIKE CONCAT('%',c.terminal1,'%') THEN 0 
                        ELSE 1
                    END";
        $stmt = mysqli_prepare($con, $qry);
        mysqli_stmt_bind_param($stmt, "s", $maquina);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    }

    while ($rowlistas = mysqli_fetch_array($result)) {
        $calibre = $rowlistas['calibre'];
        $tipo = $rowlistas['tipo'];
        $color = $rowlistas['color'];
        $terminal1 = $rowlistas['terminal1'];
        $terminal2 = $rowlistas['terminal2'];
        $minutos = $rowlistas['minutos'];

        $clave = $calibre . "-" . $tipo . "-" . $color;
        if (array_key_exists($clave, $cables)) {
            $cables[$clave] += $minutos;
        } else {
            $cables[$clave] = $minutos;
        }

        if (!array_key_exists($terminal1, $terminales) && stripos($terminal1, 'Empalme') === false) {
            $terminales[$terminal1] = 1;
        }
        if (!array_key_exists($terminal2, $terminales) && stripos($terminal2, 'Empalme') === false) {
            $terminales[$terminal2] = 1;
        }
    }

    echo json_encode([
        'cables' => $cables,
        'terminales' => $terminales,
    ]);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    echo json_encode([]);
}