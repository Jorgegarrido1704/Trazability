<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
session_start();

    require "../app/conection.php";
    date_default_timezone_set("America/Mexico_City");
    $date=date("d-m-Y H:i");
    $prueba=isset($_POST['prueba'])?$_POST['prueba']:"";
    $num= isset($_POST['pn'])?$_POST['pn']:"";
    $client=isset($_POST["client"])?$_POST["client"]:"";
    $parcial = isset($_POST['parcial']) ? $_POST['parcial'] : "";
    $info = isset($_POST['info']) ? $_POST['info'] : "";
    $qty = isset($_POST['qty']) ? $_POST['qty'] : "";
    $ok=isset($_POST['ok'])? $_POST['ok']:"";
    $nok=isset($_POST['nok'])? $_POST['nok'] :"";
    $c1=isset($_POST["1"])? $_POST["1"] :"";
    $c2=isset($_POST["2"])? $_POST["2"] :"";
    $c3=isset($_POST["3"])? $_POST["3"] :"";
    $c4=isset($_POST["4"])? $_POST["4"] :"";
    $c5=isset($_POST["5"])? $_POST["5"] :"";
    $co1=isset($_POST["c1"])? $_POST["c1"] :"";
    $co2=isset($_POST["c2"])? $_POST["c2"] :"";
    $co3=isset($_POST["c3"])? $_POST["c3"] :"";
    $co4=isset($_POST["c4"])? $_POST["c4"] :"";
    $co5=isset($_POST["c5"])? $_POST["c5"] :"";
    $codigo=isset($_POST["codigo"])? $_POST["codigo"] :"";
$faltan=($qty-$ok)-$nok;
$sum=$ok+$nok;
if(substr($prueba,0,2)=='0-0'){
    $ini='0-0';
    $prueba = str_replace('0-0', '', $prueba);
    $prueba = (int)$prueba;
    
}else {
$prueba = str_replace('0-', '', $prueba);
$prueba = (int)$prueba; 
$ini='0-0';}
$tprueba=$prueba+($sum);
$i=0;
$selectrev=mysqli_query($con,"SELECT rev FROM registro WHERE info='$info'");
while($rowbusc=mysqli_fetch_array($selectrev)){
    $revreg=$rowbusc['rev'];
}
$buscarcalida="SELECT * FROM clavecali";
$qrycali=mysqli_query($con,$buscarcalida);
while($rowcali=mysqli_fetch_array($qrycali)){
    $bus[$i]=$rowcali['clave'];
    $bc[$i]=$rowcali['defecto'];
    $i++;
}
for($i= 0;$i<count($bus);$i++){
if($c1!=0 and $bus[$i]== $c1){
    $c1=$bc[$i];
    echo $c1;
}
if($c2!=0 and $bus[$i]== $c2){
    $c2=$bc[$i];
    echo $c2;}
    if($c3!=0 and$bus[$i]== $c3){
        $c3=$bc[$i];
        echo $c3;}
        if($c4!=0 and $bus[$i]== $c4){
                $c4=$bc[$i];
                echo $c4;}  
                if($c51=0 and $bus[$i]== $c5){
                    $c5=$bc[$i];   
                    echo $c5;}
}
if($ok>0){
    for($i= 0;$i<$ok;$i++){
        if($prueba<$tprueba){$fprueba=$ini.(string)$prueba; $prueba++;} else if($prueba>=$tprueba){$fprueba=""; }
   $inserta="INSERT INTO `regsitrocalidad`(`id`, `fecha`, `client`, `pn`, `info`, `resto`, `codigo`, `prueba`) VALUES ('','$date','$client','$num','$info','1','TODO BIEN','$fprueba')";
        $qryco1=mysqli_query($con,$inserta);
             
}}
if($parcial=='si'){
 
if($co1>0){
    for($i= 0;$i<$co1;$i++){        $fprueba=$ini.(string)$prueba;
    $inserta="INSERT INTO `regsitrocalidad`(`id`, `fecha`, `client`, `pn`, `info`, `resto`, `codigo`, `prueba`) VALUES ('','$date','$client','$num','$info','1','$c1','$fprueba')";
        $qryco1=mysqli_query($con,$inserta);
        if($prueba<$tprueba){  $prueba++; }else if($prueba>=$tprueba){$fprueba=""; }}
}if($co2>0){
    for($i= 0;$i<$co2;$i++){     $fprueba=$ini.(string)$prueba;
    $inserta="INSERT INTO `regsitrocalidad`(`id`, `fecha`, `client`, `pn`, `info`, `resto`, `codigo`, `prueba`) VALUES ('','$date','$client','$num','$info','1','$c2','$fprueba')";
        $qryco1=mysqli_query($con,$inserta);
        if($prueba<$tprueba){  $prueba++; }else if($prueba>=$tprueba){$fprueba=""; }}
}if($co3>0){
    for($i= 0;$i<$co3;$i++){     $fprueba=$ini.(string)$prueba;
    $inserta="INSERT INTO `regsitrocalidad`(`id`, `fecha`, `client`, `pn`, `info`, `resto`, `codigo`, `prueba`) VALUES ('','$date','$client','$num','$info','1' ,'$c3','$fprueba')";
        $qryco1=mysqli_query($con,$inserta);
        if($prueba<$tprueba){  $prueba++; }else if($prueba>=$tprueba){$fprueba=""; }}
}if($co4>0){
    for($i= 0;$i<$co4;$i++){     $fprueba=$ini.(string)$prueba;
    $inserta="INSERT INTO `regsitrocalidad`(`id`, `fecha`, `client`, `pn`, `info`, `resto`, `codigo`, `prueba`) VALUES ('','$date','$client','$num','$info','1','$c4','$fprueba')";
        $qryco1=mysqli_query($con,$inserta);
        if($prueba<$tprueba){  $prueba++; }else if($prueba>=$tprueba){$fprueba=""; }}
}if($co5>0){
    for($i= 0;$i<$co5;$i++){
    $inserta="INSERT INTO `regsitrocalidad`(`id`, `fecha`, `client`, `pn`, `info`, `resto`, `codigo`, `prueba`) VALUES ('','$date','$client','$num','$info','1','$c5','$fprueba')";
        $qryco1=mysqli_query($con,$inserta);
        if($prueba<$tprueba){  $prueba++; }else if($prueba>=$tprueba){$fprueba=""; }}
}
if($faltan>0){
    $altaparcial="UPDATE registro SET paro='parcial' WHERE info='$info'";
    $qryregistro=mysqli_query($con,$altaparcial);
    $upaderest="UPDATE calidad Set qty='$faltan',parcial='$parcial' WHERE info='$info'";
$sqlrest=mysqli_query($con,$upaderest);
$iniciarparo="INSERT INTO `paros`(`id`, `info`,`tipo`, `registoinicial`, `registroparcial`,`count`) VALUES ('','$info','Parcial','$fecha','','')";
$tiempo=mysqli_query($con,$iniciarparo);
$tiempos="UPDATE `tiempos` SET `calidad`='$date' WHERE `info`='$info'";
$time=mysqli_query($con,$tiempos);

}else  if ($faltan <= 0) {
    $count=12;
    $buscarfiin="SELECT * FROM paros WHERE  info='$info'";
     $qrycodigo=mysqli_query($con,$buscarfiin);
     while($rowbus=mysqli_fetch_array($qrycodigo)){
    $iniparo=$rowbus['registoinicial'];
    $iniparo=strtotime($iniparo);
    $actualdate=strtotime($fecha);
        $inter = abs($actualdate - $iniparo);
                    $hcalc = floor($inter / 3600);
                    $mcalc = floor(($inter % 3600) / 60);
                    $totalmin=($hcalc*60)+$mcalc;
    $parostotal= "INSERT INTO `parostotal`(`id`, `info`, `tiempo`) VALUES ('','$info','$totalmin')";
    $qrytotal=mysqli_query($con,$parostotal);
$delteparo="DELETE FROM `paros` WHERE info='$info'";
$qrydelete=mysqli_query($con,$delteparo);}
   
$update="UPDATE `registro` SET `paro`='', `count`='$count', `donde`='En espera de embarque' WHERE `info`='$info'";
$up = mysqli_query($con, $update);
$updateemb="UPDATE embarque SET datein='$date' WHERE info='$info'";
$qryup=mysqli_query($con,$updateemb); 
$tiempos="UPDATE `tiempos` SET `calidad`='$date' WHERE `info`='$info'";
$time=mysqli_query($con,$tiempos);

    $deltenull = $con->prepare("DELETE FROM `calidad` WHERE info = ?");
    $deltenull->bind_param("s", $info); 

    if ($deltenull->execute()) {
        $llamarPPAP="SELECT * FROM registro WHERE info='$info'";
        $qryemail=mysqli_query($con,$llamarPPAP);
        while ($rowemail = mysqli_fetch_array($qryemail)) {
            $rev=$rowemail['rev'];
            $emailcliente=$rowemail['cliente'];
            $emailpn=$rowemail['NumPart'];
            $emailwo=$rowemail['wo'];
            $emailpo=$rowemail['po'];
            $emailQty=$rowemail['Qty'];
        }
        $revin=substr($rev,0,4);
if($revin=="PPAP" and ($emailcliente=="BERGSTROM" or $emailcliente=="BLUE BIRD")){
$revf=substr($rev,4);
date_default_timezone_set('America/Mexico_City');
$date = date('d-m-Y');
$time=date('H:i');
// Start constructing the email content
$message = '<div align="center"><h1>PPAP PRUEBA ELECTRICA </h1></div> <br><br>';
$message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
$message .='<br><h2>Cliente:</h2>'.$emailcliente;
$message .='<br><h2>Numero de parte:</h2>'.$emailpn;
$message .='<br><h2>PPAP en revision:</h2>'.$revf;
$message .='<br><h2>Con Work order:</h2>'.$emailwo;
$message .='<br><h2>Con Sono order:</h2>'.$emailpo;
$message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
$message .='<br><br><br>Con las siguientes anotaciones:';
$defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
$defectosqry=mysqli_query($con,$defectos);
while($rowdef=mysqli_fetch_array($defectosqry)){
$defectos=$rowdef['codigo'];
$message.='<br>'.$defectos;
}

$message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
// Set the recipient email address
//$recipientEmail = 'garridofth@outlook.com';
//$recipientEmail = 'jolaes@mx.bergstrominc.com';
// Set the email subject and sender details
$subject = 'PPAP PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;

$senderEmail = 'garridofth@outlook.com';
$headers = "From: $senderEmail\r\n";
$headers .= "Content-Type: text/html; charset=utf-8\r\n";
$recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';

  $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
}else    if($revin=="PPAP" and ($emailcliente=="ATLAS COPCO" or $emailcliente=="UTILIMASTER")){
$revf=substr($rev,4);
date_default_timezone_set('America/Mexico_City');
$date = date('d-m-Y');
$time=date('H:i');
// Start constructing the email content  
$message = '<div align="center"><h1>PPAP PRUEBA ELECTRICA </h1></div> <br><br>';
$message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
$message .='<br><h2>Cliente:</h2>'.$emailcliente;
$message .='<br><h2>Numero de parte:</h2>'.$emailpn;
$message .='<br><h2>PPAP en revision:</h2>'.$revf;
$message .='<br><h2>Con Work order:</h2>'.$emailwo;
$message .='<br><h2>Con Sono order:</h2>'.$emailpo;
$message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
$message .='<br><br><br>Con las siguientes anotaciones:';
$defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
$defectosqry=mysqli_query($con,$defectos);
while($rowdef=mysqli_fetch_array($defectosqry)){
$defectos=$rowdef['codigo'];
$message.='<br>'.$defectos;
}

$message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
// Set the recipient email address
//$recipientEmail = 'garridofth@outlook.com';
//$recipientEmail = 'jolaes@mx.bergstrominc.com';
// Set the email subject and sender details
$subject = 'PPAP PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;

$senderEmail = 'garridofth@outlook.com';
$headers = "From: $senderEmail\r\n";
$headers .= "Content-Type: text/html; charset=utf-8\r\n";
$recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';

  $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
}else    if($revin=="PPAP" and ($emailcliente=="EL DORADO CALIFORNIA")){
$revf=substr($rev,4);
date_default_timezone_set('America/Mexico_City');
$date = date('d-m-Y');
$time=date('H:i');
// Start constructing the email content
$message = '<div align="center"><h1>PPAP PRUEBA ELECTRICA </h1></div> <br><br>';
$message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
$message .='<br><h2>Cliente:</h2>'.$emailcliente;
$message .='<br><h2>Numero de parte:</h2>'.$emailpn;
$message .='<br><h2>PPAP en revision:</h2>'.$revf;
$message .='<br><h2>Con Work order:</h2>'.$emailwo;
$message .='<br><h2>Con Sono order:</h2>'.$emailpo;
$message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
$message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
$defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
$defectosqry=mysqli_query($con,$defectos);
while($rowdef=mysqli_fetch_array($defectosqry)){
$defectos=$rowdef['codigo'];
}
// Set the recipient email address
//$recipientEmail = 'garridofth@outlook.com';
//$recipientEmail = 'jolaes@mx.bergstrominc.com';
// Set the email subject and sender details
$subject = 'PPAP PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;

$senderEmail = 'garridofth@outlook.com';
$headers = "From: $senderEmail\r\n";
$headers .= "Content-Type: text/html; charset=utf-8\r\n";
$recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx,luis.valdivia99@outlook.com, batterycable.fth@outlook.com, david-villa88@outlook.com';

  $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
}else    if($revin=="PPAP" and ($emailcliente=="KALMAR" or $emailcliente=="MODINE" or $emailcliente== "NILFISK")) {
$revf=substr($rev,4);
date_default_timezone_set('America/Mexico_City');
$date = date('d-m-Y');
$time=date('H:i');
// Start constructing the email content
$message = '<div align="center"><h1>PPAP PRUEBA ELECTRICA </h1></div> <br><br>';
$message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
$message .='<br><h2>Cliente:</h2>'.$emailcliente;
$message .='<br><h2>Numero de parte:</h2>'.$emailpn;
$message .='<br><h2>PPAP en revision:</h2>'.$revf;
$message .='<br><h2>Con Work order:</h2>'.$emailwo;
$message .='<br><h2>Con Sono order:</h2>'.$emailpo;
$message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
$message .='<br><br><br>Con las siguientes anotaciones:';
$defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
$defectosqry=mysqli_query($con,$defectos);
while($rowdef=mysqli_fetch_array($defectosqry)){
$defectos=$rowdef['codigo'];
$message.='<br>'.$defectos;
}

$message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
// Set the recipient email address
//$recipientEmail = 'garridofth@outlook.com';
//$recipientEmail = 'jolaes@mx.bergstrominc.com';
// Set the email subject and sender details
$subject = 'PPAP PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;

$senderEmail = 'garridofth@outlook.com';
$headers = "From: $senderEmail\r\n";
$headers .= "Content-Type: text/html; charset=utf-8\r\n";
$recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';

  $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
}else    if($revin=="PPAP" and ($emailcliente=="TICO MANUFACTURING" or $emailcliente=="COLLINS" or $emailcliente=="PROTERRA" or $emailcliente=="SPARTAN" or $emailcliente=="SHYFT" )){
$revf=substr($rev,4);
date_default_timezone_set('America/Mexico_City');
$date = date('d-m-Y');
$time=date('H:i');
// Start constructing the email content
$message = '<div align="center"><h1>PPAP PRUEBA ELECTRICA </h1></div> <br><br>';
$message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
$message .='<br><h2>Cliente:</h2>'.$emailcliente;
$message .='<br><h2>Numero de parte:</h2>'.$emailpn;
$message .='<br><h2>PPAP en revision:</h2>'.$revf;
$message .='<br><h2>Con Work order:</h2>'.$emailwo;
$message .='<br><h2>Con Sono order:</h2>'.$emailpo;
$message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
$message .='<br><br><br>Con las siguientes anotaciones:';
$defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
$defectosqry=mysqli_query($con,$defectos);
while($rowdef=mysqli_fetch_array($defectosqry)){
$defectos=$rowdef['codigo'];
$message.='<br>'.$defectos;
}

$message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
// Set the recipient email address
//$recipientEmail = 'garridofth@outlook.com';
//$recipientEmail = 'jolaes@mx.bergstrominc.com';
// Set the email subject and sender details
$subject = 'PPAP PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;

$senderEmail = 'garridofth@outlook.com';
$headers = "From: $senderEmail\r\n";
$headers .= "Content-Type: text/html; charset=utf-8\r\n";
$recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';

  $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
}else    if($revin=="PPAP" and ($emailcliente=="FOREST" or $emailcliente=="ZOELLER" )){
$revf=substr($rev,4);
date_default_timezone_set('America/Mexico_City');
$date = date('d-m-Y');
$time=date('H:i');
// Start constructing the email content
$message = '<div align="center"><h1>PPAP PRUEBA ELECTRICA </h1></div> <br><br>';
$message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
$message .='<br><h2>Cliente:</h2>'.$emailcliente;
$message .='<br><h2>Numero de parte:</h2>'.$emailpn;
$message .='<br><h2>PPAP en revision:</h2>'.$revf;
$message .='<br><h2>Con Work order:</h2>'.$emailwo;
$message .='<br><h2>Con Sono order:</h2>'.$emailpo;
$message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
$message .='<br><br><br>Con las siguientes anotaciones:';
$defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
$defectosqry=mysqli_query($con,$defectos);
while($rowdef=mysqli_fetch_array($defectosqry)){
$defectos=$rowdef['codigo'];
$message.='<br>'.$defectos;
}

$message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
// Set the recipient email address
//$recipientEmail = 'garridofth@outlook.com';
//$recipientEmail = 'jolaes@mx.bergstrominc.com';
// Set the email subject and sender details
$subject = 'PPAP PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;

$senderEmail = 'garridofth@outlook.com';
$headers = "From: $senderEmail\r\n";
$headers .= "Content-Type: text/html; charset=utf-8\r\n";
$recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';

  $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
}

    }else if($revin=="PRIM" and ($emailcliente=="BERGSTROM" or $emailcliente=="BLUE BIRD")){
        $revf=substr($rev,4);
        date_default_timezone_set('America/Mexico_City');
        $date = date('d-m-Y');
        $time=date('H:i');
        // Start constructing the email content
        $message = '<div align="center"><h1>Primera pieza PRUEBA ELECTRICA </h1></div> <br><br>';
        $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la Primera pieza siguiente<br>';
        $message .='<br><h2>Cliente:</h2>'.$emailcliente;
        $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
        $message .='<br><h2>Primera pieza en revision:</h2>'.$revf;
        $message .='<br><h2>Con Work order:</h2>'.$emailwo;
        $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
        $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
        $message .='<br><br><br>Con las siguientes anotaciones:';
        $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
        $defectosqry=mysqli_query($con,$defectos);
        while($rowdef=mysqli_fetch_array($defectosqry)){
        $defectos=$rowdef['codigo'];
        $message.='<br>'.$defectos;
        }
        
        $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
        // Set the recipient email address
        //$recipientEmail = 'garridofth@outlook.com';
        //$recipientEmail = 'jolaes@mx.bergstrominc.com';
        // Set the email subject and sender details
        $subject = 'Primera pieza PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
        
        $senderEmail = 'garridofth@outlook.com';
        $headers = "From: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
        
         $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
        }else    if($revin=="PRIM" and ($emailcliente=="ATLAS COPCO" or $emailcliente=="UTILIMASTER")){
        $revf=substr($rev,4);
        date_default_timezone_set('America/Mexico_City');
        $date = date('d-m-Y');
        $time=date('H:i');
        // Start constructing the email content  
        $message = '<div align="center"><h1>Primera pieza PRUEBA ELECTRICA </h1></div> <br><br>';
        $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la Primera pieza siguiente<br>';
        $message .='<br><h2>Cliente:</h2>'.$emailcliente;
        $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
        $message .='<br><h2>Primera pieza en revision:</h2>'.$revf;
        $message .='<br><h2>Con Work order:</h2>'.$emailwo;
        $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
        $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
        $message .='<br><br><br>Con las siguientes anotaciones:';
        $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
        $defectosqry=mysqli_query($con,$defectos);
        while($rowdef=mysqli_fetch_array($defectosqry)){
        $defectos=$rowdef['codigo'];
        $message.='<br>'.$defectos;
        }
        
        $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
        // Set the recipient email address
        //$recipientEmail = 'garridofth@outlook.com';
        //$recipientEmail = 'jolaes@mx.bergstrominc.com';
        // Set the email subject and sender details
        $subject = 'Primera pieza PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
        
        $senderEmail = 'garridofth@outlook.com';
        $headers = "From: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
        
         $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
        }else    if($revin=="PRIM" and ($emailcliente=="EL DORADO CALIFORNIA")){
        $revf=substr($rev,4);
        date_default_timezone_set('America/Mexico_City');
        $date = date('d-m-Y');
        $time=date('H:i');
        // Start constructing the email content
        $message = '<div align="center"><h1>Primera pieza PRUEBA ELECTRICA </h1></div> <br><br>';
        $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la Primera pieza siguiente<br>';
        $message .='<br><h2>Cliente:</h2>'.$emailcliente;
        $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
        $message .='<br><h2>Primera pieza en revision:</h2>'.$revf;
        $message .='<br><h2>Con Work order:</h2>'.$emailwo;
        $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
        $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
        $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
        $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
        $defectosqry=mysqli_query($con,$defectos);
        while($rowdef=mysqli_fetch_array($defectosqry)){
        $defectos=$rowdef['codigo'];
        }
        // Set the recipient email address
        //$recipientEmail = 'garridofth@outlook.com';
        //$recipientEmail = 'jolaes@mx.bergstrominc.com';
        // Set the email subject and sender details
        $subject = 'Primera pieza PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
        
        $senderEmail = 'garridofth@outlook.com';
        $headers = "From: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx,luis.valdivia99@outlook.com, batterycable.fth@outlook.com, david-villa88@outlook.com';
        
         $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
        }else    if($revin=="PRIM" and ($emailcliente=="KALMAR" or $emailcliente=="MODINE" or $emailcliente== "NILFISK")) {
        $revf=substr($rev,4);
        date_default_timezone_set('America/Mexico_City');
        $date = date('d-m-Y');
        $time=date('H:i');
        // Start constructing the email content
        $message = '<div align="center"><h1>Primera pieza PRUEBA ELECTRICA </h1></div> <br><br>';
        $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la Primera pieza siguiente<br>';
        $message .='<br><h2>Cliente:</h2>'.$emailcliente;
        $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
        $message .='<br><h2>Primera pieza en revision:</h2>'.$revf;
        $message .='<br><h2>Con Work order:</h2>'.$emailwo;
        $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
        $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
        $message .='<br><br><br>Con las siguientes anotaciones:';
        $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
        $defectosqry=mysqli_query($con,$defectos);
        while($rowdef=mysqli_fetch_array($defectosqry)){
        $defectos=$rowdef['codigo'];
        $message.='<br>'.$defectos;
        }
        
        $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
        // Set the recipient email address
        //$recipientEmail = 'garridofth@outlook.com';
        //$recipientEmail = 'jolaes@mx.bergstrominc.com';
        // Set the email subject and sender details
        $subject = 'Primera pieza PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
        
        $senderEmail = 'garridofth@outlook.com';
        $headers = "From: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
        
         $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
        }else    if($revin=="PRIM" and ($emailcliente=="TICO MANUFACTURING" or $emailcliente=="COLLINS" or $emailcliente=="PROTERRA" or $emailcliente=="SPARTAN" or $emailcliente=="SHYFT" )){
        $revf=substr($rev,4);
        date_default_timezone_set('America/Mexico_City');
        $date = date('d-m-Y');
        $time=date('H:i');
        // Start constructing the email content
        $message = '<div align="center"><h1>Primera pieza PRUEBA ELECTRICA </h1></div> <br><br>';
        $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la Primera pieza siguiente<br>';
        $message .='<br><h2>Cliente:</h2>'.$emailcliente;
        $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
        $message .='<br><h2>Primera pieza en revision:</h2>'.$revf;
        $message .='<br><h2>Con Work order:</h2>'.$emailwo;
        $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
        $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
        $message .='<br><br><br>Con las siguientes anotaciones:';
        $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
        $defectosqry=mysqli_query($con,$defectos);
        while($rowdef=mysqli_fetch_array($defectosqry)){
        $defectos=$rowdef['codigo'];
        $message.='<br>'.$defectos;
        }
        
        $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
        // Set the recipient email address
        //$recipientEmail = 'garridofth@outlook.com';
        //$recipientEmail = 'jolaes@mx.bergstrominc.com';
        // Set the email subject and sender details
        $subject = 'Primera pieza PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
        
        $senderEmail = 'garridofth@outlook.com';
        $headers = "From: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
        
         $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
        }else    if($revin=="PRIM" and ($emailcliente=="FOREST" or $emailcliente=="ZOELLER" )){
        $revf=substr($rev,4);
        date_default_timezone_set('America/Mexico_City');
        $date = date('d-m-Y');
        $time=date('H:i');
        // Start constructing the email content
        $message = '<div align="center"><h1>Primera pieza PRUEBA ELECTRICA </h1></div> <br><br>';
        $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la Primera pieza siguiente<br>';
        $message .='<br><h2>Cliente:</h2>'.$emailcliente;
        $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
        $message .='<br><h2>Primera pieza en revision:</h2>'.$revf;
        $message .='<br><h2>Con Work order:</h2>'.$emailwo;
        $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
        $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
        $message .='<br><br><br>Con las siguientes anotaciones:';
        $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
        $defectosqry=mysqli_query($con,$defectos);
        while($rowdef=mysqli_fetch_array($defectosqry)){
        $defectos=$rowdef['codigo'];
        $message.='<br>'.$defectos;
        }
        
        $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
        // Set the recipient email address
        //$recipientEmail = 'garridofth@outlook.com';
        //$recipientEmail = 'jolaes@mx.bergstrominc.com';
        // Set the email subject and sender details
        $subject = 'Primera pieza PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
        
        $senderEmail = 'garridofth@outlook.com';
        $headers = "From: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
        
         $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
        }
    
    else {
        // Handle database error, perhaps log it for debugging
        echo "Error: " . $deltenull->error;
    }

    // Close the prepared statement
    $deltenull->close();

     }} else {
        if($co1>0){
            for($i= 0;$i<$co1;$i++){
                if($prueba<$tprueba){$fprueba=$ini.(string)$prueba; $prueba++;} else if($prueba>=$tprueba){$fprueba=""; }
            $inserta="INSERT INTO `regsitrocalidad`(`id`, `fecha`, `client`, `pn`, `info`, `resto`, `codigo`, `prueba`) VALUES ('','$date','$client','$num','$info','1','$c1','$fprueba')";
                $qryco1=mysqli_query($con,$inserta);
                }
            
        }if($co2>0){
            for($i= 0;$i<$co2;$i++){
                if($prueba<$tprueba){$fprueba=$ini.(string)$prueba; $prueba++;} else if($prueba>=$tprueba){$fprueba=""; }
            $inserta="INSERT INTO `regsitrocalidad`(`id`, `fecha`, `client`, `pn`, `info`, `resto`, `codigo`, `prueba`) VALUES ('','$date','$client','$num','$info','1','$c2','$fprueba')";
                $qryco1=mysqli_query($con,$inserta);}
            }
        if($co3>0){
            for($i= 0;$i<$co3;$i++){
                if($prueba<$tprueba){$fprueba=$ini.(string)$prueba; $prueba++;} else if($prueba>=$tprueba){$fprueba=""; }
            $inserta="INSERT INTO `regsitrocalidad`(`id`, `fecha`, `client`, `pn`, `info`, `resto`, `codigo`, `prueba`) VALUES ('','$date','$client','$num','$info','1' ,'$c3','$fprueba')";
                $qryco1=mysqli_query($con,$inserta); }

        }if($co4>0){
            for($i= 0;$i<$co4;$i++){
                if($prueba<$tprueba){$fprueba=$ini.(string)$prueba; $prueba++;} else if($prueba>=$tprueba){$fprueba=""; }
            $inserta="INSERT INTO `regsitrocalidad`(`id`, `fecha`, `client`, `pn`, `info`, `resto`, `codigo`, `prueba`) VALUES ('','$date','$client','$num','$info','1','$c4','$fprueba')";
                $qryco1=mysqli_query($con,$inserta);}

        }if($co5>0){
            for($i= 0;$i<$co5;$i++){
                if($prueba<$tprueba){$fprueba=$ini.(string)$prueba; $prueba++;} else if($prueba>=$tprueba){$fprueba=""; }
            $inserta="INSERT INTO `regsitrocalidad`(`id`, `fecha`, `client`, `pn`, `info`, `resto`, `codigo`, `prueba`) VALUES ('','$date','$client','$num','$info','1','$c5','$fprueba')";
                $qryco1=mysqli_query($con,$inserta); }

        }
        if($faltan>0){
            $altaparcial="UPDATE registro SET count='11',donde='En proceso de prueba electrica',paro='parcial' WHERE info='$info'";
            $qryregistro=mysqli_query($con,$altaparcial);
            $upaderest="UPDATE calidad Set qty='$faltan',parcial='si' WHERE info='$info'";
        $sqlrest=mysqli_query($con,$upaderest);
        $iniciarparo="INSERT INTO `paros`(`id`, `info`,`tipo`, `registoinicial`, `registroparcial`,`count`) VALUES ('','$info','Parcial','$date','','')";
        $tiempo=mysqli_query($con,$iniciarparo);
        $tiempos="UPDATE `tiempos` SET `calidad`='$date' WHERE `info`='$info'";
        $time=mysqli_query($con,$tiempos);
       
        
        }else  if ($faltan <= 0) {
            $count=12;
            $buscarfiin="SELECT * FROM paros WHERE  info='$info'";
             $qrycodigo=mysqli_query($con,$buscarfiin);
             while($rowbus=mysqli_fetch_array($qrycodigo)){
            $iniparo=$rowbus['registoinicial'];
            $iniparo=strtotime($iniparo);
            $actualdate=strtotime($fecha);
                $inter = abs($actualdate - $iniparo);
                            $hcalc = floor($inter / 3600);
                            $mcalc = floor(($inter % 3600) / 60);
                            $totalmin=($hcalc*60)+$mcalc;
            $parostotal= "INSERT INTO `parostotal`(`id`, `info`, `tiempo`) VALUES ('','$info','$totalmin')";
            $qrytotal=mysqli_query($con,$parostotal);
        $delteparo="DELETE FROM `paros` WHERE info='$info'";
        $qrydelete=mysqli_query($con,$delteparo);}
        $updatiempos="UPDATE `tiemposharn` SET `emba`='$date', `qlyF` = '$date' WHERE `info`='$info'";
        if(substr($revreg,0,4)=='PRIM' or substr($revreg,0,4)=='PPAP' ){
            
            $update="UPDATE `registro` SET `paro`='',`count`='18', `donde`='En de liberacion de Ingenieria Calidad' WHERE `info`='$info'";
            $up = mysqli_query($con, $update);
            $tiempos="UPDATE `tiempos` SET `calidad`='$fecha' WHERE `info`='$info'";
            $time=mysqli_query($con,$tiempos);   
            $updateemb="UPDATE embarque SET datein='$date' WHERE info='$info'";
        $qryup=mysqli_query($con,$updateemb);  
        $deltenull = $con->prepare("DELETE FROM `calidad` WHERE info = ?");
            $deltenull->bind_param("s", $info); 
        
    
        }else{
        $update="UPDATE `registro` SET `paro`='', `count`='$count', `donde`='En espera de embarque' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
        $updateemb="UPDATE embarque SET datein='$date' WHERE info='$info'";
        $qryup=mysqli_query($con,$updateemb); 
        $tiempos="UPDATE `tiempos` SET `calidad`='$date' WHERE `info`='$info'";
        $time=mysqli_query($con,$tiempos);
        
            $deltenull = $con->prepare("DELETE FROM `calidad` WHERE info = ?");
            $deltenull->bind_param("s", $info); }
        
            if ($deltenull->execute()) {
                $llamarPPAP="SELECT * FROM registro WHERE info='$info'";
                $qryemail=mysqli_query($con,$llamarPPAP);
                while ($rowemail = mysqli_fetch_array($qryemail)) {
                    $rev=$rowemail['rev'];
                    $emailcliente=$rowemail['cliente'];
                    $emailpn=$rowemail['NumPart'];
                    $emailwo=$rowemail['wo'];
                    $emailpo=$rowemail['po'];
                    $emailQty=$rowemail['Qty'];
                }
                $revin=substr($rev,0,4);
        if($revin=="PPAP" and ($emailcliente=="BERGSTROM" or $emailcliente=="BLUE BIRD")){
        $revf=substr($rev,4);
        date_default_timezone_set('America/Mexico_City');
        $date = date('d-m-Y');
        $time=date('H:i');
        // Start constructing the email content
        $message = '<div align="center"><h1>PPAP PRUEBA ELECTRICA </h1></div> <br><br>';
        $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
        $message .='<br><h2>Cliente:</h2>'.$emailcliente;
        $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
        $message .='<br><h2>PPAP en revision:</h2>'.$revf;
        $message .='<br><h2>Con Work order:</h2>'.$emailwo;
        $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
        $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
        $message .='<br><br><br>Con las siguientes anotaciones:';
        $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
        $defectosqry=mysqli_query($con,$defectos);
        while($rowdef=mysqli_fetch_array($defectosqry)){
        $defectos=$rowdef['codigo'];
        $message.='<br>'.$defectos;
        }
        
        $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
        // Set the recipient email address
        //$recipientEmail = 'garridofth@outlook.com';
        //$recipientEmail = 'jolaes@mx.bergstrominc.com';
        // Set the email subject and sender details
        $subject = 'PPAP PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
        
        $senderEmail = 'garridofth@outlook.com';
        $headers = "From: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
        
         $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
        }else    if($revin=="PPAP" and ($emailcliente=="ATLAS COPCO" or $emailcliente=="UTILIMASTER")){
        $revf=substr($rev,4);
        date_default_timezone_set('America/Mexico_City');
        $date = date('d-m-Y');
        $time=date('H:i');
        // Start constructing the email content  
        $message = '<div align="center"><h1>PPAP PRUEBA ELECTRICA </h1></div> <br><br>';
        $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
        $message .='<br><h2>Cliente:</h2>'.$emailcliente;
        $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
        $message .='<br><h2>PPAP en revision:</h2>'.$revf;
        $message .='<br><h2>Con Work order:</h2>'.$emailwo;
        $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
        $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
        $message .='<br><br><br>Con las siguientes anotaciones:';
        $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
        $defectosqry=mysqli_query($con,$defectos);
        while($rowdef=mysqli_fetch_array($defectosqry)){
        $defectos=$rowdef['codigo'];
        $message.='<br>'.$defectos;
        }
        
        $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
        // Set the recipient email address
        //$recipientEmail = 'garridofth@outlook.com';
        //$recipientEmail = 'jolaes@mx.bergstrominc.com';
        // Set the email subject and sender details
        $subject = 'PPAP PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
        
        $senderEmail = 'garridofth@outlook.com';
        $headers = "From: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
        
         $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
        }else    if($revin=="PPAP" and ($emailcliente=="EL DORADO CALIFORNIA")){
        $revf=substr($rev,4);
        date_default_timezone_set('America/Mexico_City');
        $date = date('d-m-Y');
        $time=date('H:i');
        // Start constructing the email content
        $message = '<div align="center"><h1>PPAP PRUEBA ELECTRICA </h1></div> <br><br>';
        $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
        $message .='<br><h2>Cliente:</h2>'.$emailcliente;
        $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
        $message .='<br><h2>PPAP en revision:</h2>'.$revf;
        $message .='<br><h2>Con Work order:</h2>'.$emailwo;
        $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
        $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
        $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
        $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
        $defectosqry=mysqli_query($con,$defectos);
        while($rowdef=mysqli_fetch_array($defectosqry)){
        $defectos=$rowdef['codigo'];
        }
        // Set the recipient email address
        //$recipientEmail = 'garridofth@outlook.com';
        //$recipientEmail = 'jolaes@mx.bergstrominc.com';
        // Set the email subject and sender details
        $subject = 'PPAP PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
        
        $senderEmail = 'garridofth@outlook.com';
        $headers = "From: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx,luis.valdivia99@outlook.com, batterycable.fth@outlook.com, david-villa88@outlook.com';
        
         $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
        }else    if($revin=="PPAP" and ($emailcliente=="KALMAR" or $emailcliente=="MODINE" or $emailcliente== "NILFISK")) {
        $revf=substr($rev,4);
        date_default_timezone_set('America/Mexico_City');
        $date = date('d-m-Y');
        $time=date('H:i');
        // Start constructing the email content
        $message = '<div align="center"><h1>PPAP PRUEBA ELECTRICA </h1></div> <br><br>';
        $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
        $message .='<br><h2>Cliente:</h2>'.$emailcliente;
        $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
        $message .='<br><h2>PPAP en revision:</h2>'.$revf;
        $message .='<br><h2>Con Work order:</h2>'.$emailwo;
        $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
        $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
        $message .='<br><br><br>Con las siguientes anotaciones:';
        $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
        $defectosqry=mysqli_query($con,$defectos);
        while($rowdef=mysqli_fetch_array($defectosqry)){
        $defectos=$rowdef['codigo'];
        $message.='<br>'.$defectos;
        }
        
        $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
        // Set the recipient email address
        //$recipientEmail = 'garridofth@outlook.com';
        //$recipientEmail = 'jolaes@mx.bergstrominc.com';
        // Set the email subject and sender details
        $subject = 'PPAP PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
        
        $senderEmail = 'garridofth@outlook.com';
        $headers = "From: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
        
         $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
        }else    if($revin=="PPAP" and ($emailcliente=="TICO MANUFACTURING" or $emailcliente=="COLLINS" or $emailcliente=="PROTERRA" or $emailcliente=="SPARTAN" or $emailcliente=="SHYFT" )){
        $revf=substr($rev,4);
        date_default_timezone_set('America/Mexico_City');
        $date = date('d-m-Y');
        $time=date('H:i');
        // Start constructing the email content
        $message = '<div align="center"><h1>PPAP PRUEBA ELECTRICA </h1></div> <br><br>';
        $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
        $message .='<br><h2>Cliente:</h2>'.$emailcliente;
        $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
        $message .='<br><h2>PPAP en revision:</h2>'.$revf;
        $message .='<br><h2>Con Work order:</h2>'.$emailwo;
        $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
        $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
        $message .='<br><br><br>Con las siguientes anotaciones:';
        $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
        $defectosqry=mysqli_query($con,$defectos);
        while($rowdef=mysqli_fetch_array($defectosqry)){
        $defectos=$rowdef['codigo'];
        $message.='<br>'.$defectos;
        }
        
        $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
        // Set the recipient email address
        //$recipientEmail = 'garridofth@outlook.com';
        //$recipientEmail = 'jolaes@mx.bergstrominc.com';
        // Set the email subject and sender details
        $subject = 'PPAP PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
        
        $senderEmail = 'garridofth@outlook.com';
        $headers = "From: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
        
         $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
        }else    if($revin=="PPAP" and ($emailcliente=="FOREST" or $emailcliente=="ZOELLER" )){
        $revf=substr($rev,4);
        date_default_timezone_set('America/Mexico_City');
        $date = date('d-m-Y');
        $time=date('H:i');
        // Start constructing the email content
        $message = '<div align="center"><h1>PPAP PRUEBA ELECTRICA </h1></div> <br><br>';
        $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
        $message .='<br><h2>Cliente:</h2>'.$emailcliente;
        $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
        $message .='<br><h2>PPAP en revision:</h2>'.$revf;
        $message .='<br><h2>Con Work order:</h2>'.$emailwo;
        $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
        $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
        $message .='<br><br><br>Con las siguientes anotaciones:';
        $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
        $defectosqry=mysqli_query($con,$defectos);
        while($rowdef=mysqli_fetch_array($defectosqry)){
        $defectos=$rowdef['codigo'];
        $message.='<br>'.$defectos;
        }
        
        $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
        // Set the recipient email address
        //$recipientEmail = 'garridofth@outlook.com';
        //$recipientEmail = 'jolaes@mx.bergstrominc.com';
        // Set the email subject and sender details
        $subject = 'PPAP PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
        
        $senderEmail = 'garridofth@outlook.com';
        $headers = "From: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
        
         $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
        }
        
            else if($revin=="PRIM" and ($emailcliente=="BERGSTROM" or $emailcliente=="BLUE BIRD")){
                $revf=substr($rev,4);
                date_default_timezone_set('America/Mexico_City');
                $date = date('d-m-Y');
                $time=date('H:i');
                // Start constructing the email content
                $message = '<div align="center"><h1>Primera pieza PRUEBA ELECTRICA </h1></div> <br><br>';
                $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la Primera pieza siguiente<br>';
                $message .='<br><h2>Cliente:</h2>'.$emailcliente;
                $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
                $message .='<br><h2>Primera pieza en revision:</h2>'.$revf;
                $message .='<br><h2>Con Work order:</h2>'.$emailwo;
                $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
                $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
                $message .='<br><br><br>Con las siguientes anotaciones:';
                $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
                $defectosqry=mysqli_query($con,$defectos);
                while($rowdef=mysqli_fetch_array($defectosqry)){
                $defectos=$rowdef['codigo'];
                $message.='<br>'.$defectos;
                }
                
                $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
                // Set the recipient email address
                //$recipientEmail = 'garridofth@outlook.com';
                //$recipientEmail = 'jolaes@mx.bergstrominc.com';
                // Set the email subject and sender details
                $subject = 'Primera pieza PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
                
                $senderEmail = 'garridofth@outlook.com';
                $headers = "From: $senderEmail\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
                
                 $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
                }else    if($revin=="PRIM" and ($emailcliente=="ATLAS COPCO" or $emailcliente=="UTILIMASTER")){
                $revf=substr($rev,4);
                date_default_timezone_set('America/Mexico_City');
                $date = date('d-m-Y');
                $time=date('H:i');
                // Start constructing the email content  
                $message = '<div align="center"><h1>Primera pieza PRUEBA ELECTRICA </h1></div> <br><br>';
                $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la Primera pieza siguiente<br>';
                $message .='<br><h2>Cliente:</h2>'.$emailcliente;
                $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
                $message .='<br><h2>Primera pieza en revision:</h2>'.$revf;
                $message .='<br><h2>Con Work order:</h2>'.$emailwo;
                $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
                $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
                $message .='<br><br><br>Con las siguientes anotaciones:';
                $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
                $defectosqry=mysqli_query($con,$defectos);
                while($rowdef=mysqli_fetch_array($defectosqry)){
                $defectos=$rowdef['codigo'];
                $message.='<br>'.$defectos;
                }
                
                $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
                // Set the recipient email address
                //$recipientEmail = 'garridofth@outlook.com';
                //$recipientEmail = 'jolaes@mx.bergstrominc.com';
                // Set the email subject and sender details
                $subject = 'Primera pieza PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
                
                $senderEmail = 'garridofth@outlook.com';
                $headers = "From: $senderEmail\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
                
                 $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
                }else    if($revin=="PRIM" and ($emailcliente=="EL DORADO CALIFORNIA")){
                $revf=substr($rev,4);
                date_default_timezone_set('America/Mexico_City');
                $date = date('d-m-Y');
                $time=date('H:i');
                // Start constructing the email content
                $message = '<div align="center"><h1>Primera pieza PRUEBA ELECTRICA </h1></div> <br><br>';
                $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la Primera pieza siguiente<br>';
                $message .='<br><h2>Cliente:</h2>'.$emailcliente;
                $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
                $message .='<br><h2>Primera pieza en revision:</h2>'.$revf;
                $message .='<br><h2>Con Work order:</h2>'.$emailwo;
                $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
                $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
                $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
                $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
                $defectosqry=mysqli_query($con,$defectos);
                while($rowdef=mysqli_fetch_array($defectosqry)){
                $defectos=$rowdef['codigo'];
                }
                // Set the recipient email address
                //$recipientEmail = 'garridofth@outlook.com';
                //$recipientEmail = 'jolaes@mx.bergstrominc.com';
                // Set the email subject and sender details
                $subject = 'Primera pieza PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
                
                $senderEmail = 'garridofth@outlook.com';
                $headers = "From: $senderEmail\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx,luis.valdivia99@outlook.com, batterycable.fth@outlook.com, david-villa88@outlook.com';
                
                 $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
                }else    if($revin=="PRIM" and ($emailcliente=="KALMAR" or $emailcliente=="MODINE" or $emailcliente== "NILFISK")) {
                $revf=substr($rev,4);
                date_default_timezone_set('America/Mexico_City');
                $date = date('d-m-Y');
                $time=date('H:i');
                // Start constructing the email content
                $message = '<div align="center"><h1>Primera pieza PRUEBA ELECTRICA </h1></div> <br><br>';
                $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la Primera pieza siguiente<br>';
                $message .='<br><h2>Cliente:</h2>'.$emailcliente;
                $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
                $message .='<br><h2>Primera pieza en revision:</h2>'.$revf;
                $message .='<br><h2>Con Work order:</h2>'.$emailwo;
                $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
                $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
                $message .='<br><br><br>Con las siguientes anotaciones:';
                $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
                $defectosqry=mysqli_query($con,$defectos);
                while($rowdef=mysqli_fetch_array($defectosqry)){
                $defectos=$rowdef['codigo'];
                $message.='<br>'.$defectos;
                }
                
                $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
                // Set the recipient email address
                //$recipientEmail = 'garridofth@outlook.com';
                //$recipientEmail = 'jolaes@mx.bergstrominc.com';
                // Set the email subject and sender details
                $subject = 'Primera pieza PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
                
                $senderEmail = 'garridofth@outlook.com';
                $headers = "From: $senderEmail\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
                
                 $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
                }else    if($revin=="PRIM" and ($emailcliente=="TICO MANUFACTURING" or $emailcliente=="COLLINS" or $emailcliente=="PROTERRA" or $emailcliente=="SPARTAN" or $emailcliente=="SHYFT" )){
                $revf=substr($rev,4);
                date_default_timezone_set('America/Mexico_City');
                $date = date('d-m-Y');
                $time=date('H:i');
                // Start constructing the email content
                $message = '<div align="center"><h1>Primera pieza PRUEBA ELECTRICA </h1></div> <br><br>';
                $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la Primera pieza siguiente<br>';
                $message .='<br><h2>Cliente:</h2>'.$emailcliente;
                $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
                $message .='<br><h2>Primera pieza en revision:</h2>'.$revf;
                $message .='<br><h2>Con Work order:</h2>'.$emailwo;
                $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
                $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
                $message .='<br><br><br>Con las siguientes anotaciones:';
                $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
                $defectosqry=mysqli_query($con,$defectos);
                while($rowdef=mysqli_fetch_array($defectosqry)){
                $defectos=$rowdef['codigo'];
                $message.='<br>'.$defectos;
                }
                
                $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
                // Set the recipient email address
                //$recipientEmail = 'garridofth@outlook.com';
                //$recipientEmail = 'jolaes@mx.bergstrominc.com';
                // Set the email subject and sender details
                $subject = 'Primera pieza PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
                
                $senderEmail = 'garridofth@outlook.com';
                $headers = "From: $senderEmail\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
                
                 $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
                }else    if($revin=="PRIM" and ($emailcliente=="FOREST" or $emailcliente=="ZOELLER" )){
                $revf=substr($rev,4);
                date_default_timezone_set('America/Mexico_City');
                $date = date('d-m-Y');
                $time=date('H:i');
                // Start constructing the email content
                $message = '<div align="center"><h1>Primera pieza PRUEBA ELECTRICA </h1></div> <br><br>';
                $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la Primera pieza siguiente<br>';
                $message .='<br><h2>Cliente:</h2>'.$emailcliente;
                $message .='<br><h2>Numero de parte:</h2>'.$emailpn;
                $message .='<br><h2>Primera pieza en revision:</h2>'.$revf;
                $message .='<br><h2>Con Work order:</h2>'.$emailwo;
                $message .='<br><h2>Con Sono order:</h2>'.$emailpo;
                $message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
                $message .='<br><br><br>Con las siguientes anotaciones:';
                $defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
                $defectosqry=mysqli_query($con,$defectos);
                while($rowdef=mysqli_fetch_array($defectosqry)){
                $defectos=$rowdef['codigo'];
                $message.='<br>'.$defectos;
                }
                
                $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
                // Set the recipient email address
                //$recipientEmail = 'garridofth@outlook.com';
                //$recipientEmail = 'jolaes@mx.bergstrominc.com';
                // Set the email subject and sender details
                $subject = 'Primera pieza PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;
                
                $senderEmail = 'garridofth@outlook.com';
                $headers = "From: $senderEmail\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                $recipientEmail = 'egaona@mx.bergstrominc.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, jesus.zamarripa07@outlook.com';
                
                 $mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com'; // Replace with your SMTP username
    $mail->Password   = 'Garridom.13'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
                } else {
                // Handle database error, perhaps log it for debugging
                echo "Error: " . $deltenull->error;
            }
        
            // Close the prepared statement
            $deltenull->close();
        
             }}
        
            }
            header("location:fallas.php");
        
        
        
        
        