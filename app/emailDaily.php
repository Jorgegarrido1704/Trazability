<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
date_default_timezone_set("America/Mexico_City");
$date=date("d-m-Y");



$messege = '<html><body><br><div align="center"><h1>Desviacion aprobada</h1></div><br>';

$subject='Desviacion Aprovada -';
$recipientEmail='jgarrido@mx.bergstrominc.com';
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n"; 
$headers .= "SPF: v=spf1 mx ~all\r\n";

function sendEmail($hostM,$userM,$auth,$passM,$sec,$recipientEmail,$subject,$body,$id,$con) : bool {
    $mail = new PHPMailer(true);
try {
    $date=date("d-m-Y");
    $mail->isSMTP();
    $mail->Host       = $hostM; 
   $mail->SMTPAuth   = $auth;
   $mail->Username   = $userM; 
   $mail->Password   = $passM; 
   $mail->SMTPSecure = $sec;
   $mail->Port       = 587;
 
 $mail->setFrom( $userM, 'Jorge Garrido'); 

    $recipients= explode(',',$recipientEmail);
    foreach($recipients as $recipient){
        $mail->addAddress(trim($recipient));
    }
    $mail->isHTML(true); 
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $attachmentPath = 'C:\Users\garri\Downloads\Reporte General ' . $date . '.xlsx';  // Replace with the actual file path
    $mail->addAttachment($attachmentPath);
    // Send the email
    $mail->send();
    
 return true;

       
} catch (Exception $e) {
    echo "Error sending email: $hostM: {$mail->ErrorInfo}";
    return false;
}
}
$host1 = 'smtp.gmail.com'; // Replace with your SMTP server address
$auth1  = true;
$userM1   = 'jorgegarrdio@gmail.com'; // Replace with your SMTP username
$passM1   = 'kdzo nqtp xgza isza'; // Replace with your SMTP password
$secM1 = 'tls';


    if(!sendEmail($host1,$userM1,$auth1,$passM1,$secM1,$recipientEmail,$subject,$messege,$id,$con)){
        $host2= 'mail.bergstrominc.com'; 
        $auth2   = false;
        $userM2 ='jgarrido@mx.bergstrominc.com';
        $passM2="";
        $secM2   = false;  
        if(!sendEmail($host2,$userM2,$auth2,$passM2,$secM2,$recipientEmail,$subject,$messege,$id,$con)){
            echo "no se pudo";
    } }

    
