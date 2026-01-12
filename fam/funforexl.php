<?php

require "family.php";  // Ensure this file contains necessary data and database connection
$grupoCom = [];
$comprarcion='H';

$stmtItems = $con->prepare("
    SELECT item 
    FROM datos 
    WHERE part_num = ?
      AND item NOT LIKE 'WTXL-%'
      AND item NOT LIKE 'WGXL-%'
      AND item NOT LIKE 'WSGX-%'
      AND item NOT LIKE 'LTP%'
      AND item NOT LIKE 'LW-%'
      AND item NOT LIKE 'TAPE-25'
");

$stmtCompare = $con->prepare("
    SELECT part_num 
    FROM datos 
    WHERE item = ? 
      AND part_num != ?
");


    
    foreach ($grupos[$comprarcion] as $partNum) {

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


?>
