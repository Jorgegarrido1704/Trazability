<?php

require 'conection.php';

try {
    $maquinas = [
        "MCUT-10" => 0, "MCUT-1" => 0, "MCUT-5" => 0, 
        "MCUT-6"  => 0, "MCUT-4" => 0, "MCUT-7" => 0, 
        ">10"     => 0
    ];

    // 1. Mapeo de lógica para evitar la repetición de los "switch"
    // Estructura: [tinta][rango_de_calibres] => modelo_maquina
    $maquinaMapping = [
        'NEGRA' => [
            '10_12' => 'MCUT-10',
            '14_16' => 'MCUT-5',
            '18_24' => 'MCUT-4'
        ],
        'BLANCA' => [
            '10_12' => 'MCUT-1',
            '14_16' => 'MCUT-6',
            '18_24' => 'MCUT-7'
        ]
    ];

    // 2. Preparar las consultas fijas ANTES de los bucles para mejorar rendimiento y seguridad
    $stmtListas = mysqli_prepare($con, "SELECT pn, aws, cons, color FROM listascorte WHERE pn = ? ORDER BY aws DESC");
    $stmtTiempo = mysqli_prepare($con, "SELECT timePerProcess FROM routing_models WHERE pn_routing = ? AND work_routing = '10001' AND work_description LIKE ?");
    $stmtColor  = mysqli_prepare($con, "SELECT tintaOrg FROM coloresencables WHERE eng_short_color = ? OR eng_color = ? OR spn_color = ? LIMIT 1");

    // Consulta inicial externa
    $estructuras = mysqli_query($con, "SELECT np, qty FROM estructuracortetiempos");

    while ($rowEstructura = mysqli_fetch_assoc($estructuras)) {
        $pn_estructura = $rowEstructura['np'];  
        $qty = (int)$rowEstructura['qty'];

        // Ejecutar consulta de listas de corte de forma segura
        mysqli_stmt_bind_param($stmtListas, "s", $pn_estructura);
        mysqli_stmt_execute($stmtListas);
        $resListas = mysqli_stmt_get_result($stmtListas);

        while ($rowlistas = mysqli_fetch_assoc($resListas)) {
            $pn       = $rowlistas['pn'];
            $calibre  = (int)$rowlistas['aws'];
            $consumo  = $rowlistas['cons'];
            $color    = $rowlistas['color'];

            // Valores por defecto para cada iteración
            $tiempo = 0;
            $setUp_routing = 0;

            // Buscar tiempos con Prepared Statement
            $likeConsumo = "Cutting cons {$consumo} //%";
            mysqli_stmt_bind_param($stmtTiempo, "ss", $pn, $likeConsumo);
            mysqli_stmt_execute($stmtTiempo);
            $resTiempo = mysqli_stmt_get_result($stmtTiempo);

            if ($rowTiempo = mysqli_fetch_assoc($resTiempo)) {
                $tiempo = round(($rowTiempo['timePerProcess'] * $qty/60),2);
                $setUp_routing = 10;
            }

            // Buscar Color con Prepared Statement
            mysqli_stmt_bind_param($stmtColor, "sss", $color, $color, $color);
            mysqli_stmt_execute($stmtColor);
            $resColor = mysqli_stmt_get_result($stmtColor);

            if ($rowColor = mysqli_fetch_assoc($resColor)) {
                $tinta = $rowColor['tintaOrg'];

                if (isset($maquinaMapping[$tinta])) {
                    // Determinar el rango del calibre
                    $rango = '>10';
                    if ($calibre == 10 || $calibre == 12) {
                        $rango = '10_12';
                    } elseif ($calibre == 14 || $calibre == 16) {
                        $rango = '14_16';
                    } elseif ($calibre >= 18 && $calibre <= 24 && $calibre % 2 == 0) {
                        // Captura 18, 20, 22, 24
                        $rango = '18_24';
                    }

                    // Asignar a la máquina correspondiente usando el mapa
                    $maquinaAsignada = $maquinaMapping[$tinta][$rango] ?? '>10';
                    $maquinas[$maquinaAsignada] += ($tiempo + $setUp_routing);
                }
            }
        }
    }

    // Cerrar statements
    mysqli_stmt_close($stmtListas);
    mysqli_stmt_close($stmtTiempo);
    mysqli_stmt_close($stmtColor);

    // Devolver JSON limpio
    header('Content-Type: application/json');
    echo json_encode($maquinas);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Ocurrió un error interno en el servidor."]);
}
?>