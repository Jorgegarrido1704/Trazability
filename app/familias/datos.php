<?php 

require "../conection.php";
$i=0;
$datos=[];

$registros=mysqli_query($con,"SELECT * FROM  timeprocess WHERE registrado = 'No Aun' ORDER BY partnum, process,subProcess DESC");
while($row=mysqli_fetch_array($registros)){
    
    $parte=$row['partnum'];
    $proccess=$row['process'];
    $subProccess=$row['subProcess'];
    $laps=$row['laps'];
    $obs=$row['obs'];
    if(strpos($laps,"-")){
    $lap=list($lap)=explode("-",$laps);
    foreach($lap as $l){
        $datos[$i]=[$parte,$proccess,$subProccess,$l,$obs];
        $i++; 
    }

    
    }else{   
    
    $datos[$i]=[$parte,$proccess,$subProccess,$l,$obs];
    $i++;
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registros</title>
</head>
<body>
    <table>
        <tr>
            <th>partnum</th>
            <th>proccess</th>
            <th>subProcess</th>
            <th>laps</th>
            <th>obs</th>
        </tr>
        </tbody>
        
            
        <?php 
        foreach($datos as  $value  ){?>
        <tr>
        <td><?php echo $value[0];?></td>
        <td><?php echo $value[1];?></td>
        <td><?php echo $value[2];?></td>
        <td><?php echo $value[3];?></td>
        <td><?php echo $value[4];?></td>
        </tr>
       <?php  } ?>  
       </tbody>             
    </table>
</body>
</html>