<?php
require "../../app/conection.php";
require "timesReg.php";

if (!isset($_GET['np']) || empty($_GET['np'])) {
    header("location:../busqueda.php");
    exit;
}

$paramNp = $_GET['np'];
$paramNp = str_replace(' ', ',', $paramNp);
$datos = array_filter(explode(',', $paramNp));

if (empty($datos)) {
    header("location:../busqueda.php");
    exit;
}

// ==========================================
// 1. LIMPIEZA INICIAL (UN SOLO DELETE)
// ==========================================
$placeholders = implode(',', array_fill(0, count($datos), '?'));
$types = str_repeat('s', count($datos));

// Lista de todas las estaciones de trabajo involucradas en el sistema completo
$worksToDelete = [
    '10001', '11501', '11701', '10061', '10081', '10951', 
    '10960', '10381', '10431', '10361', '10401', '10341', 
    '10301', '11001', '10601', '11101', '10701', '11000', 
    '11050', '10801', '10901'
];
$worksList = "'" . implode("','", $worksToDelete) . "'";

$sqlDelete = "DELETE FROM routing_models WHERE pn_routing IN ($placeholders) AND work_routing IN ($worksList)";
$stmtDel = $con->prepare($sqlDelete);
$stmtDel->bind_param($types, ...$datos);
$stmtDel->execute();
$stmtDel->close();

// Arreglo maestro para acumular todas las inserciones del sistema
$bulkInserts = [];

// ==========================================
// 2. PROCESAMIENTO PRINCIPAL (UN SOLO BUCLE)
// ==========================================
foreach ($datos as $np) {
    $npEscaped = mysqli_real_escape_string($con, $np);
    
    // Variables y estructuras en memoria PHP para acumular datos de este PN
    $totalCircuitsCorte = 0; 
    $agrupadoTwist = [];     
    $terminalesConteo1 = []; 
    $terminalesConteo2 = []; 
    $terminalesSello1 = [];
    $terminalesSello2 = [];
    $totalSoldar = 0;
    $cantidadMangas = 0;
    $tipoSplice = [];

    // --- A. CONSULTA ÚNICA A LISTASCORTE POR NÚMERO DE PARTE ---
    $queryCorte = "SELECT cons, tipo, aws, color, tamano, terminal1, terminal2, dataFrom, dataTo FROM listascorte WHERE pn='$npEscaped'";
    $buscarCorte = mysqli_query($con, $queryCorte);

    while ($row = mysqli_fetch_assoc($buscarCorte)) {
        $cons = $row['cons'] ?? '';
        $tipo = $row['tipo'] ?? '';
        $aws = $row['aws'] ?? '';
        $color = $row['color'] ?? '';
        $tamano = floatval($row['tamano'] ?? 0);
        $t1 = $row['terminal1'] ?? '';
        $t2 = $row['terminal2'] ?? '';
        $df = $row['dataFrom'] ?? '';
        $dt = $row['dataTo'] ?? '';

        // 1. LÓGICA: REGISTRO DE CORTE (Estación 10001)
        if ($tamano > 0) {
            $totalCircuitsCorte++;
            $randomCorte = rand(0, count($corte) - 1);
            $tiempoCorte = $tamano * $corte[$randomCorte];
            $dataLabelCorte = mysqli_real_escape_string($con, 'Cutting cons ' . $cons . ' // Tipo:' . $tipo . '// AWG: ' . $aws . '// Color: ' . $color);
            
            $bulkInserts[] = "('$npEscaped','10001','FB036','$dataLabelCorte','1','$tiempoCorte','300')";
        }

        // 2. LÓGICA: TWIST (Estación 10061)
        if ($tamano > 0 && (strpos($cons, 'T') === 0)) { 
            $randomTwist = rand(0, count($twistMm) - 1);
            $tiempoTwist = $tamano * $twistMm[$randomTwist];
            $verificacion = "Twist " . explode("-", $cons)[0];

            if (!isset($agrupadoTwist[$verificacion])) {
                $agrupadoTwist[$verificacion] = [
                    'labels' => [$cons],
                    'tiempo' => $tiempoTwist
                ];
            } else {
                $agrupadoTwist[$verificacion]['labels'][] = $cons;
            }
        }

        // 3. LÓGICA: SELLOS EN TERMINAL 1 (Estación 10381)
        if (stripos($t1, 'Sello') !== false) {
            $termClean = $t1;
            if (($pos1 = strpos($termClean, '(')) !== false) $termClean = explode('(', $termClean)[1];
            if (($pos2 = strpos($termClean, ')')) !== false) $termClean = explode(')', $termClean)[0];
            $terminalesSello1[$termClean] = ($terminalesSello1[$termClean] ?? 0) + 1;
        }

        // 4. LÓGICA: SELLOS EN TERMINAL 2 (Estación 10381)
        if (stripos($t2, 'Sello') !== false) {
            $termClean = $t2;
            if (($pos1 = strpos($termClean, '(')) !== false) $termClean = explode('(', $termClean)[1];
            if (($pos2 = strpos($termClean, ')')) !== false) $termClean = explode(')', $termClean)[0];
            $terminalesSello2[$termClean] = ($terminalesSello2[$termClean] ?? 0) + 1;
        }

        // 5. LÓGICA: TERMINALES 1 (Estación 10081, 10951, 10960)
        $esTerminal1Valida = !empty($t1) && !preg_match('/^(Empalme|EMPALME|SPL|SPLICE|JUMPER|CONECTOR|Blunt|PORTA|CORTAR|N\/T|BLUNT)/i', $t1);
        if ($esTerminal1Valida) {
            $termClean1 = $t1;
            if (($pos = strpos($termClean1, '(')) !== false) $termClean1 = substr($termClean1, 0, $pos);
            
            $terminalesConteo1[$termClean1] = ($terminalesConteo1[$termClean1] ?? 0) + 1;

            if (strpos($termClean1, 'T3-') === false && strpos($termClean1, 'T4-') === false && !in_array($termClean1, $NoRequeridas)) {
                $random = rand(0, count($plugIn) - 1);
                $bulkInserts[] = sprintf("('%s','10951','pend','%s','1','%s','300')", $npEscaped, mysqli_real_escape_string($con, "Plug $termClean1 Terminal in $df"), $plugIn[$random]);

                $randomr = rand(0, count($routingBoardTime) - 1);
                $bulkInserts[] = sprintf("('%s','10960','pend','%s','1','%s','300')", $npEscaped, mysqli_real_escape_string($con, "Routing Wire in $df"), $routingBoardTime[$randomr]);
            }
        }

        // 6. LÓGICA: TERMINALES 2 (Estación 10081, 10951, 10960)
        $esTerminal2Valida = !empty($t2) && !preg_match('/^(Empalme|EMPALME|SPL|SPLICE|JUMPER|Jumper|CONECTOR|Blunt|PORTA|CORTAR|N\/T|BLUNT)/i', $t2);
        if ($esTerminal2Valida) {
            $termClean2 = $t2;
            if (($pos = strpos($termClean2, '(')) !== false) $termClean2 = substr($termClean2, 0, $pos);
            
            $terminalesConteo2[$termClean2] = ($terminalesConteo2[$termClean2] ?? 0) + 1;

            if (strpos($termClean2, 'T3-') === false && strpos($termClean2, 'T4-') === false && !in_array($termClean2, $NoRequeridas)) {
                $random = rand(0, count($plugIn) - 1);
                $bulkInserts[] = sprintf("('%s','10951','pend','%s','1','%s','300')", $npEscaped, mysqli_real_escape_string($con, "Plug $termClean2 Terminal in $dt"), $plugIn[$random]);

                $randomr = rand(0, count($routingBoardTime) - 1);
                $bulkInserts[] = sprintf("('%s','10960','pend','%s','1','%s','300')", $npEscaped, mysqli_real_escape_string($con, "Routing Wire in $dt"), $routingBoardTime[$randomr]);
            }
        }

        // 7. LÓGICA: SOLDADURA (Estación 10431)
        if (stripos($t1, 'SOLDAR') !== false || stripos($t2, 'SOLDAR') !== false) {
            $totalSoldar++;
        }

        // 8. LÓGICA: MANGAS (Estación 10361, 10401)
        if (stripos($t1, 'MANGA') !== false || stripos($t2, 'MANGA') !== false) {
            $cantidadMangas++;
        }

        // 9. LÓGICA: SPLICES (Estación 10341, 10301)
        if (!empty($df) && preg_match('/^(SPL|spl|Spl|splice|SPLICE|Empalme)/i', $df)) {
            $tipoSplice[$df] = ($tipoSplice[$df] ?? 0) + 1;
        }
        if (!empty($dt) && preg_match('/^(SPL|spl|Spl|splice|SPLICE|Empalme)/i', $dt)) {
            $tipoSplice[$dt] = ($tipoSplice[$dt] ?? 0) + 1;
        }
    }

    // --- B. PROCESAMIENTO E INSERCIÓN DE CORTES, TESTING Y PACKING ---
    if ($totalCircuitsCorte > 0) {
        if ($totalCircuitsCorte <= 10) {
            $testing = 60; $packing = 60;
        } else if ($totalCircuitsCorte <= 20) {
            $testing = 180; $packing = 120;
        } else if ($totalCircuitsCorte <= 50) {
            $testing = 240; $packing = 180;
        } else {
            $testing = 720; $packing = 300;
        }
        $labelTesting = 'Testing: ' . $totalCircuitsCorte . ' Circuits';
        $bulkInserts[] = "('$npEscaped','11501','Pend','$labelTesting','1','$testing','300')";
        $bulkInserts[] = "('$npEscaped','11701','Pend','Packing','1','$packing','300')";
    }

    // --- C. PROCESAMIENTO E INSERCIÓN DEL RESTO DE COMPONENTES DE LISTASCORTE ---
    
    // Volcar Agrupación Twist
    foreach ($agrupadoTwist as $prefijo => $info) {
        rsort($info['labels']); 
        $dataLabelTwist = mysqli_real_escape_string($con, $prefijo . " " . implode(' , ', $info['labels']));
        $bulkInserts[] = "('$npEscaped','10061','Pending','$dataLabelTwist','1','" . $info['tiempo'] . "','300')";
    }

    // Volcar Sellos 1 y 2
    $sealsGlobales = array_merge($terminalesSello1, $terminalesSello2);
    foreach ($sealsGlobales as $term => $qty) {
        $tiempoSetSeal = $setSealTime[rand(0, count($setSealTime) - 1)];
        $bulkInserts[] = "('$npEscaped','10381','Pend','" . mysqli_real_escape_string($con, $term) . "','$qty','$tiempoSetSeal','300')";
    }

    // Volcar Conteo Terminales 1 y 2 (Estación 10081)
    // Usamos el factor '4.084' para Terminales 1 y '3.084' para Terminales 2 como en tus scripts originales
    foreach ($terminalesConteo1 as $term => $qty) {
        $bulkInserts[] = "('$npEscaped','10081','FB-081','" . mysqli_real_escape_string($con, $term) . "','$qty','4.084','300')";
    }
    foreach ($terminalesConteo2 as $term => $qty) {
        $bulkInserts[] = "('$npEscaped','10081','FB-081','" . mysqli_real_escape_string($con, $term) . "','$qty','3.084','300')";
    }

    // Volcar Soldadura
    if ($totalSoldar > 0) {
        $bulkInserts[] = "('$npEscaped','10431','Pend','set tin point','$totalSoldar','" . $tinSet[rand(0, count($tinSet) - 1)] . "','300')";
    }

    // Volcar Mangas
    if ($cantidadMangas > 0) {
        $bulkInserts[] = "('$npEscaped','10361','Pend','Set HeadShrink in Terminals ','$cantidadMangas','" . $setHeadShrink[rand(0, count($setHeadShrink) - 1)] . "','300')";
        $bulkInserts[] = "('$npEscaped','10401','Pend','Burn Heatshrirnk w/headgun in Terminals ','$cantidadMangas','" . $burnHeatGun[rand(0, count($burnHeatGun) - 1)] . "','300')";
    }

    // Volcar Splices
    foreach ($tipoSplice as $key => $value) {
        $QtySpliceA = intval($value / 2) + intval($value % 2);
        $QtySpliceB = intval($value / 2);
        $timpoSetSplice = ($QtySpliceA * $setSplice[rand(0, count($setSplice) - 1)]) * $setSplice[rand(0, count($setSplice) - 1)];
        
        $bulkInserts[] = "('$npEscaped','10341','Pend','Create set for splice $QtySpliceA : $QtySpliceB','1','$timpoSetSplice','300')";
        $bulkInserts[] = "('$npEscaped','10301','FB110','splice set apply with machine','1','" . $applySpleceInMachine[rand(0, count($applySpleceInMachine) - 1)] . "','300')";
        $bulkInserts[] = "('$npEscaped','10361','Pend','Set HeadShrink in splice ','1','" . $setHeadShrink[rand(0, count($setHeadShrink) - 1)] . "','300')";
        $bulkInserts[] = "('$npEscaped','10401','Pend','Burn Heatshrirnk w/headgun in Splice ','1','" . $burnHeatGun[rand(0, count($burnHeatGun) - 1)] . "','300')";
    }

    // --- D. CONSULTA ÚNICA A LA TABLA DATOS (LOOM PROCESS) ---
    $queryLoom = "SELECT item, qty FROM datos WHERE part_num='$npEscaped' AND (item LIKE 'LTP%' OR item='TAPE-835' OR item='TAPE-25' OR item LIKE 'LW-%' OR item LIKE 'LSL%-%' OR item LIKE 'PA%-%')";
    $buscarLoom = mysqli_query($con, $queryLoom);

    $loomingTotal835 = 0; $tapingTotal835 = 0; $normalTaping835 = 0;
    $loomingTotal25 = 0;  $normalTaping25 = 0;
    $totalLabeling = 0; $braidTotal = 0; $totalTies = 0;

    while ($d = mysqli_fetch_assoc($buscarLoom)) {
        $item = $d['item'];
        $qty = floatval($d['qty']);
        $timeRand = $loomingTime[array_rand($loomingTime)];

        if ($item === 'TAPE-835') {
            $tapingTotal835 += ($timeRand * 1.2 * $qty) * 1.15;
        } elseif (strpos($item, 'LTP') === 0) {
            $loomingTotal835 += ($timeRand * 1.2) * $qty;
            $normalTaping835 += ($timeRand * 1.2) * $qty;
            $loomingTotal25 += $timeRand * $qty;
        }

        if ($item === 'TAPE-25')  $normalTaping25 += ($timeRand * $qty) * 1.25;
        if (strpos($item, 'LW-') === 0) $totalLabeling += ($qty * 5);
        if (strpos($item, 'LSL') === 0 && strpos($item, '-') !== false) $braidTotal += ($timeRand * $qty) * 1.33;
        if (strpos($item, 'PA') === 0 && strpos($item, '-') !== false) $totalTies += ($qty * 5.3 * 1.15);
    }

    // Inserciones de Loom
    if (($loomingTotal835 + $tapingTotal835 + $normalTaping835) > 0) {
        $tappingandlooming = ($loomingTotal835 + $tapingTotal835) * 1.1;
        $normalTaping835 *= 1.55;
        if ($loomingTotal835 > 0) $bulkInserts[] = sprintf("('%s','11000','Pend','looming','1','%d','150')", $npEscaped, max(30, round($loomingTotal835)));
        if ($tappingandlooming > 0) $bulkInserts[] = sprintf("('%s','11001','Pend','Taping/Looming','1','%d','150')", $npEscaped, max(30, round($tappingandlooming)));
        if ($normalTaping835 > 0) $bulkInserts[] = sprintf("('%s','10901','Pend','Taping Body/Assembly','1','%d','150')", $npEscaped, max(30, round($normalTaping835)));
    }

    if ($loomingTotal25 <= 0 && $normalTaping25 > 0) {
        $normalTaping25 = round(($normalTaping25 * 2.5), 2);
        $bulkInserts[] = sprintf("('%s','10901','Pend','Taping Body/Assembly','1','%d','150')", $npEscaped, max(30, round($normalTaping25)));
    }

    if ($totalLabeling > 0) $bulkInserts[] = sprintf("('%s','11050','Pend','labeling','1','%d','150')", $npEscaped, max(30, round($totalLabeling)));
    if ($braidTotal > 0) $bulkInserts[] = sprintf("('%s','11101','Pend','Braiding','1','%d','150')", $npEscaped, max(30, round($braidTotal)));
    if ($totalTies > 0) $bulkInserts[] = sprintf("('%s','10801','Pend','Add Ties','1','%d','150')", $npEscaped, max(30, round($totalTies)));
}

// ==========================================
// 3. INSERCIÓN MASIVA FINAL EN BLOQUES
// ==========================================
if (!empty($bulkInserts)) {
    $chunks = array_chunk($bulkInserts, 200);
    foreach ($chunks as $chunk) {
        $sqlInsert = "INSERT INTO `routing_models` (`pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) VALUES " . implode(',', $chunk);
        mysqli_query($con, $sqlInsert);
    }
}

// Redirección final del sistema completo al menú/registro principal
header("location:../busqueda.php");
exit;