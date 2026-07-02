<?php
require "../app/conection.php";

$datos = isset($_GET['grupo']) ? $_GET['grupo'] : '';
if($datos != ''){
    $exclusivosNumerosdeparte='';
    $rows = 0;
    $buscarGrupo = mysqli_query($con, "SELECT  customer FROM workschedule WHERE pn = '$datos' LIMIT 1");
    $cliente = mysqli_fetch_array($buscarGrupo);
    $customer = $cliente['customer'];
    $buscarFamilias = mysqli_query($con, "SELECT NumPart FROM registro WHERE cliente = '$customer' ");
    $numerodeRows= mysqli_num_rows($buscarFamilias); 
    while ($rowFamilias = mysqli_fetch_array($buscarFamilias)) {
        $rows++;
        $partNum = $rowFamilias['NumPart'];
        $grupos[$customer][] = $partNum;
        if($rows >= $numerodeRows){
            $exclusivosNumerosdeparte .= "'$partNum'";
        }else{
            $exclusivosNumerosdeparte .= "'$partNum',";
        }
    }


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
      AND part_num IN ($exclusivosNumerosdeparte)
");


    
    foreach ($grupos[$customer] as $partNum) {

        $items = [];
        $compatibilidad = [];
   // echo $partNum."-".$grupo."<br>";
        /** Obtener items del part number */
        $stmtItems->bind_param("s", $partNum);
        $stmtItems->execute();
        $resultItems = $stmtItems->get_result();

        while ($row = $resultItems->fetch_assoc()) {
            $items[] = $row['item'];
        }

        $totalItems = count($items);
        if ($totalItems === 0) {
            continue;
        }

        /** Comparar items contra otros part numbers */
        foreach ($items as $item) {
            $stmtCompare->bind_param("ss", $item, $partNum);
            $stmtCompare->execute();
            $resultCompare = $stmtCompare->get_result();

            while ($row = $resultCompare->fetch_assoc()) {
                $pn = $row['part_num'];
                $compatibilidad[$pn] = ($compatibilidad[$pn] ?? 0) + 1;
            }
        }

        /** Calcular porcentaje de compatibilidad */
        foreach ($compatibilidad as $pn => $matches) {
            $porcentaje = round(($matches / $totalItems) * 100, 2);

            if ($porcentaje >= 60) {
                $grupoCom[$partNum][$pn] = $porcentaje;
               echo "<br>$partNum → $pn : $porcentaje%";
            }
        }
    }


$stmtItems->close();
$stmtCompare->close();

}else{
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="GET" action="cuateRegistro.php">
        <label for="grupo">Ingrese el numero de parte:</label>
        <input type="text" name="grupo" id="grupo">
        <button type="submit">Buscar</button>
    </form>
</body>
</html>
<?php } ?>