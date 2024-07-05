
<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php';

require "../app/conection.php";
date_default_timezone_set('America/Mexico_City');
$actualDate = date('d-m-Y H:i');
// Start constructing the email content

$result = mysqli_query($con, "SELECT * FROM diarios");

// Start constructing the email content
$message = '<html><body>';
$message .= '<div> <h1 align="center">Registro de golpeteo de herramental</h1> </div>';
$message .= '<table border="1">';
$message .= '<thead><tr><th>Herramental</th><th>Fecha</th><th>Hora</th><th>Golpes del dia</th><th>Contador de golpes</th></thead>';
$message .= '<tbody>';
$totalcont=0;
while ($row = mysqli_fetch_array($result)) {
    $totalcont+=$row['golpesdiarios'];
       if($row['fecha']=$actualDate){
    $message .= '<tr>';
    $message .= '<td>' . $row['herramental'] . '</td>';
    $message .= '<td>' . $row['fecha'] . '</td>';
    $message .= '<td>' . $row['hora'] . '</td>';
    $message .= '<td>' . $row['golpesdiarios'] . '</td>';
    $message .= '<td>' . $row['golpestotales'] . '</td>';
    
}}



$message .= '</tbody></table>';
$message .= '<p> Total de golpes diarios: '.$totalcont.'</p>';
$message .= '</body></html>';


$subject = 'Reporte de golpeteo '.$actualDate;
$headers = "From: jorgegarrdio@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n"; // Changed charset to UTF-8 for better compatibility
$headers .= "SPF: v=spf1 mx ~all\r\n";
$recipientEmail = 'jgarrido@mx.bergstrominc.com';
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
    $mail->Body    = $message;

    // Send the email
    $mail->send();
 
      
        mysqli_close($conn);    //header('location:emailact.php');
        header('location:../emails/index.html');
} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
 
?>

