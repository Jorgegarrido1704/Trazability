<?php
session_start();
require "app.php"; 

date_default_timezone_set('America/Mexico_City');
//$actualDate = date('d-m-Y');
$mes='';
$sql = "SELECT * FROM registro_pull WHERE fecha LIKE '%$mes'";
$result = mysqli_query($con, $sql);

// Start constructing the email content
$message = '<html><body>';
$message .= '<div><img src="condunet.png" align="left"> <h1 align="right">Tensile Strength Pull Test</h1> </div>';
$message .= '<table border="1">';
$message .= '<thead><tr><th>Fecha</th><th>Turno</th><th>Calibre</th><th>Modelo</th><th>Número de Parte</th><th>Valor especificado</th><th>Resistencia a la tensión</th><th>Máquina o pinza con la que se aplicó la terminal</th><th>Aprobatorio</th><th>No Aprobatorio</th><th>Nombre de quién realizó la prueba</th><th>Nombre del inspector de calidad</th></th>Status<th></tr></thead>';
$message .= '<tbody>';

while ($row = mysqli_fetch_array($result)) {
    if($row['calibre']==8){
        $ValueResist=90;
       }else if($row['calibre']==10){
        $ValueResist=80;
       }else if($row['calibre']==12){
        $ValueResist=70;
       }else if($row['calibre']==14){
        $ValueResist=50;
       }else if($row['calibre']==16){
        $ValueResist=30;
       }else if($row['calibre']==18){
        $ValueResist=20;
       }else if($row['calibre']==20){
        $ValueResist=13;
       }else if($row['calibre']==22){
        $ValueResist=8;
       }else if($row['calibre']==24){
        $ValueResist=5;
       }
     //  if($row['fecha']==$actualDate){
    $message .= '<tr>';
    $message .= '<td>' . $row['fecha'] . '</td>';
    $message .= '<td> Matutino</td>';
    $message .= '<td>' . $row['calibre'] . '</td>';
    $message .= '<td>' . $row['Cliente'] . '</td>';
    $message .= '<td>' . $row['Num_part'] . '</td>';
    $message .= '<td>' . $ValueResist . '</td>';
    $message .= '<td>' . $row['presion'] . '</td>';
    $message .= '<td>' . $row['forma'] . '</td>';
    $message .= '<td> x </td>';
    $message .= '<td> </td>';
    $message .= '<td>' . $row['quien'] . '</td>';
    $message .= '<td>' . $row['val'] . '</td>';
    $message .= '<td>' . $row['tipo'] . '</td>';
    $message .= '</tr>';
//}
}

// Close the first database connection
mysqli_close($con);


// Set the recipient email address
$recipientEmail = 'garridofth@outlook.com';
//maleman.gtz@outlook.com
// Set the email subject and sender details
$subject = 'Daily pull test Report Email ';
$senderEmail = 'garridofth@outlook.com';
$headers = "From: $senderEmail\r\n";
$headers .= "Reply-To: $senderEmail\r\n";
$headers .= "Content-Type: text/html; charset=utf-8\r\n";

// Send the email
$mailSuccess = mail($recipientEmail, $subject, $message, $headers);

if ($mailSuccess) {
    echo 'Daily report email sent successfully.';
} else {
    echo 'Failed to send daily report email.';
}

