<?php 
require "../app/conection.php";

$buscar = mysqli_query($con, "SELECT * FROM retiradad  WHERE codigo ='0KA5064553851R1' ORDER by np DESC" );
while( $row = mysqli_fetch_array($buscar) ){
$np = $row['np'];
$code=$row['codigo'];
$qty=$row['qty'];

$buscarTiempo=mysqli_query($con,"SELECT * FROM tiempos WHERE info='$code'");
while($rowTiempo=mysqli_fetch_array($buscarTiempo)){
    $plan=strtotime($rowTiempo['planeacion']);
    $corte=strtotime($rowTiempo['corte']);
    $quality=strtotime($rowTiempo['calidad']);
}
if(mysqli_num_rows($buscarTiempo)>0){
   
    if($plan!="" and $quality!=""){
        print ("diferencias:".(($quality-$plan)/(60*60)/$qty)."<br>");
        $dias=getWorkingDays($plan,$quality);
        $min=minDiff($plan,$quality);
        if($min == 0){
        if($qty > 1){
        $mintotales=round(($dias*10*60)+$min)/$qty;}
        print($np." | ".$code." | Cantidad: ".$qty."<br>");       
        print("Planeacion: ".$plan." | Calidad: ".$quality." | Dias Laborados:". $dias." | Minutos Restantes:".$min." | Minutos Totales por arnes: ".$mintotales."<br>");
    }else if($corte!="" and $quality!="") {
        print ("diferencias:".($queality-$corte)."<br>");
        $dias=getWorkingDays($corte,$quality);
        $min=minDiff($corte,$quality);
        if($qty > 1){
            $mintotales=round(($dias*10*60)+$min)/$qty;}
        print($np." | ".$code." | Cantidad: ".$qty."<br>");
        print("Corte: ".$corte." | Calidad: ".$quality." | Dias Laborados:".$dias." | Minutos Restantes:".$min." | Minutos Totales por arnes: ".$mintotales."<br>");
    }
    
}
        
}

}


function minDiff($startDate, $endDate) {
    $startTimestamp = strtotime($startDate);
    $endTimestamp = strtotime($endDate);

    // Validate input dates
    if ($startTimestamp === false || $endTimestamp === false || $startTimestamp > $endTimestamp) {
        return 0;
    }
    $oneDay = 60 * 60 * 24;
     $diff = ($endTimestamp - $startTimestamp) % $oneDay;
     $diff=$diff/60;
     if($diff>600){
         $diff=600;
     }else{
      return $diff;}   
     
}
function getWorkingDays($startDate, $endDate) {
    // Convert date strings to UNIX timestamps
    $startTimestamp = strtotime($startDate);
    $endTimestamp = strtotime($endDate);

    // Validate input dates
    if ($startTimestamp === false || $endTimestamp === false || $startTimestamp > $endTimestamp) {
        return 0;
    }

    // Number of seconds in a day
    $oneDay = 60 * 60 * 24;
    if(($endTimestamp-$startTimestamp)<$oneDay){
        $diff = 0;
     return $diff;
    }else{
    // Initialize working days count
    $workingDays = 0;

    // Loop through each day between the start and end dates
    for ($currentTimestamp = $startTimestamp; $currentTimestamp <= $endTimestamp; $currentTimestamp += $oneDay) {
        $currentDayOfWeek = date("N", $currentTimestamp); // 1 (for Monday) through 7 (for Sunday)

        // Exclude weekends (Saturday = 6, Sunday = 7)
        if ($currentDayOfWeek < 6) {
                $workingDays++;  
        }
    }

    return $workingDays-1;}
}