<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';



$mail = new PHPMailer(true);

try {
   
    $mail->isSMTP();
    $mail->Host       = 'smtp.outlook.com';  
    $mail->SMTPAuth   = true;
    $mail->Username   = 'garridofth@outlook.com';  
    $mail->Password   = 'Garridom.13';  
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

   
    $mail->setFrom('garridofth@outlook.com', 'Jorge Garrido');  

    
    $mail->addAddress('garridofth@outlook.com', 'Recipient Name');  

   
    $mail->isHTML(true);
    $mail->Subject = 'bd tra';
    $mail->Body    = '<p>This is a back up for you</p>';
    
   
    $attachmentPath = 'C:/xampp/htdocs/system/app/'.$date.'.sql';  
    $mail->addAttachment($attachmentPath);

  
    $mail->send();
    echo 'Email sent successfully.';
} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}

