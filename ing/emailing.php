<?php 
require "../app/conection.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
date_default_timezone_set('America/Mexico_City');
$actualDate = date('d-m-Y H:i');

$message = '<html><body>';
$message .= '<br><br><br><h1 align="center"> Registro de solicitud de trabajo</h1>';

$buscar="SELECT * FROM reqing WHERE count='0' ";
$qry=mysqli_query($con,$buscar);
while($row=mysqli_fetch_array($qry)){
    $type=$row['type'];
    $info=$row['info'];
    $who=$row['who'];
    $donde=$row['donde'];

}
$message .= '<p>Se registro una nueva tarea</p><br><br>';
$message .= '<p> Tipo de trabajo: '.$type.'</p><br>';
$message .= '<p> Descripcion del trabajo: '.$info.'</p><br>';
$message .= '<p> Solicitado por: '.$who.'</p><br>';
$message .= '<p> En el area: '.$donde.'</p><br>';
$message .= '<br><p> De antemano Agradezco su atencion<br>ATTE: J.G.</p><br>';


mysqli_close($con);




$subject = 'New Task' ;
    

$headers = "From: jorgegarrdio@gmail.com\r\n";
$headers .= "Content-Type: text/html; charset=utf-8\r\n";
$recipientEmail = 'jcervera@mx.bergstrominc.com';


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
    
   
    
//910508
    

    
    $mail->isHTML(true); 
    $mail->Subject = $subject;
    $mail->Body    = $message;

    
    $mail->send();
    echo 'Email sent successfully.';
    header("location:reqingeng.php");

} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
