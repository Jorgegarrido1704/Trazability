<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("location: index.html");
    exit(); 
}

require "../app/conection.php"; 


$con = mysqli_connect($host, $user, $clave, $bd);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $rev=mysqli_real_escape_string($con,$_POST['rev']);
    $rev=strtoupper($rev);
    $parte = mysqli_real_escape_string($con, $_POST['parte']);  
    $cliente = mysqli_real_escape_string($con, $_POST['Cliente']);
    $Qty = mysqli_real_escape_string($con, $_POST['cantidad']);
    $wo = mysqli_real_escape_string($con, $_POST['workOrder']);
    $po = mysqli_real_escape_string($con, $_POST['po']);
    $description = mysqli_real_escape_string($con, $_POST['desc']);  
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $send = mysqli_real_escape_string($con, $_POST['sento']);
    $orday = mysqli_real_escape_string($con, $_POST['orday']);
    $reqday = mysqli_real_escape_string($con, $_POST['reqday']);

    
    

    

    $barcodeImage = $_POST['barcodeValue'];
    $info = mysqli_real_escape_string($con, $_POST['barcodeContent']);
    $barcodeImageData = base64_encode(file_get_contents($barcodeImage));

    date_default_timezone_set('America/Mexico_City');
    $fecha = date('d-m-Y H:i');
    $donde = "En espera de proceso";
    $count = 1;

    
        $duplicate="SELECT wo From registro WHERE wo='$wo'";
        $qrydup=mysqli_query($con,$duplicate);
                $numdup=mysqli_num_rows($qrydup);
                if($numdup){
                    ?>
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="refresh" content="5; url=work.php">
                        <title>Document</title>
                    </head>
                    <body style="background-color: red;">
                        <div align=center>
                            <h1>Esta duplicada</h1>

                        </div>
                    </body>
                    </html>
                    
                    
                    <?php

                }else{
        $insertar = "INSERT INTO `registro`( `fecha`, `NumPart`, `cliente`, `rev`, `wo`, `po`, `Qty`, `Barcode`, `info`, `donde`, `count`, `tiempototal`, `paro`, `description`, `price`, `sento`, `orday`, `reqday`) VALUES ('$fecha','$parte','$cliente','$rev','$wo','$po','$Qty','0','$info','En espera de proceso','1','','','$description','$price','$send','$orday','$reqday')";
        $qry = mysqli_query($con, $insertar);
        /*$inseremb="INSERT INTO `embarque`(`id`, `client`, `pn`, `qty`, `price`, `datein`, `dateout`, `parcial`, `info`) VALUES ('','$cliente','$parte','$Qty','$price','','','No','$info')";
        $qtyemb=mysqli_query($con,$inseremb);*/
        
    $updatepo="UPDATE po SET count= '1' WHERE pn='$parte' and po='$po' ";
    $updateqry=mysqli_query($con,$updatepo);
 /*       $kits = "INSERT INTO `registrokits`(`id`, `fecha`, `NumPart`, `cliente`, `wo`, `po`, `Qty`, `info`, `count`) VALUES ('','$fecha','$parte','$cliente','$wo','$po','$Qty','$info','$count')";
        $kit = mysqli_query($con, $kits);*/
        
        $tiempo = "INSERT INTO `tiempos`(`info`, `planeacion`, `corte`, `liberacion`, `ensamble`, `loom`, `calidad`, `embarque`,`kitsinicial`, `kitsfinal`,`retrabajoi`,`retrabajof`,`totalparos`) VALUES ('$info','','','','','','','','$fecha','','','','')";
        $tiempos = mysqli_query($con, $tiempo);
        
      
    $revin=substr($rev,0,4);
    if($revin=="PPAP" and ($cliente=="BERGSTROM" or $cliente=="BLUE BIRD")){
    $revf=substr($rev,4);
    date_default_timezone_set('America/Mexico_City');
    $date = date('d-m-Y');
    $time=date('H:i');
    
    $message = '<div align="center"><h1>Alta PPAP</h1></div> <br><br>';
    $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br>se bajo la PPAP siguiente<br>';
    $message .='<br><h2>Cliente:</h2>'.$cliente;
    $message .='<br><h2>Numero de parte:</h2>'.$parte;
    $message .='<br><h2>PPAP en revision:</h2>'.$revf;
    $message .='<br><h2>Con Work order:</h2>'.$wo;
    $message .='<br><h2>Con Sono order:</h2>'.$po;
    $message .='<br><h2>Por la cantida de:</h2>'.$Qty;
    $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
    
    
    
    
    
    
    $subject = 'Alta PPAP '.$cliente.'NP '.$parte.' en REV '.$revf ;
    
    
    $headers = "From: jorgegarrdio@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $recipientEmail = 'david-villa88@outlook.com,cibarra@mx.bergstrominc.com, jcervantes@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com, egaona@mx.bergstrominc.com, andrea.pacheco.fth@outlook.com, fer.segovia.fast@outlook.com, gonzalez.fast.turn4@outlook.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, luis.valdivia99@outlook.com, batterycable.fth@outlook.com, jesus.zamarripa07@outlook.com';
        
    $mail = new PHPMailer(true);

try {
    
      $mail->isSMTP();
     $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
    $mail->Password   = 'xfnv acmh rvgj tpna'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;


    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    
   
    

    

    
    $mail->isHTML(true); 
    $mail->Subject = $subject;
    $mail->Body    = $message;

    
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}

}else    if($revin=="PPAP" and ($cliente=="ATLAS COPCO" or $cliente=="UTILIMASTER")){
    $revf=substr($rev,4);
    date_default_timezone_set('America/Mexico_City');
    $date = date('d-m-Y');
    $time=date('H:i');
    
    $message = '<div align="center"><h1>Alta PPAP</h1></div> <br><br>';
    $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br>se bajo la PPAP siguiente<br>';
    $message .='<br><h2>Cliente:</h2>'.$cliente;
    $message .='<br><h2>Numero de parte:</h2>'.$parte;
    $message .='<br><h2>PPAP en revision:</h2>'.$revf;
    $message .='<br><h2>Con Work order:</h2>'.$wo;
    $message .='<br><h2>Con Sono order:</h2>'.$po;
    $message .='<br><h2>Por la cantida de:</h2>'.$Qty;
    $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
    
    
    
    
    
    
    $subject = 'Alta PPAP '.$cliente.'NP '.$parte.' en REV '.$revf ;
    
    
    $headers = "From: jorgegarrdio@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $recipientEmail = 'david-villa88@outlook.com,cibarra@mx.bergstrominc.com, jcervantes@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com, egaona@mx.bergstrominc.com, andrea.pacheco.fth@outlook.com, fer.segovia.fast@outlook.com, gonzalez.fast.turn4@outlook.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, luis.valdivia99@outlook.com, batterycable.fth@outlook.com, b.olvera.fast-turn@outlook.com';
    
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
     $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
    $mail->Password   = 'xfnv acmh rvgj tpna'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    
        $recipients= explode(',',$recipientEmail);
        foreach($recipients as $recipient){
            $mail->addAddress(trim($recipient));
        }
        
       
        
    
        
    
        
        $mail->isHTML(true); 
        $mail->Subject = $subject;
        $mail->Body    = $message;
    
        
        $mail->send();
        echo 'Email sent successfully.';
    
    } catch (Exception $e) {
        echo 'Error sending email: ', $mail->ErrorInfo;
    }
    
}else    if($revin=="PPAP" and ($cliente=="EL DORADO CALIFORNIA")){
    $revf=substr($rev,4);
    date_default_timezone_set('America/Mexico_City');
    $date = date('d-m-Y');
    $time=date('H:i');
    
    $message = '<div align="center"><h1>Alta PPAP</h1></div> <br><br>';
    $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br>se bajo la PPAP siguiente<br>';
    $message .='<br><h2>Cliente:</h2>'.$cliente;
    $message .='<br><h2>Numero de parte:</h2>'.$parte;
    $message .='<br><h2>PPAP en revision:</h2>'.$revf;
    $message .='<br><h2>Con Work order:</h2>'.$wo;
    $message .='<br><h2>Con Sono order:</h2>'.$po;
    $message .='<br><h2>Por la cantida de:</h2>'.$Qty;
    $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
    
    
    
    
    
    
    $subject = 'Alta PPAP '.$cliente.'NP '.$parte.' en REV '.$revf ;
    
    
    $headers = "From: jorgegarrdio@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $recipientEmail = 'david-villa88@outlook.com,cibarra@mx.bergstrominc.com, jcervantes@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com, egaona@mx.bergstrominc.com, andrea.pacheco.fth@outlook.com, fer.segovia.fast@outlook.com, gonzalez.fast.turn4@outlook.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, luis.valdivia99@outlook.com, batterycable.fth@outlook.com, david-villa88@outlook.com';
      
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
     $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
    $mail->Password   = 'xfnv acmh rvgj tpna'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    
        $recipients= explode(',',$recipientEmail);
        foreach($recipients as $recipient){
            $mail->addAddress(trim($recipient));
        }
        
       
        
    
        
    
        
        $mail->isHTML(true); 
        $mail->Subject = $subject;
        $mail->Body    = $message;
    
        
        $mail->send();
        echo 'Email sent successfully.';
    
    } catch (Exception $e) {
        echo 'Error sending email: ', $mail->ErrorInfo;
    }
    
}else    if($revin=="PPAP" and ($cliente=="KALMAR" or $cliente=="MODINE" or $cliente== "NILFISK")) {
    $revf=substr($rev,4);
    date_default_timezone_set('America/Mexico_City');
    $date = date('d-m-Y');
    $time=date('H:i');
    
    $message = '<div align="center"><h1>Alta PPAP</h1></div> <br><br>';
    $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br>se bajo la PPAP siguiente<br>';
    $message .='<br><h2>Cliente:</h2>'.$cliente;
    $message .='<br><h2>Numero de parte:</h2>'.$parte;
    $message .='<br><h2>PPAP en revision:</h2>'.$revf;
    $message .='<br><h2>Con Work order:</h2>'.$wo;
    $message .='<br><h2>Con Sono order:</h2>'.$po;
    $message .='<br><h2>Por la cantida de:</h2>'.$Qty;
    $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
    
    
    
    
    
    
    $subject = 'Alta PPAP '.$cliente.'NP '.$parte.' en REV '.$revf ;
    
    
    $headers = "From: jorgegarrdio@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $recipientEmail = 'david-villa88@outlook.com,cibarra@mx.bergstrominc.com, jcervantes@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com, egaona@mx.bergstrominc.com, andrea.pacheco.fth@outlook.com, fer.segovia.fast@outlook.com, gonzalez.fast.turn4@outlook.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, luis.valdivia99@outlook.com, batterycable.fth@outlook.com, ale.gaona.pw@outlook.es';
    
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
     $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
    $mail->Password   = 'xfnv acmh rvgj tpna'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    
        $recipients= explode(',',$recipientEmail);
        foreach($recipients as $recipient){
            $mail->addAddress(trim($recipient));
        }
        
       
        
    
        
    
        
        $mail->isHTML(true); 
        $mail->Subject = $subject;
        $mail->Body    = $message;
    
        
        $mail->send();
        echo 'Email sent successfully.';
    
    } catch (Exception $e) {
        echo 'Error sending email: ', $mail->ErrorInfo;
    }
    
}else    if($revin=="PPAP" and ($cliente=="TICO MANUFACTURING" or $cliente=="COLLINS" or $cliente=="PHOENIX MOTOR CARS" or $cliente=="SPARTAN" or $cliente=="SHYFT" )){
    $revf=substr($rev,4);
    date_default_timezone_set('America/Mexico_City');
    $date = date('d-m-Y');
    $time=date('H:i');
    
    $message = '<div align="center"><h1>Alta PPAP</h1></div> <br><br>';
    $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br>se bajo la PPAP siguiente<br>';
    $message .='<br><h2>Cliente:</h2>'.$cliente;
    $message .='<br><h2>Numero de parte:</h2>'.$parte;
    $message .='<br><h2>PPAP en revision:</h2>'.$revf;
    $message .='<br><h2>Con Work order:</h2>'.$wo;
    $message .='<br><h2>Con Sono order:</h2>'.$po;
    $message .='<br><h2>Por la cantida de:</h2>'.$Qty;
    $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
    
    
    
    
    
    
    $subject = 'Alta PPAP '.$cliente.'NP '.$parte.' en REV '.$revf ;
    
    
    $headers = "From: jorgegarrdio@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $recipientEmail = 'david-villa88@outlook.com,cibarra@mx.bergstrominc.com, jcervantes@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com, egaona@mx.bergstrominc.com, andrea.pacheco.fth@outlook.com, fer.segovia.fast@outlook.com, gonzalez.fast.turn4@outlook.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, luis.valdivia99@outlook.com, batterycable.fth@outlook.com, saulcastro6845@gmail.com';
       
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
     $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
    $mail->Password   = 'xfnv acmh rvgj tpna'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    
        $recipients= explode(',',$recipientEmail);
        foreach($recipients as $recipient){
            $mail->addAddress(trim($recipient));
        }
        
       
        
    
        
    
        
        $mail->isHTML(true); 
        $mail->Subject = $subject;
        $mail->Body    = $message;
    
        
        $mail->send();
        echo 'Email sent successfully.';
    
    } catch (Exception $e) {
        echo 'Error sending email: ', $mail->ErrorInfo;
    }
    
}else    if($revin=="PPAP" and ($cliente=="FOREST" or $cliente=="ZOELLER" )){
    $revf=substr($rev,4);
    date_default_timezone_set('America/Mexico_City');
    $date = date('d-m-Y');
    $time=date('H:i');
    
    $message = '<div align="center"><h1>Alta PPAP</h1></div> <br><br>';
    $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br>se bajo la PPAP siguiente<br>';
    $message .='<br><h2>Cliente:</h2>'.$cliente;
    $message .='<br><h2>Numero de parte:</h2>'.$parte;
    $message .='<br><h2>PPAP en revision:</h2>'.$revf;
    $message .='<br><h2>Con Work order:</h2>'.$wo;
    $message .='<br><h2>Con Sono order:</h2>'.$po;
    $message .='<br><h2>Por la cantida de:</h2>'.$Qty;
    $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
    
    
    
    
    
    
    $subject = 'Alta PPAP '.$cliente.'NP '.$parte.' en REV '.$revf ;
    
    
    $headers = "From: jorgegarrdio@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $recipientEmail = 'david-villa88@outlook.com,cibarra@mx.bergstrominc.com, jcervantes@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com, egaona@mx.bergstrominc.com, andrea.pacheco.fth@outlook.com, fer.segovia.fast@outlook.com, gonzalez.fast.turn4@outlook.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, luis.valdivia99@outlook.com, batterycable.fth@outlook.com';
       
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
     $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
    $mail->Password   = 'xfnv acmh rvgj tpna'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    
        $recipients= explode(',',$recipientEmail);
        foreach($recipients as $recipient){
            $mail->addAddress(trim($recipient));
        }
        
       
        
    
        
    
        
        $mail->isHTML(true); 
        $mail->Subject = $subject;
        $mail->Body    = $message;
    
        
        $mail->send();
        echo 'Email sent successfully.';
    
    } catch (Exception $e) {
        echo 'Error sending email: ', $mail->ErrorInfo;
    }
    
}


$revin=substr($rev,0,4);
    if($revin=="PRIM" and ($cliente=="BERGSTROM" or $cliente=="BLUE BIRD")){
    $revf=substr($rev,4);
    date_default_timezone_set('America/Mexico_City');
    $date = date('d-m-Y');
    $time=date('H:i');
    
    $message = '<div align="center"><h1>Alta liberacion primera pieza(hoja amarilla)</h1></div> <br><br>';
    $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br>se bajo la liberacion primera pieza(hoja amarilla) siguiente<br>';
    $message .='<br><h2>Cliente:</h2>'.$cliente;
    $message .='<br><h2>Numero de parte:</h2>'.$parte;
    $message .='<br><h2>liberacion primera pieza(hoja amarilla) en revision:</h2>'.$revf;
    $message .='<br><h2>Con Work order:</h2>'.$wo;
    $message .='<br><h2>Con Sono order:</h2>'.$po;
    $message .='<br><h2>Por la cantida de:</h2>'.$Qty;
    $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
    
    
    
    
    
    
    $subject = 'Alta liberacion primera pieza(hoja amarilla) '.$cliente.'NP '.$parte.' en REV '.$revf ;
    
    
    $headers = "From: jorgegarrdio@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $recipientEmail = 'david-villa88@outlook.com,cibarra@mx.bergstrominc.com, jcervantes@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com, egaona@mx.bergstrominc.com, andrea.pacheco.fth@outlook.com, fer.segovia.fast@outlook.com, gonzalez.fast.turn4@outlook.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, luis.valdivia99@outlook.com, batterycable.fth@outlook.com, jesus.zamarripa07@outlook.com';
        
    $mail = new PHPMailer(true);

    try {
        
    $mail->isSMTP();
     $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
    $mail->Password   = 'xfnv acmh rvgj tpna'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    
        $recipients= explode(',',$recipientEmail);
        foreach($recipients as $recipient){
            $mail->addAddress(trim($recipient));
        }
        
       
        
    
        
    
        
        $mail->isHTML(true); 
        $mail->Subject = $subject;
        $mail->Body    = $message;
    
        
        $mail->send();
        echo 'Email sent successfully.';
    
    } catch (Exception $e) {
        echo 'Error sending email: ', $mail->ErrorInfo;
    }
    
}else    if($revin=="PRIM" and ($cliente=="ATLAS COPCO" or $cliente=="UTILIMASTER")){
    $revf=substr($rev,4);
    date_default_timezone_set('America/Mexico_City');
    $date = date('d-m-Y');
    $time=date('H:i');
    
    $message = '<div align="center"><h1>Alta liberacion primera pieza(hoja amarilla)</h1></div> <br><br>';
    $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br>se bajo la liberacion primera pieza(hoja amarilla) siguiente<br>';
    $message .='<br><h2>Cliente:</h2>'.$cliente;
    $message .='<br><h2>Numero de parte:</h2>'.$parte;
    $message .='<br><h2>liberacion primera pieza(hoja amarilla) en revision:</h2>'.$revf;
    $message .='<br><h2>Con Work order:</h2>'.$wo;
    $message .='<br><h2>Con Sono order:</h2>'.$po;
    $message .='<br><h2>Por la cantida de:</h2>'.$Qty;
    $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
    
    
    
    
    
    
    $subject = 'Alta liberacion primera pieza(hoja amarilla) '.$cliente.'NP '.$parte.' en REV '.$revf ;
    
    
    $headers = "From: jorgegarrdio@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $recipientEmail = 'david-villa88@outlook.com,cibarra@mx.bergstrominc.com, jcervantes@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com, egaona@mx.bergstrominc.com, andrea.pacheco.fth@outlook.com, fer.segovia.fast@outlook.com, gonzalez.fast.turn4@outlook.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, luis.valdivia99@outlook.com, batterycable.fth@outlook.com, b.olvera.fast-turn@outlook.com';
    
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
     $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
    $mail->Password   = 'xfnv acmh rvgj tpna'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    
        $recipients= explode(',',$recipientEmail);
        foreach($recipients as $recipient){
            $mail->addAddress(trim($recipient));
        }
        
       
        
    
        
    
        
        $mail->isHTML(true); 
        $mail->Subject = $subject;
        $mail->Body    = $message;
    
        
        $mail->send();
        echo 'Email sent successfully.';
    
    } catch (Exception $e) {
        echo 'Error sending email: ', $mail->ErrorInfo;
    }
    
}else    if($revin=="PRIM" and ($cliente=="EL DORADO CALIFORNIA")){
    $revf=substr($rev,4);
    date_default_timezone_set('America/Mexico_City');
    $date = date('d-m-Y');
    $time=date('H:i');
    
    $message = '<div align="center"><h1>Alta liberacion primera pieza(hoja amarilla)</h1></div> <br><br>';
    $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br>se bajo la liberacion primera pieza(hoja amarilla) siguiente<br>';
    $message .='<br><h2>Cliente:</h2>'.$cliente;
    $message .='<br><h2>Numero de parte:</h2>'.$parte;
    $message .='<br><h2>liberacion primera pieza(hoja amarilla) en revision:</h2>'.$revf;
    $message .='<br><h2>Con Work order:</h2>'.$wo;
    $message .='<br><h2>Con Sono order:</h2>'.$po;
    $message .='<br><h2>Por la cantida de:</h2>'.$Qty;
    $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
    
    
    
    
    
    
    $subject = 'Alta liberacion primera pieza(hoja amarilla) '.$cliente.'NP '.$parte.' en REV '.$revf ;
    
    
    $headers = "From: jorgegarrdio@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $recipientEmail = 'david-villa88@outlook.com,cibarra@mx.bergstrominc.com, jcervantes@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com, egaona@mx.bergstrominc.com, andrea.pacheco.fth@outlook.com, fer.segovia.fast@outlook.com, gonzalez.fast.turn4@outlook.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, luis.valdivia99@outlook.com, batterycable.fth@outlook.com, david-villa88@outlook.com';
      
  $mail = new PHPMailer(true);

try {
    
      $mail->isSMTP();
     $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
    $mail->Password   = 'xfnv acmh rvgj tpna'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;


    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    
   
    

    

    
    $mail->isHTML(true); 
    $mail->Subject = $subject;
    $mail->Body    = $message;

    
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}

}else    if($revin=="PRIM" and ($cliente=="KALMAR" or $cliente=="MODINE" or $cliente== "NILFISK")) {
    $revf=substr($rev,4);
    date_default_timezone_set('America/Mexico_City');
    $date = date('d-m-Y');
    $time=date('H:i');
    
    $message = '<div align="center"><h1>Alta liberacion primera pieza(hoja amarilla)</h1></div> <br><br>';
    $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br>se bajo la liberacion primera pieza(hoja amarilla) siguiente<br>';
    $message .='<br><h2>Cliente:</h2>'.$cliente;
    $message .='<br><h2>Numero de parte:</h2>'.$parte;
    $message .='<br><h2>liberacion primera pieza(hoja amarilla) en revision:</h2>'.$revf;
    $message .='<br><h2>Con Work order:</h2>'.$wo;
    $message .='<br><h2>Con Sono order:</h2>'.$po;
    $message .='<br><h2>Por la cantida de:</h2>'.$Qty;
    $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
    
    
    
    
    
    
    $subject = 'Alta liberacion primera pieza(hoja amarilla) '.$cliente.'NP '.$parte.' en REV '.$revf ;
    
    
    $headers = "From: jorgegarrdio@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $recipientEmail = 'david-villa88@outlook.com,cibarra@mx.bergstrominc.com, jcervantes@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com, egaona@mx.bergstrominc.com, andrea.pacheco.fth@outlook.com, fer.segovia.fast@outlook.com, gonzalez.fast.turn4@outlook.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, luis.valdivia99@outlook.com, batterycable.fth@outlook.com, ale.gaona.pw@outlook.es';
    
  $mail = new PHPMailer(true);

try {
    
      $mail->isSMTP();
     $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
    $mail->Password   = 'xfnv acmh rvgj tpna'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;


    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    
   
    

    

    
    $mail->isHTML(true); 
    $mail->Subject = $subject;
    $mail->Body    = $message;

    
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}

}else    if($revin=="PRIM" and ($cliente=="TICO MANUFACTURING" or $cliente=="COLLINS" or $cliente=="PHOENIX MOTOR CARS" or $cliente=="SPARTAN" or $cliente=="SHYFT" )){
    $revf=substr($rev,4);
    date_default_timezone_set('America/Mexico_City');
    $date = date('d-m-Y');
    $time=date('H:i');
    
    $message = '<div align="center"><h1>Alta liberacion primera pieza(hoja amarilla)</h1></div> <br><br>';
    $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br>se bajo la liberacion primera pieza(hoja amarilla) siguiente<br>';
    $message .='<br><h2>Cliente:</h2>'.$cliente;
    $message .='<br><h2>Numero de parte:</h2>'.$parte;
    $message .='<br><h2>liberacion primera pieza(hoja amarilla) en revision:</h2>'.$revf;
    $message .='<br><h2>Con Work order:</h2>'.$wo;
    $message .='<br><h2>Con Sono order:</h2>'.$po;
    $message .='<br><h2>Por la cantida de:</h2>'.$Qty;
    $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
    
    
    
    
    
    
    $subject = 'Alta liberacion primera pieza(hoja amarilla) '.$cliente.'NP '.$parte.' en REV '.$revf ;
    
    
    $headers = "From: jorgegarrdio@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $recipientEmail = 'david-villa88@outlook.com,cibarra@mx.bergstrominc.com, jcervantes@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com, egaona@mx.bergstrominc.com, andrea.pacheco.fth@outlook.com, fer.segovia.fast@outlook.com, gonzalez.fast.turn4@outlook.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, luis.valdivia99@outlook.com, batterycable.fth@outlook.com, saulcastro6845@gmail.com';
       
  $mail = new PHPMailer(true);

try {
    
      $mail->isSMTP();
     $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
    $mail->Password   = 'xfnv acmh rvgj tpna'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;


    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    
   
    

    

    
    $mail->isHTML(true); 
    $mail->Subject = $subject;
    $mail->Body    = $message;

    
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}

}else    if($revin=="PRIM" and ($cliente=="FOREST" or $cliente=="ZOELLER" )){
    $revf=substr($rev,4);
    date_default_timezone_set('America/Mexico_City');
    $date = date('d-m-Y');
    $time=date('H:i');
    
    $message = '<div align="center"><h1>Alta liberacion primera pieza(hoja amarilla)</h1></div> <br><br>';
    $message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br>se bajo la liberacion primera pieza(hoja amarilla) siguiente<br>';
    $message .='<br><h2>Cliente:</h2>'.$cliente;
    $message .='<br><h2>Numero de parte:</h2>'.$parte;
    $message .='<br><h2>liberacion primera pieza(hoja amarilla) en revision:</h2>'.$revf;
    $message .='<br><h2>Con Work order:</h2>'.$wo;
    $message .='<br><h2>Con Sono order:</h2>'.$po;
    $message .='<br><h2>Por la cantida de:</h2>'.$Qty;
    $message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';
    
    
    
    
    
    
    $subject = 'Alta liberacion primera pieza(hoja amarilla) '.$cliente.'NP '.$parte.' en REV '.$revf ;
    
    
    $headers = "From: jorgegarrdio@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    $recipientEmail = 'david-villa88@outlook.com,cibarra@mx.bergstrominc.com, jcervantes@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com, egaona@mx.bergstrominc.com, andrea.pacheco.fth@outlook.com, fer.segovia.fast@outlook.com, gonzalez.fast.turn4@outlook.com, jolaes@mx.bergstrominc.com, lramos@mx.bergstrominc.com, emedina@mx.bergstrominc.com, jhoana.fast.turn@outlook.com, jcervera@mx.bergstrominc.com, vestrada@mx.bergstrominc.com, productengineering-fast-turn@outlook.com, fgomez@mx.bergstrominc.com, lmireles@mx.bergstrominc.com, Efrain.vera.fast-turn@outlook.com, jaredti@live.com.mx, luis.valdivia99@outlook.com, batterycable.fth@outlook.com';
       
  $mail = new PHPMailer(true);

try {
    
      $mail->isSMTP();
     $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
    $mail->Password   = 'xfnv acmh rvgj tpna'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;


    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    
   
    

    

    
    $mail->isHTML(true); 
    $mail->Subject = $subject;
    $mail->Body    = $message;

    
    $mail->send();
    echo 'Email sent successfully.';

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}

}


mysqli_close($con);
header("location:../impresion/barcode.php");
}   }            
?>