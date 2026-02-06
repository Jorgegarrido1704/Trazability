<?php
require "../app/conection.php";

$currentWeek = date("W");
$pnRegistros = [];
$allWeeks = [];


// Obtener registros MPS
//$registrosMPS = mysqli_query($con, "SELECT pn, dq, qtymps FROM `datos_mps` WHERE pn='1001489409'OR pn='1001488939'OR pn='660925'OR pn='1002707335'OR pn='1001455147'OR pn='1003318064'OR pn='B222992'OR pn='1000109371'OR pn='16517630'OR pn='1003312301'OR pn='1000473129'OR pn='1002835774'OR pn='16516661'OR pn='1002835044'OR pn='1003544214'OR pn='660320'OR pn='16516612'OR pn='1000516139'OR pn='1001774292'OR pn='16517623'OR pn='16514775'OR pn='16514514'OR pn='1000230326'OR pn='1000312635'OR pn='1002719292'OR pn='16518485'OR pn='16518486'OR pn='1003359943'OR pn='1002186052'OR pn='1001073962' ");
$registrosMPS = mysqli_query($con,"SELECT DISTINCT pn FROM `po`");
while ($row = mysqli_fetch_assoc($registrosMPS)) {
    $pn = $row['pn'];
    $qty = 1;

 $procesos = ['Cutting' => 0, 'Terminals' => 0, 'Assembly' => 0, 'Looming' => 0,  'Quality' => 0, 'Packaging' => 0];
$assetsProcess = ['Cutting' => 0, 'Terminals' => 0, 'Assembly' => 0, 'Looming' => 0, 'Quality' => 0, 'Packaging' => 0];
    // Obtener tiempos de ruteo
    $timeProcess = mysqli_query($con, "SELECT `work_routing`, QtyTimes, timePerProcess, setUp_routing 
                                       FROM `routing_models` 
                                    WHERE pn_routing = '$pn' ORDER BY work_routing ASC");
    if(mysqli_num_rows($timeProcess) > 0) {
      // echo "<h3>{$pn}</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0' align='center' style='width: 100%;'>";

    while ($row = mysqli_fetch_assoc($timeProcess)) {
        if ($row['work_routing'] > 10000 && $row['work_routing'] < 10061) {$procesos['Cutting'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Cutting']++;}
        if ($row['work_routing'] > 10060 && $row['work_routing'] < 10441) {$procesos['Terminals'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Terminals']++;}
        if ($row['work_routing'] > 10440 && $row['work_routing'] < 11000) {$procesos['Assembly'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Assembly']++;}
        if ($row['work_routing'] > 10999 && $row['work_routing'] < 11500) {$procesos['Looming'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Looming']++;}
        if ($row['work_routing'] > 11500 && $row['work_routing'] < 11700) {$procesos['Quality'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Quality']++;}
        if ($row['work_routing'] > 11700 && $row['work_routing'] < 12000) {$procesos['Packaging'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Packaging']++;}
    }
    $totalsPerProcess =$totalSetup  =$granTotalGeneral= 0;
    foreach ($procesos as $key => $valor) {
        echo "<tr><td>{$pn}</td><td>{$key}</td>";
       $valor = $procesos[$key]>0?round($procesos[$key]/60,2):0;
        $valor = round($valor*1.20,2);
        $totalsPerProcess+=$valor;
        
        $rowText = $valor;
        echo "<td style='font-weight:bold;'>{$rowText}</td>";
      /*  echo "<td>{$pn}-setUp-{$key}</td>";
        $setup=$assetsProcess[$key]>0?round(($assetsProcess[$key]*300)/60,2):0;
        $totalSetup+=$setup;
        echo "<td>{$setup} min</td>";
        $totaGeneral=$valor+$setup;
        echo "<td>{$totaGeneral} min</td>";
        echo "</tr>";
        $granTotalGeneral+=$totaGeneral;
      //  $totalsPerProcess[$key]['total'] += $rowTotal;
    }
    echo "<tr><td style='font-weight:bold;'>Total</td>";
    echo "<td style='font-weight:bold;'>{$totalsPerProcess} min</td>";
      echo "<td style='font-weight:bold;'></td>";
    echo "<td style='font-weight:bold;'>{$totalSetup} min</td>";
    echo "<td style='font-weight:bold;'>{$granTotalGeneral} min</td>";
    echo "</tr>";*/
    echo "</tr>";
    }
}

echo "</table>";
}
?>
