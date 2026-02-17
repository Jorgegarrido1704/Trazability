<?php
require "../app/conection.php";

 $Droptodo=mysqli_query($con,"DELETE FROM `tiemposderuteo`");
// Obtener registros MPS
//$registrosMPS = mysqli_query($con, "SELECT pn, dq, qtymps FROM `datos_mps` WHERE pn='1001489409'OR pn='1001488939'OR pn='660925'OR pn='1002707335'OR pn='1001455147'OR pn='1003318064'OR pn='B222992'OR pn='1000109371'OR pn='16517630'OR pn='1003312301'OR pn='1000473129'OR pn='1002835774'OR pn='16516661'OR pn='1002835044'OR pn='1003544214'OR pn='660320'OR pn='16516612'OR pn='1000516139'OR pn='1001774292'OR pn='16517623'OR pn='16514775'OR pn='16514514'OR pn='1000230326'OR pn='1000312635'OR pn='1002719292'OR pn='16518485'OR pn='16518486'OR pn='1003359943'OR pn='1002186052'OR pn='1001073962' ");
$registrosMPS = mysqli_query($con,"SELECT DISTINCT pn_routing FROM `routing_models`");
while ($row = mysqli_fetch_assoc($registrosMPS)) {
    $pn = $row['pn_routing'];
    $qty = 1;

 $procesos = ['Cutting' => 0, 'Terminals' => 0, 'Assembly' => 0, 'Looming' => 0,  'Quality' => 0, 'Packaging' => 0];
$assetsProcess = ['Cutting' => 0, 'Terminals' => 0, 'Assembly' => 0, 'Looming' => 0, 'Quality' => 0, 'Packaging' => 0];
    // Obtener tiempos de ruteo
    $timeProcess = mysqli_query($con, "SELECT `work_routing`, QtyTimes, timePerProcess, setUp_routing 
                                       FROM `routing_models` 
                                    WHERE pn_routing = '$pn' ORDER BY work_routing ASC");
    if(mysqli_num_rows($timeProcess) > 0) {
      // echo "<h3>{$pn}</h3>";
   // echo "<table border='1' cellpadding='5' cellspacing='0' align='center' style='width: 60%;'>";
    while ($row = mysqli_fetch_assoc($timeProcess)) {
        if ($row['work_routing'] > 10000 && $row['work_routing'] < 10061) {$procesos['Cutting'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Cutting']+=$row['setUp_routing'];}
        if ($row['work_routing'] > 10060 && $row['work_routing'] < 10441) {$procesos['Terminals'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Terminals']+=$row['setUp_routing'];}
        if ($row['work_routing'] > 10440 && $row['work_routing'] < 11000) {$procesos['Assembly'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Assembly']+=$row['setUp_routing'];}
        if ($row['work_routing'] > 10999 && $row['work_routing'] < 11500) {$procesos['Looming'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Looming']+=$row['setUp_routing'];}
        if ($row['work_routing'] > 11500 && $row['work_routing'] < 11700) {$procesos['Quality'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Quality']+=$row['setUp_routing'];}
        if ($row['work_routing'] > 11700 && $row['work_routing'] < 12000) {$procesos['Packaging'] += ($row['QtyTimes'] * $row['timePerProcess']); $assetsProcess['Packaging']+=$row['setUp_routing'];}
    }
    $totalsPerProcess =$totalSetup  =$granTotalGeneral= 0;
    foreach ($procesos as $key => $valor) {
      //  echo "<tr><td>{$pn}</td><td>{$key}</td>";
       $valor = $procesos[$key]>0?round($procesos[$key]/60,3):0;
       $setupt = $assetsProcess[$key]>0?round($assetsProcess[$key]*1.40/60,3):0;

        $valor = round($valor*1.40,2);
        $totalsPerProcess+=$valor; 
        //$rowText = $valor;
      //  echo "<td style='font-weight:bold;'>{$rowText}</td>";
   // echo "</tr>"; 
        $insertar = mysqli_query($con,"INSERT INTO `tiemposderuteo`(`pn`, `work`, `processtime`, `setupTime`) VALUES ('{$pn}','{$key}','{$valor}','{$setupt}')");
}
    }

//echo "</table>";
}
?>
