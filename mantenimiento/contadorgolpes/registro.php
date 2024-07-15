<?php
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;
 require '../../vendor/autoload.php';
 require "../app/conection.php";
session_start();
$herramental=$_POST['dado'];
date_default_timezone_set('America/Mexico_City');
$fecha = strtotime(date('d-m-Y 23:59'));
$diafecha=date('d-m-Y');
$golpesdiarios=intval($_POST['cantidad']);
$today=date("d-m-Y H:i");
$terminal=$_POST['terminal'];
$terminal=strtoupper($terminal);
$conect=mysqli_query($con,"SELECT * FROM mant_golpes_diarios WHERE herramental ='$herramental'and terminal='$terminal' ");
$rowcount=mysqli_num_rows($conect);
while($row=mysqli_fetch_array($conect)){
$golpesDiariosanterio=$row['golpesDiarios'];    
$golpesanterio=intval($row['golpesTotales']);
$totalactual=$row['totalmant'];
$fechas=strtotime($row['fecha_reg']);
$diaFechas=substr($row['fecha_reg'],0,10);

$totalupadate=$golpesdiarios + $golpesanterio;
$totalmant=$totalupadate/5000;
$totalmant=intval($totalmant);
if($fechas<$fecha && $diaFechas==$diafecha){
    $golesdeldia=$golpesDiariosanterio+$golpesdiarios;
    if($totalmant>$totalactual){
    mysqli_query($con, "INSERT INTO mant_golpes (herramental,terminal,fecha_reg,golpesDiarios) values ('$herramental','$terminal','$today','$golpesdiarios')");
        mysqli_query($con,"UPDATE mant_golpes_diarios SET golpesDiarios='$golesdeldia',fecha_reg='$today',maquina='Bodega_aplicadores', golpesTotales='$totalupadate',  totalmant='$totalmant' WHERE herramental ='$herramental' and terminal='$terminal'");     
$message = '<html><body>';
$message .= '<div> <h1 align="center">Registro de golpeteo de herramental</h1> </div>';
$message .= '<div> <h3 align="center">Fecha: '.$diafecha.'</h3>';
$message .= ' <h3 align="center">El Herramental: '.$herramental.' con terminal: '.$terminal.' necesita mantenimiento</h3>';
$message .= ' <h3 align="center">Este seria su mantenimiento N°: '.$totalmant.'</h3>';
$message .= ' </div>';
$message .= '</body></html>';
$subject = 'Matenimiento de herramental '.$herramental;
$headers = "From: jorgegarrdio@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n"; // Changed charset to UTF-8 for better compatibility
$headers .= "SPF: v=spf1 mx ~all\r\n";
$recipientEmail = 'jaredti@live.com.mx';
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
 
      
        mysqli_close($con);   
                header('location:index.php');
} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
}else{
    mysqli_query($con, "INSERT INTO mant_golpes (herramental,terminal,fecha_reg,golpesDiarios) values ('$herramental','$terminal','$today','$golpesdiarios')");
        mysqli_query($con,"UPDATE mant_golpes_diarios SET golpesDiarios='$golesdeldia',fecha_reg='$today', golpesTotales='$totalupadate',maquina='Bodega_aplicadores'  WHERE herramental ='$herramental' and terminal='$terminal' ");
        header('location:index.php');
    }

}else if($fechas<$fecha && $diaFechas!=$diafecha){
    $golesdeldia=$golpesdiarios;
    if($totalmant>$totalactual){
        mysqli_query($con, "INSERT INTO mant_golpes (herramental,fecha_reg,golpesDiarios) values ('$herramental','$today','$golpesdiarios')");
    mysqli_query($con,"UPDATE mant_golpes_diarios SET golpesDiarios='$golesdeldia',fecha_reg='$today', golpesTotales='$totalupadate', maquina='Bodega_aplicadores', totalmant='$totalmant' WHERE herramental ='$herramental' and terminal='$terminal'");
           
$message = '<html><body>';
$message .= '<div> <h1 align="center">Registro de golpeteo de herramental</h1> </div>';
$message .= '<div> <h3 align="center">Fecha: '.$diafecha.'</h3>';
$message .= ' <h3 align="center">El Herramental: '.$herramental.'  con terminal: '.$terminal.' necesita mantenimiento</h3>';
$message .= ' <h3 align="center">Este seria su mantenimiento N°: '.$totalmant.'</h3>';
$message .= ' </div>';
$message .= '</body></html>';
$subject = 'Matenimiento de herramental '.$herramental;
$headers = "From: jorgegarrdio@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n"; // Changed charset to UTF-8 for better compatibility
$headers .= "SPF: v=spf1 mx ~all\r\n";
$recipientEmail = 'jaredti@live.com.mx';
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
 
      
        mysqli_close($con);   
                header('location:index.php');
} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
}else{
    mysqli_query($con, "INSERT INTO mant_golpes (herramental,terminal,fecha_reg,golpesDiarios) values ('$herramental','$terminal','$today','$golpesdiarios')");
        mysqli_query($con,"UPDATE mant_golpes_diarios SET golpesDiarios='$golesdeldia',fecha_reg='$today', golpesTotales='$totalupadate', maquina='Bodega_aplicadores' WHERE herramental ='$herramental' AND terminal ='$terminal' ");
        header('location:index.php');
    }

}


}
if($rowcount==0){
    mysqli_query($con, "INSERT INTO mant_golpes (herramental,terminal,fecha_reg,golpesDiarios) values ('$herramental','$terminal','$today','$golpesdiarios')");
    mysqli_query($con,"INSERT INTO mant_golpes_diarios (herramental,terminal,fecha_reg,golpesDiarios,golpesTotales,maquina,totalmant)VALUES('$herramental','$terminal','$today',$golpesdiarios,$golpesdiarios,'Bodega_aplicadores',0)");
    header('location:index.php');
}
header("location=index.php");

?>