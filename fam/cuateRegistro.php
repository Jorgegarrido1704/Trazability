<?php
require "../app/conection.php";

$datos = isset($_GET['grupo']) ? trim($_GET['grupo']) : '';
$items = isset($_GET['items']) ? $_GET['items'] : '';

if ($datos != '') {
    $exclusivosNumerosdeparte = '';
    $rows = 0;
    
    $stmtBuscarFamilias = $con->prepare("SELECT DISTINCT pn FROM po WHERE client = (SELECT DISTINCT client FROM po WHERE pn = ?)");
    $stmtBuscarFamilias->bind_param("s", $datos);
    $stmtBuscarFamilias->execute();
    $buscarFamilias = $stmtBuscarFamilias->get_result();
    
    if ($buscarFamilias->num_rows == 0) {
        echo "No se encontraron números de parte";
        exit;
    }
    
    // Guardamos los números de parte válidos del cliente en un array tipo espejo para búsquedas rápidas
    $partesDelCliente = [];
    while ($rowFamilias = $buscarFamilias->fetch_assoc()) {
        $partesDelCliente[$rowFamilias['pn']] = true;
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
          AND item NOT LIKE '%T_-%'
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
          AND item NOT LIKE '%T_-%'
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

}else if ($items) {
    // Limpiar y quitar vacíos/duplicados
    $items = array_values(array_unique(array_filter(array_map('trim', explode(",", $items)))));
    $ItemsCount = count($items);

    if ($ItemsCount < 1) {
        header("Location: cuateRegistro.php");
        exit;
    }

    // Placeholders: ?,?,?
    $placeholders = implode(',', array_fill(0, $ItemsCount, '?'));
    $tipos = str_repeat('s', $ItemsCount);

    $sql = "
        SELECT 
            d.part_num,
            COUNT(DISTINCT d.item) AS coincidencias,
            (SELECT COUNT(DISTINCT t.item) FROM datos t WHERE t.part_num = d.part_num) AS total_part
        FROM datos d
        WHERE d.item IN ($placeholders)
        GROUP BY d.part_num
        ORDER BY coincidencias DESC, total_part ASC
    ";

    $buscarDatos = $con->prepare($sql);
    $buscarDatos->bind_param($tipos, ...$items);
    $buscarDatos->execute();
    $registrosItems = $buscarDatos->get_result();

    while ($row = $registrosItems->fetch_assoc()) {
        $parte         = htmlspecialchars($row['part_num']);
        $coincidencias = (int)$row['coincidencias'];
        $totalPart     = (int)$row['total_part'];

        // % respecto a lo que buscaste y % respecto a los componentes de esa parte
        $simBusqueda = round(($coincidencias / $ItemsCount) * 100, 1);
        $simParte    = $totalPart > 0 ? round(($coincidencias / $totalPart) * 100, 1) : 0;

        echo "<h3>Parte: $parte</h3>";
        echo "<p>Coincidencias: $coincidencias de $ItemsCount buscados ($simBusqueda%)</p>";
        echo "<p>Cobertura de la parte: $coincidencias de $totalPart items ($simParte%)</p>";
    }

    $buscarDatos->close();
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
    <br><hr><br>
     <form method="GET" action="cuateRegistro.php">
        <label for="items">Ingresa Items separados por comas: AT21-2, AT22-2</label>
      <textarea name="items" id="items" cols="30" rows="10" required></textarea>
        <button type="submit">Encontrar</button>
    </form>
</body>
</html>
<?php } ?>