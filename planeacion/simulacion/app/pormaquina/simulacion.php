<?php
require '../conection.php';

header('Content-Type: application/json');

ini_set('display_errors', 0);
error_reporting(E_ALL);

set_exception_handler(function($e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    exit;
});

function clasificarMaquina($calibre, $tinta, $cons, $term1, $term2) {
    $awg = intval(preg_replace('/[^0-9]/', '', (string)$calibre));
    $tintaUpper = strtoupper(trim((string)$tinta));
    $consUpper  = strtoupper(trim((string)$cons));
    $term1Upper = strtoupper((string)$term1);
    $term2Upper = strtoupper((string)$term2);

    $esBlanca   = (strpos($tintaUpper, 'BLAN') !== false || strpos($tintaUpper, 'WHT') !== false || strpos($tintaUpper, 'WHITE') !== false);
    $esNegra    = (strpos($tintaUpper, 'NEG') !== false || strpos($tintaUpper, 'BLK') !== false || strpos($tintaUpper, 'BLACK') !== false);
    $tieneSello = (strpos($term1Upper, 'SELLO') !== false || strpos($term2Upper, 'SELLO') !== false);
    $esConsumoC = (strpos($consUpper, 'C') === 0);

    if ($esConsumoC || in_array($awg, [1, 2, 4, 6, 8])) {
        return 'MCUT-7';
    }
    if ($tieneSello) {
        return 'MCUT-3';
    }
    if (in_array($awg, [10, 12])) {
        return 'MCUT-1';
    }
    if (in_array($awg, [14, 16])) {
        return $esBlanca ? 'MCUT-2' : 'MCUT-5';
    }
    if (in_array($awg, [18, 20, 22, 24])) {
        return $esBlanca ? 'MCUT-4' : 'MCUT-6';
    }
    return 'MCUT-7';
}

try {
    if (!isset($con) || !$con) {
        throw new \Exception("Conexión a BD no disponible.");
    }

    $inputJSON = file_get_contents('php://input');
    $dataInput = json_decode($inputJSON, true);

    $itemsSimulados = $dataInput['items'] ?? [];
    $maquinaFiltro  = $dataInput['maquina'] ?? 'todas';

    if (empty($itemsSimulados)) {
        echo json_encode(["status" => "empty", "debug" => "No se recibieron items en el payload", "data" => []]);
        exit;
    }

    $pnList = [];
    $pnDataMap = [];

    foreach ($itemsSimulados as $item) {
        $pn         = strtoupper(trim($item[1] ?? ''));
        $wo         = trim($item[2] ?? '');
        $qty        = intval($item[3] ?? 0);
        $programado = intval($item[4] ?? 1); // Forzar a int

        // Aceptamos cualquier item con PN y cantidad > 0 que esté activo (1)
        if ($programado === 1 && !empty($pn) && $qty > 0) {
            $pnEscaped = mysqli_real_escape_string($con, $pn);
            $pnList[] = "'$pnEscaped'";

            if (!isset($pnDataMap[$pn])) {
                $pnDataMap[$pn] = [];
            }
            $pnDataMap[$pn][] = [
                'wo' => $wo,
                'qty' => $qty
            ];
        }
    }

    if (empty($pnList)) {
        echo json_encode(["status" => "empty", "debug" => "Ningún PN cumple condición programado=1 y qty>0", "data" => []]);
        exit;
    }
    $herramentales=[
        ['1','DT1-1'],
        ['2','DT1-10'],
        ['3','DT1-13'],
        ['4','TT2-9'],
        ['5','DT2-802'],
        ['6','ET2-27'],
        ['7','ET1-35'],
        ['8','DT1-61'],
        ['9','DT1-34'],
        ['10','DT1-53'],
        ['11','DT1-57'],
        ['12','DT1-59'],
        ['13','DT1-7'],
        ['14','DT1-78'],
        ['15','DT1-8'],
        ['16','DT2-10'],
        ['17','DT2-100'],
        ['18','DT2-103'],
        ['19','DT2-45'],
        ['20','DT2-115'],
        ['21','DT2-116'],
        ['22','DT2-12'],
        ['23','DT2-135'],
        ['24','DT2-17'],
        ['25','DT2-16'],
        ['26','TT1-310'],
        ['27','DT2-15'],
        ['28','DT2-194'],
        ['29','DT2-22'],
        ['30','DT2-3'],
        ['31','DT2-74'],
        ['32','DT2-71'],
        ['33','DT2-37'],
        ['34','DT2-38'],
        ['35','DT1-5'],
        ['36','TT2-14'],
        ['37','DT1-5'],
        ['38','DT1-17'],
        ['39','DT1-31'],
        ['40','DT2-58'],
        ['41','DT2-71'],
        ['42','DT2-802'],
        ['43','DT2-94'],
        ['44','DT2-99'],
        ['45','TT2-14'],
        ['46','ET2-27'],
        ['47','ET2-34'],
        ['48','ET1-36'],
        ['49','ET1-37'],
        ['50','TT1-2'],
        ['51','DT1-3'],
        ['52','ET2-39'],
        ['53','ET2-6'],
        ['54','ET1-5'],
        ['55','FT1-1'],
        ['56','DT2-119'],
        ['57','FT2-5'],
        ['58','GNT1-1'],
        ['59','GNT2-1'],
        ['60','YT2-13'],
        ['61','MT1-119'],
        ['62','DT2-21'],
        ['63','MT1-2'],
        ['64','DT1-57'],
        ['65','YT2-26'],
        ['66','TT2-308'],
        ['67','MT1-4'],
        ['68','MT1-61'],
        ['69','MT2-102'],
        ['70','MT2-136'],
        ['71','MT2-3'],
        ['72','MT2-3'],
        ['73','YT2-28'],
        ['74','MT2-46'],
        ['75','MT2-55'],
        ['76','ET2-27'],
        ['77','MT2-71'],
        ['78','MT2-93'],
        ['79','PT3-10'],
        ['80','PT3-15'],
        ['81','TT2-900'],
        ['82','MT4-23'],
        ['83','TT3-104'],
        ['84','MT4-24'],
        ['85','MT4-24'],
        ['86','MT3-86'],
        ['87','PT3-7'],
        ['88','PT3-8'],
        ['89','ET1-35'],
        ['90','TT1-120'],
        ['91','TT1-281'],
        ['92','TT2-311'],
        ['93','DT2-36'],
        ['94','TT2-9'],
        ['95','TT2-231'],
        ['96','TT2-25'],
        ['97','DT1-78'],
        ['98','TT2-180'],
        ['99','TT2-65'],
        ['100','TT2-21'],
        ['101','TT2-3'],
        ['102','TT2-22'],
        ['103','TT2-22'],
        ['104','DT2-6'],
        ['105','TT2-102'],
        ['106','TT2-267'],
        ['107','TT2-55'],
        ['108','TT2-279'],
        ['109','TT2-308'],
        ['110','DT2-32'],
        ['111','TT2-9'],
        ['112','DT2-245'],
        ['113','TT2-217'],
        ['114','MT2-136'],
        ['115','TT2-64'],
        ['116','TT2-313'],
        ['117','TT2-33'],
        ['118','TT2-35'],
        ['119','TT2-431'],
        ['120','TT2-313'],
        ['121','TT2-901'],
        ['122','MT1-2'],
        ['123','DT2-22'],
        ['124','MT2-118'],
        ['125','TT1-53'],
        ['126','TT2-22'],
        ['127','TT1-103'],
        ['128','DT1-53'],
        ['129','DT1-83'],
        ['130','DT2-123'],
        ['131','DT2-119'],
        ['132','DT2-111'],
        ['133','DT2-103'],
        ['134','DT2-139'],
        ['135','DT2-17'],
        ['136','DT2-21'],
        ['137','DT2-31'],
        ['138','DT2-32'],
        ['139','DT2-39'],
        ['140','DT2-44'],
        ['141','DT2-55'],
        ['142','DT2-42'],
        ['143','DT2-66'],
        ['144','DT2-67'],
        ['145','DT2-79'],
        ['146','DT2-8'],
        ['147','DT2-690'],
        ['148','DT2-95'],
        ['149','ET2-30'],
        ['150','ET1-44'],
        ['151','ET1-5'],
        ['152','ET2-26'],
        ['153','FT1-7'],
        ['154','MT2-1'],
        ['155','DT2-74'],
        ['156','MT2-3'],
        ['157','MT2-55'],
        ['158','PT2-1'],
        ['159','PT2-2'],
        ['160','TT2-11'],
        ['161','TT2-12'],
        ['162','TT2-137'],
        ['163','TT2-140'],
        ['164','TT2-114'],
        ['165','TT2-157'],
        ['166','TT2-187'],
        ['167','TT2-217'],
        ['168','TT2-255'],
        ['169','TT2-27'],
        ['170','TT2-310'],
        ['171','TT2-311'],
        ['172','TT2-315'],
        ['173','TT2-4'],
        ['174','TT2-63'],
        ['175','TT2-65'],
        ['176','TT2-66'],
        ['177','TT2-72'],
        ['178','TT2-90'],
        ['179','829-12077412-MR'],
        ['180','829-12048254-MR'],
        ['181','829-12124581-MR'],
        ['182','829-12077413-MR'],
        ['183','1212582-MR'],
        ['184','12089188'],
        ['185','DT2-74'],
        ['186','DT1-775'],
        ['187','FT2-4'],
        ['188','DT1-134'],
        ['189','YT2-42'],
        ['190','DT1-74'],
        ['191','DT2-126'],
        ['192','YT2-26'],
        ['193','YT1-13'],
        ['194','DT1-27'],
        ['195','DT2-85'],
        ['196','MT2-48'],
        ['197','DT2-802'],
        ['198','MTAT1-1'],
        ['199','TT3-137'],
        ['200','TT1-74'],
        ['3','DT1-3'],
        ['4','TT2-23'],
        ['8','DT1-14'],
        ['18','DT2-132'],
        ['19','DT2-11'],
        ['22','DT2-52'],
        ['24','DT2-94'],
        ['25','DT2-132'],
        ['26','TT2-311'],
        ['29','DT2-59'],
        ['35','DT2-4'],
        ['37','DT2-4'],
        ['39','DT2-14'],
        ['45','TT2-152'],
        ['46','ET1-5'],
        ['47','ET1-35'],
        ['51','DT1-87'],
        ['60','MT1-20'],
        ['64','DT1-54'],
        ['67','MT2-3'],
        ['70','MT2-132'],
        ['77','MT2-92'],
        ['82','PT1-17'],
        ['83','PT3-30'],
        ['84','MT3-87'],
        ['86','MT2-24'],
        ['89','ET2-27'],
        ['92','TT1-310'],
        ['95','TT2-15'],
        ['98','TT2-221'],
        ['99','TT2-58'],
        ['100','TT2-218'],
        ['101','TT2-219'],
        ['102','TT2-15'],
        ['107','TT2-127'],['110','DT2-111'],['111','TT2-23'],['112','TT2-170'],['113','TT2-309'],['115','TT2-18'],['117','TT2-60'],['123','DT2-59'],['127','TT1-102'],['132','DT2-112'],['135','DT2-128'],['139','DT2-127'],['148','FT2-12'],['149','AT1-9'],['152','ET1-37'],
        ['153','YT2-28'],['154','TT2-209'],['156','MT2-55'],['157','MT1-4'],['164','TT2-152'],['171','TT1-310'],['178','TT2-51'],['24','DT2-128'],['60','MT2-136'],['64','DT1-58'],['67','MT1-54'],['82','MT3-146'],['84','TT1-307'],['86','MT3-75'],['89','ET2-33'],['95','PT1-17'],['98','TT1-257'],['99','TT2-218'],
        ['101','TT2-64'],['107','FT2-5'],['113','TT2-310'],['115','TT2-31'],['127','TT2-111'],['152','ET1-25'],['154','TT2-224'],['156','MT1-54'],['178','TT2-32'],['60','MT1-131'],['64','DT1-4'],['67','MT1-78'],['82','TT3-14'],['84','MT3-117'],['86','YT3-75'],
        ['89','AT1-5'],['95','TT2-167'],['101','TT2-60'],['154','MT1-2'],['60','MT2-132'],['82','MT4-24'],['84','TT3-158'],['86','TT3-51'],['89','ET2-28'],['95','TT2-22'],['101','TT2-19'],['82','MT3-41'],['84','MT4-23'],['86','TT3-117'],['89','ET2-34'],['86','MT3-126'],['89','DT1-134'],['89','ET1-36'],['89','ET1-44'],
    ];

    $pnInQuery = implode(',', array_unique($pnList));

    // Búsqueda insensible a espacios y mayúsculas en listascorte
    $qry = "SELECT 
                id, TRIM(UPPER(pn)) as pn_clean, pn, cons, tipo, aws, color, tamano,
                strip1, terminal1, strip2, terminal2,
                conector, colorTinta, dist_stamp
            FROM listascorte
            WHERE TRIM(UPPER(pn)) IN ($pnInQuery)
              AND (tamano > 0 OR tamano IS NOT NULL)
            ORDER BY aws ASC, color ASC, tipo ASC,
            CASE
                            WHEN terminal2 LIKE CONCAT('%', terminal1, '%') THEN 0
                            ELSE 1
                         END";

    $res = mysqli_query($con, $qry);
    if (!$res) {
        throw new \Exception("Error en SQL: " . mysqli_error($con));
    }

    $calibres = [];
    $fechaHoy = date('Y-m-d');
    $diaBloqueDefecto = 1;

    while ($row = mysqli_fetch_assoc($res)) {
        $app1="";
        $app2="";
        $banco1 = "";
        $banco2 = "";
        $pnKey = $row['pn_clean'];
        if (!isset($pnDataMap[$pnKey])) continue;

        $strip1 = $row['strip1'];
        $strip1 = ($strip1 === null) ? 0 : (($strip1 < 1.5) ? floatval($strip1) * 25.4 : floatval($strip1));
        $strip1 = round((float)$strip1, 2);

        $strip2 = $row['strip2'];
        $strip2 = ($strip2 === null) ? 0 : (($strip2 < 1.5) ? floatval($strip2) * 25.4 : floatval($strip2));
        $strip2 = round((float)$strip2, 2);


        $maquinaAsignada = clasificarMaquina(
            $row['aws'], 
            $row['colorTinta'], 
            $row['cons'], 
            $row['terminal1'], 
            $row['terminal2']
        );

        if ($maquinaFiltro !== 'todas' && $maquinaAsignada !== $maquinaFiltro) {
            continue;
        }

        foreach ($pnDataMap[$pnKey] as $orden) {
            $qtyMultiplicada = $orden['qty'];
            $time_ruteo_seg  = round((2.92 * $qtyMultiplicada) + 180, 2);
            $minutos         = round(($time_ruteo_seg / 60), 2);
            $terminal1 = $row['terminal1'];
            $terminal2 = $row['terminal2'];
            $term1=strpos($terminal1, ' ') !== false ? explode(' ', $terminal1)[0] : $terminal1;
            $term2=strpos($terminal2, ' ') !== false ? explode(' ', $terminal2)[0] : $terminal2;
            $bancos= [
                '1'=>['TT1-310','DT2-6','TT2-72','TT1-18','TT1-53','ET2-26'],
                '2'=>['DT2-74'],
                '3'=>['DT2-58','DT2-36','DT1-87','DT1-54','DT2-103','TT2-47','TT2-90','DT2-132','DT2-116','TT1-2','DT2-47','MT1-2','TT2-32','DT2-112','TT2-51'],
                '4' =>['TT2-311','DT2-16','ET1-5','TT2-267','TT2-901','DT2-70','PT2-1','PT2-2','MT2-46','DT2-67'],
                '5'=>['ET1-35','DT1-27','YT2-42','MT1-20','DT1-8','MT1-131','DT2-100','MT2-132','DT1-78','TT2-276','MT2-136','TT2-127','YT2-13','FT2-5','TT2-102','TT2-235','MT2-71'],
                '6'=>['DT2-4','DT1-5','DT2-139','DT2-8'],
                '7'=>['DT1-775','DT1-134','YT2-26','TT2-103','TT2-63','TT1-102','ET1-36','TT2-187','FT2-12','TT2-111','TT2-279','TT1-110','DT1-59','DT2-95'],
                '8'=>['DT2-11','DT2-45','TT2-209','MT4-24','MT4-23','MT1-4','MT1-119','TT2-224','MT3-86','YT2-28','MT1-54','TT3-117','DT1-57','MT3-41','MT2-3','TT3-14','TT3-51','DT2-79','MT2-102','MT3-126','MT1-78','PT1-17','MT3-75','MT2-1'],
                '9'=>['ET2-27','DT2-14','TT2-309','TT2-180','TT2-219','TT2-310','TT1-74','TT2-60','DT1-83','TT2-221','TT2-167','TT2-15','DT2-42','TT2-64','TT2-271','TT2-308','TT1-257','TT2-22','TT2-217','TT2-3','TT2-231'],
                '10'=>['TT2-14','DT2-690','MT2-48','DT1-61','DT1-3','DT1-13','MT2-55','TT3-158','TT2-33','TT1-307','DT2-50','MT3-87','DT1-14','DT2-35','TT2-152','ET1-37','DT1-53'],
                '11'=>['YT1-13','DT1-31','DT2-802','DT1-74','DT2-85','TT2-9','DT2-12','DT1-34','TT2-23','ET2-6','TT2-222','DT2-52','DT2-111','DT2-1','ET2-34','DT2-32'],
                '12'=>['DT2-15','DT1-7','TT2-139','DT2-119','DT2-10','DT2-21','DT2-127','AT1-9','MT2-31','TT2-21','TT3-104','PT3-30'],
                '13'=>['DT2-17','MT2-118','DT2-59','DT2-22','DT2-126','DT2-128','TT2-58','TT2-218','TT2-27','DT2-94','TT2-900','TT2-65','DT2-66','TT2-31'],
            ];

            foreach ($bancos as $bancoNum => $terminales) {
                if (in_array($term1, $terminales) ) {
                    $banco1 =  $bancoNum;
                }
                if (in_array($term2, $terminales) ) {
                    $banco2 =  $bancoNum;
                }
            }

            $calibres[] = [
                'id'             => $row['id'],
                'pn'             => $row['pn'],
                'calibre'        => $row['aws'],
                'consumo'        => $row['cons'],
                'tipo'           => $row['tipo'],
                'color'          => $row['color'],
                'tamano'         => round((float)$row['tamano'], 2),
                'Qty'            => $qtyMultiplicada,
                'min'            => $minutos,
                'tinta'          => $row['colorTinta'],
                'terminal1'      => $row['terminal1'],
                'terminal2'      => $row['terminal2'],
                'wo'             => $orden['wo'],
                'codigo'         => $orden['wo'] . '-' . $row['cons'],
                'strip1'         => $strip1,
                'strip2'         => $strip2,
                'conector'       => $row['conector'],
                'estampado'      => $row['dist_stamp'] ?? '',
                'maquina'        => $maquinaAsignada,
                'fecha_asignada' => $fechaHoy,
                'dia_bloque'     => $diaBloqueDefecto,
                'banco1'         => $banco1,
                'banco2'         => $banco2,
            ];
        }
    }

    echo json_encode($calibres);

} catch (\Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>