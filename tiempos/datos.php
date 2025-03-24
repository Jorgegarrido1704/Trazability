<?php
require '../app/conection.php';
function formatTime($time) {
    $time = explode(':', $time);
    $min = $time[0];
    $sec = $time[1];
    $mil = $time[2];
    $totalSeconds = ($min * 60) + ($sec) + ($mil / 1000);
    return $totalSeconds;
}

$selct = mysqli_query($con,"SELECT * FROM `timeprocess` Order by partnum DESC ");
while($row = mysqli_fetch_array($selct)){
    $partnum = $row['partnum'];
    $process = $row['process'];
    $subprocess = $row['subProcess'];
    $obs = $row['obs'];
    $laps = $row['laps'];
    $lapstep=explode('-', $laps);
    $rep = count($lapstep);
    $reg=$continue=0;
    

    echo "<tr>
    <td>$partnum</td>
    <td>$process</td>
    <td>$subprocess</td>
    <td>$obs</td>
    <td>ciclos: $rep</td>
    </tr>";
    echo '<br>';
    foreach ($lapstep as $l) {
        $reg+=formatTime($l);
        $dato=formatTime($l);

        
        $continue++;
        echo "<tr>
        <td></td>
        <td>lap: </td>
        <td>$continue;</td>
        <td>$l</td>
        <td>$dato</td>
        </tr>";
        echo '<br>';
    }
    $fReg=$reg/$rep;
    echo "<tr>
    <td></td>
    <td></td>
    <td></td>    
    <td></td>
    <td>promedio: $fReg</td>

    </tr>";

    echo '<br>';
    echo '<br>';


}