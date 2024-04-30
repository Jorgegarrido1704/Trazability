<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
date_default_timezone_set("America/Mexico_City");
$date=date("d-m-Y"); 
function backUpDatasbaseTables($dhHost, $dhUser, $dhPassword, $dbname, $tables='*') {
    $db = new mysqli($dhHost, $dhUser, $dhPassword, $dbname);

    if ($tables == '*') {
        $tables = array();
        $result = $db->query('SHOW TABLES');
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : array($tables);
    }

    $return = '';

    foreach ($tables as $table) {
        $result = $db->query("SELECT * FROM $table");
        $numColumns = $result->field_count;
        //$return .= "DROP TABLE $table;";

        for ($i = 0; $i < $numColumns; $i++) {
            while ($row = $result->fetch_assoc()) {
                $return .= "INSERT INTO $table VALUES (";

                foreach ($row as $column => $value) {
                    $value = addslashes($value);
                    $value = str_replace("\n", "\\n", $value);

                    if (isset($value)) {
                        $return .= '"' . $value . '",';
                    } else {
                        $return .= '"",';
                    }

                    if ($column < ($numColumns - 1)) {
                        $return .= ',';
                    }
                }

                $return .= ");\n";
            }
        }

        $return .= "\n\n";
    }

    // Save file
 
    $handle = fopen( date("d-m-Y") . 'sys.sql', 'w+');
    fwrite($handle, $return);
    fclose($handle);
}

backUpDatasbaseTables('localhost', 'root', '', 'trazabilidad');

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
    
    
    $attachmentPath = 'C:/xampp/htdocs/system/app/'.$date.'sys.sql';  
    $mail->addAttachment($attachmentPath);

    
    $mail->send();
    echo 'Email sent successfully.';
} catch (Exception $e) {
    echo 'Error sending email: ', $mail->ErrorInfo;
}
header("location:../../ing/app/dbresp.php");
