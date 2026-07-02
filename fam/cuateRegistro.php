<?php
require "../app/conection.php";

$datos = isset($_GET['grupo']) ? trim($_GET['grupo']) : '';

if ($datos != '') {
    $exclusivosNumerosdeparte = '';
    $rows = 0;
    
    // 1. Corregido: Usar Prepared Statements para evitar Inyección SQL
    $stmtBuscarGrupo = $con->prepare("SELECT customer FROM workschedule WHERE pn = ? LIMIT 1");
    $stmtBuscarGrupo->bind_param("s", $datos);
    $stmtBuscarGrupo->execute();
    $buscarGrupo = $stmtBuscarGrupo->get_result();
    
    if ($buscarGrupo->num_rows == 0) {
        echo "No se encontró ningún grupo para el número de parte: " . htmlspecialchars($datos);
        exit;
    }
    
    $cliente = $buscarGrupo->fetch_assoc();
    $customer = $cliente['customer'];
    $stmtBuscarGrupo->close();
    
    // 2. Obtener los números de parte del cliente (Familias)
    $stmtBuscarFamilias = $con->prepare("SELECT NumPart FROM registro WHERE cliente = ?");
    $stmtBuscarFamilias->bind_param("s", $customer);
    $stmtBuscarFamilias->execute();
    $buscarFamilias = $stmtBuscarFamilias->get_result();
    
    if ($buscarFamilias->num_rows == 0) {
        echo "No se encontraron números de parte para el cliente: " . htmlspecialchars($customer);
        exit;
    }
    
    // Guardamos los números de parte válidos del cliente en un array tipo espejo para búsquedas rápidas
    $partesDelCliente = [];
    while ($rowFamilias = $buscarFamilias->fetch_assoc()) {
        $partesDelCliente[$rowFamilias['NumPart']] = true;
    }
    $stmtBuscarFamilias->close();

    // 3. Obtener los items del número de parte consultado ($datos)
    $stmtItems = $con->prepare("
        SELECT item 
        FROM datos 
        WHERE part_num = ?
          AND item NOT LIKE 'WTXL-%'
          AND item NOT LIKE 'WGXL-%'
          AND item NOT LIKE 'WSGX-%'
          AND item NOT LIKE 'LTP%'
          AND item NOT LIKE 'LW-%'
          AND item NOT LIKE 'TAPE-25%'
    ");
    
    $stmtItems->bind_param("s", $datos);
    $stmtItems->execute();
    $resultItems = $stmtItems->get_result();

    $items = [];
    while ($row = $resultItems->fetch_assoc()) {
        $items[] = $row['item'];
    }
    $stmtItems->close();

    $totalItems = count($items);
    if ($totalItems === 0) {
        echo "No se encontraron items para el part number: " . htmlspecialchars($datos) . "<br>";
        exit;
    }

    // 4. Buscar compatibilidad. Quitamos el bucle repetitivo y lo hacemos directo.
    $stmtCompare = $con->prepare("
        SELECT part_num 
        FROM datos 
        WHERE item = ? 
          AND part_num != ?
          AND item NOT LIKE 'WTXL-%'
          AND item NOT LIKE 'WGXL-%'
          AND item NOT LIKE 'WSGX-%'
          AND item NOT LIKE 'LTP%'
          AND item NOT LIKE 'LW-%'
          AND item NOT LIKE 'TAPE%'
    ");

    $compatibilidad = [];
    foreach ($items as $item) {
        $stmtCompare->bind_param("ss", $item, $datos);
        $stmtCompare->execute();
        $resultCompare = $stmtCompare->get_result();

        while ($row = $resultCompare->fetch_assoc()) {
            $pn = $row['part_num'];
            
            // FILTRO CRUCIAL: Solo nos interesan los números de parte que pertenecen al mismo cliente
            if (isset($partesDelCliente[$pn])) {
                $compatibilidad[$pn] = ($compatibilidad[$pn] ?? 0) + 1;
            }
        }
    }
    $stmtCompare->close();

    // 5. Calcular y mostrar los porcentajes de compatibilidad con la parte consultada
    echo "<h3>Resultados de compatibilidad para: " . htmlspecialchars($datos) . "</h3>";
    $encontroCoincidencias = false;

    foreach ($compatibilidad as $pn => $matches) {
        $porcentaje = round(($matches / $totalItems) * 100, 2);

        if ($porcentaje >= 30) {
            $encontroCoincidencias = true;
            echo "Compatibilidad con <strong>$pn</strong> : $porcentaje% ($matches de $totalItems items)<br>";
        }
    }

    if (!$encontroCoincidencias) {
        echo "No se encontraron otros números de parte del cliente con más del 30% de compatibilidad.";
    }

} else {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Grupo</title>
</head>
<body>
    <form method="GET" action="cuateRegistro.php">
        <label for="grupo">Ingrese el número de parte:</label>
        <input type="text" name="grupo" id="grupo" required>
        <button type="submit">Buscar</button>
    </form>
</body>
</html>
<?php } ?>