<?php
session_start();
$cortetimeh=0;
$libetimeh=0;
$ensatimeh= 0;
$enbatimeh= 0;
$loomtimeh=0;
$qtimeh=0;
$ttime=0;
$tc=0;
round($tlib=0);
$tens=0;
$tlo=0;
$tql=0;
$ttl=0;
$totalharness=0;
date_default_timezone_set("America/Mexico_City");
$actualDate=date("d-m-Y H:i");
$today=strtotime($actualDate);
require "../app/conection.php";

$parte=isset($_POST['pn']) ? $_POST['pn'] :"";


$serachTimes="SELECT * FROM retiradad WHERE np='$parte'  ";
$qrySearch=mysqli_query($con,$serachTimes);
$numrowsparcial=mysqli_num_rows($qrySearch);
//echo $numrowsparcial;
$i=0;
while($rowinfo=mysqli_fetch_array($qrySearch)){
    
    $info[$i]=$rowinfo['codigo'];
    $qty=$rowinfo['qty'];
   // echo "<br>".$qty."<-->".$parte."<-->".$info[$i];
$totalharness=$totalharness+$qty;
$i++;
}
//echo "<br>".$totalharness;
for($i=0;$i<$numrowsparcial;$i++){
    $codigo=$info[$i];
   $serachTimes="SELECT * FROM tiempos WHERE info='$codigo' ";
$qrySearch=mysqli_query($con,$serachTimes);
while($rowSearch=mysqli_fetch_array($qrySearch)){
 $plan=$rowSearch["planeacion"];
 $cort=$rowSearch["corte"];
 $libe=$rowSearch['liberacion'];
 $ensa=$rowSearch['ensamble'];
 $loom=$rowSearch['loom'];
 $qlity=$rowSearch['calidad'];
 $emba=$rowSearch['embarque'];
 $plan=strtotime($plan);
 $cort=strtotime($cort);
 $libe=strtotime($libe);
 $ensa= strtotime($ensa);
 $loom=strtotime($loom);
$qlity=strtotime($qlity);
$emba=strtotime($emba);
if($cort!='' and $plan!=''){ 
$cortetimeh=round((floor(abs($cort-$plan)/60)));
$tc=$tc+$cortetimeh;
}
 if($cort!= '' and $libe!=''){
$libetimeh=round(floor(abs($libe-$cort)/60));
$tlib=$tlib+$libetimeh;
} if($ensa!= '' and $libe!= ''){
$ensatimeh=round(floor(abs($ensa-$libe)/60));
$tens=$tens+$ensatimeh;
} if($ensa!='' and $loom!=''){
$loomtimeh=round(floor(abs($loom-$ensa)/60));
$tlo=$tlo+$loomtimeh;
} if($qlity!= '' and $loom!= ''){
$qtimeh=round(floor(abs($qlity-$loom)/60));
$tql=$tql+$qtimeh;
}
if($emba!=""){
    $ttime=round(floor(abs($emba-$plan)/60));
    $ttl=$ttl+$ttime;
}else{
    $ttime=round(floor(abs($today-$plan)/60));
    $ttl=$ttl+$ttime;
}

}
}if($tc>0){
$tc=round($tc/$totalharness);
}
if($tlib>0){
$tlib=round($tlib/$totalharness); 
}
if($tens>0){
$tens=round($tens/$totalharness);}
if($tlo){
$tlo=round($tlo/$totalharness);}
if($tql>0){
$tql=round($tql/$totalharness);}
if($ttl>0){
$ttl=round($ttl/$totalharness);}
/*echo "<br>".$tc;
echo "<br>".$tlib;
echo "<br>".$tens;
echo "<br>".$tlo;
echo "<br>".$tql;
echo "<br>".$ttl;*/








if($parte!=""){
$etiquetas=['Cutting','Terminals','Assembly','Looming','Electric Test','Total'];
$tiempos=[($tc),($tlib),($tens),($tlo),($tql),$ttl];
}?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Average Times</title>
    <!-- Importar chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
</head>

<body>
    <div><small><a href="../main/principal.php"><button>Home</button></a></small></div>
    <h1 align="center">Average Times </h1>
    <div align="center"><form action="busctime.php" method="POST">
        <label for="pn"><h2>Part Number:  </label><select name="pn" id="pn">
            <option value=""></option>
<?php

$select="SELECT DISTINCT np FROM retiradad";
$qry=mysqli_query($con,$select);
while($row=mysqli_fetch_array($qry)){
    $pn=$row['np'];
    echo $pn;
?>
<option value="<?php echo $pn; ?>"><?php echo $pn; ?></option>
    <?php
}

?>

        </select></h2>
    <input type="submit" id="request" name="request" value="Request">
    </div>
    <canvas id="grafica" width="300" height="300"></canvas>
    <script type="text/javascript">
        
        const $grafica = document.querySelector("#grafica");
        
        const etiquetas = <?php echo json_encode($etiquetas) ?>;
        
        const datosVentas2020 = {
            label: <?php echo json_encode($parte) ?>,
            
            data: <?php echo json_encode($tiempos) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.2)', 
            borderColor: 'rgba(54, 162, 235, 1)', 
            borderWidth: 1, 
        };
        new Chart($grafica, {
            type: 'line', 
            data: {
                labels: etiquetas,
                datasets: [
                    datosVentas2020,
                    
                ]
            },
            options: {
                maintainAspectRatio: false,
                    
                scales: { 
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                },
            }
        });
    </script>
     <footer>    <style>
        footer{            background-color: lightslategray;
            color: aliceblue;
            height: 20px;  
            align-items: center;
           width: 100%;                                }
            p{                font: bold italic            } 
            
    </style>    <p>Created by Jorge Garrido</p></footer>
</body>

</html>
