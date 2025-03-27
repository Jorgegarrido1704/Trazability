<?php


function excels($g) {
    
 require_once "family.php";
$subFamiliesA = $grupos[$g];
$x= 0;
foreach ($subFamiliesA as $subA) {
    $i =0;
    $items = [];
    $compatativo = [];
    
    // Consulta para obtener los datos
    $buscar = mysqli_query($con, "SELECT * FROM datos 
                                  WHERE part_num = '$subA' 
                                  AND item NOT LIKE 'WTXL-%' 
                                  AND item NOT LIKE 'WGXL-%' 
                                  AND item NOT LIKE 'WSGX-%' 
                                  AND item NOT LIKE 'LTP%' 
                                  AND item NOT LIKE 'LW-%' 
                                  AND item NOT LIKE 'TAPE-25'");
    
    if (!$buscar) {
        echo "Error en la consulta: " . mysqli_error($con);
        continue;
    }

    // Procesar resultados de la primera consulta
    while ($row = mysqli_fetch_array($buscar)) {
        $item = $row['item'];
        $items[$subA][$i] = $item;
        $i++;
    }

    $totalItems = mysqli_num_rows($buscar); // Total de ítems para calcular el porcentaje

    // Procesar los ítems obtenidos
    foreach ($items as $key => $valueArray) {
        foreach ($valueArray as $value) {
            $buscarcomp1 = mysqli_query($con, "SELECT * FROM datos WHERE item = '$value' AND part_num != '$subA'");
            
            if (!$buscarcomp1) {
                echo "Error en la consulta: " . mysqli_error($con);
                continue;
            }

            while ($row1 = mysqli_fetch_array($buscarcomp1)) {
                $part_num = $row1['part_num'];

                // Agregar al array comparativo
                if (isset($compatativo[$key][$part_num])) {
                    $compatativo[$key][$part_num] += 1;
                } else {
                    $compatativo[$key][$part_num] = 1;
                }
            }
        }
    }

    // Filtrar y mostrar resultados con compatibilidad > 75%
 //   echo "<h3> $subA (Compatibility > 75%)</h3>";
   // echo "<pre>";
    
    foreach ($compatativo as $key => $comparisons) {
        foreach ($comparisons as $part_num => $count) {
            $compatibility = ($count / $totalItems) * 100;
            if ($compatibility >= 75) {
               // echo " $part_num Compatibility: " . round($compatibility, 2) . "%\n";
               $grupoCom[$x][0]=$key;
               $grupoCom[$x][1]=$part_num;
               $grupoCom[$x][2]=round($compatibility, 2);
               $x++;
            }else if ($compatibility >= 60 and $compatibility < 75) {
                $grupoCom[$x][0]=$key;
                $grupoCom[$x][1]=$part_num;
                $grupoCom[$x][2]=round($compatibility, 2);
                $x++;
            }
        }
    }
    //echo "</pre>";
}
return $grupoCom;
}