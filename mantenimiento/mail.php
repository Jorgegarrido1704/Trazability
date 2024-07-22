<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../app/vendor/autoload.php';
require "../app/conection.php";

$buscar="SELECT * FROM mant_golpes_diarios WHERE  mantenimiento='falta1'";
$sqli=mysqli_query($con,$buscar);
if($sqli){
    if($numR=mysqli_num_rows($sqli)<=0){        header("location:../../../ing/emails/index.html"); }else{
    mysqli_data_seek($sqli, $numR=mysqli_num_rows($sqli)-1);
    $row=mysqli_fetch_assoc($sqli); 
    $id=$row['id'];
     $fecha=$row['fecha_reg']; 
     $herramental=$row['herramental'];
     $terminal=$row['terminal'];
     $totalmant=$row['totalmant'];
  

$messege = '<html><body><br><div align="center"><h1>Se necesita mantenimiento</h1></div><br>';
$messege .= '<div align="center"><h2>El dia de hoy '.$fecha.'.</h2><br></div>';
$messege .= '<div align="center"><h2>Se solicita mantenimiento de '.$herramental.'. </h2><br></div>';
$messege .= '<div align="center"><h2>Con la terminal '.$terminal.'</h2><br></div>';
$messege .= '<div align="center"><h2>Este sera el mantenimiento No. '.$totalmant.'.</h2><br></div>';

$subject='Mantenimiento preventivo -'.$herramental.' - '.$terminal;
$recipientEmail='jolaes@mx.bergstrominc.com,jaredti@live.com.com,gonzalez.fast.turn4@outlook.com';
$headers = "From: jorgegarrdio@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n"; // Changed charset to UTF-8 for better compatibility
$headers .= "SPF: v=spf1 mx ~all\r\n";

$mail = new PHPMailer(true);

try {
    // Server settings
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
    // Recipients
   // $mail->setFrom($senderEmail);
    //$mail->addAddress($recipientEmail);

    // Attach file
//    $mail->addAttachment($file_path);
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $messege;

    // Send the email
    $mail->send();
 
        echo 'Daily report email sent successfully.';
        $update="UPDATE mant_golpes_diarios SET mantenimiento='falta' WHERE id='$id'";
        $qryup=mysqli_query($con,$update);
        mysqli_close($con);    
        header("location:../../../ing/emails/index.html");
       
} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
}      

}
header("location:../../../ing/emails/index.html");
