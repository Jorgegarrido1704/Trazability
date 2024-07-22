<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../app/vendor/autoload.php';
require "../app/conection.php";
$today=date("d-m-Y");
$threeMontBef=date('d-m-Y ', strtotime('-3 months'));
$herramentales=[];
$terminales=[];
$ids=[];
$i=0;
echo $threeMontBef;

$buscar="SELECT * FROM mant_golpes_diarios WHERE  mantenimiento!='falta1' or mantenimiento!='falta'";
$sqli=mysqli_query($con,$buscar);
$numrow=mysqli_num_rows($sqli);
if($numrow<=0){        header("location:../actividades/emailact.php"); }
else {
    

while($row=mysqli_fetch_array($sqli)){
    

    
    $id=$row['id'];
     $fecha=substr($row['fecha_reg'],0,10); 

     $herramental=$row['herramental'];
     $terminal=$row['terminal'];
     $totalmant=$row['totalmant'];
     $mantenimiento=$row['mantenimiento'];
if(strtotime($fecha) < strtotime($threeMontBef) and $mantenimiento!='falta' and $i<4){
    $ids[$i] = $id;
    $herramentales[$i] = $herramental;
    $terminales[$i] = $terminal;
    echo $herramentales[$i].'-'.$terminales[$i];
    $i++;
}   
}  
    
$messege = '<html><body><br><div align="center"><h1>Faltan mantenimiento preventivo</h1></div><br>';
$messege .= '<div align="center"><h2>El dia de hoy '.$today.'.</h2><br></div>';
$messege .= '<div align="center"><h2>Se solicita mantenimiento de preventivo de los siguientes equipos.</h2><br></div>';
for($i=0; $i < 4; $i++) {
    $her=$herramentales[$i];
    $term=$terminales[$i];
$messege .= '<div align="center"><h2> '.$her.' Con la terminal '.$term.' </h2><br></div>';
}
$subject='Mantenimientos preventivos ';
$recipientEmail='jgarrido@mx.bergstrominc.com';
$headers = "From: jorgegarrdio@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n"; 
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
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $messege;
    $mail->send();
        echo 'Daily report email sent successfully.';
        for($i=0; $i < count($herramentales); $i++) {
        $update="UPDATE mant_golpes_diarios SET mantenimiento='falta' WHERE herramental='$herramentales[$i]' and terminal='$terminales[$i]'";
        $qryup=mysqli_query($con,$update);
        }
        mysqli_close($con);    
        header("location:../reportes/reportes/index.html");
       
} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
     
}

header("location:../reportes/reportes/index.html");
