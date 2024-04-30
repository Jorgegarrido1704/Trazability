<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require "../app/conection.php";
$info=isset($_GET['info'])?$_GET['info']:"";
$count=isset($_GET['count'])?$_GET['count']:"";
$code=isset($_GET['codeing'])?$_GET['codeing']:"";
date_default_timezone_set("America/Mexico_City");
$bus=mysqli_query($con,"SELECT * FROM registro WHERE info='$info'");
while($row=mysqli_fetch_array($bus)){
$parte=$row['NumPart'];
$cliente=$row['cliente'];
$wo=$row['wo'];
$po=$row['po'];
$Qty=$row['Qty'];
}
$today=date("d-m-Y H:i");

if($code!="" ){
    if($code=="Sup"  or $code=="v1ct0r1023635" or $code=="c4r10510282r" or $code=="j35us10207c3rv" or $code=="n4ncy1031641d" or $code=="p40l41030751l"){
    echo "<h1>el codigo es correcto</h1>";
   
    if($count=='17'){
        $insertar=mysqli_query($con,"INSERT INTO `ppap`( `info`, `fecha`, `codigo`,`area`) VALUES ('$info','$today','$code','corte')");
    $update=mysqli_query($con,"UPDATE `registro` SET `count`='4', `donde`='En espera de liberacion' WHERE `info`='$info'");}
    else if($count=='13'){
        $busqueda = array('2664-GG5A-007','621180','1002707335','1001233873','1001234967','1001333091','910985','910987','91304','90863','90843','90844','910966','911021','91318','60744','60745','91267','910958','91277','90833','910988','910992','90836','91315','920628','40742','90943','910956','40741','91175','91164','910980','910982','90834','910508','91194','90835','91583','910968','910350','910651','911028','91195','910886','910965','910962','910824','910887','910964','910659','40304','91222','91518','91518-1','910957','91135','910974','910577','91138','91221','910792','910978','90841','90842','910908','910910','910444','91525','910981','910967','40601','91211','91682','910621','90798','91517','91516','91681','91133','91212','91224','910975','910325','910347','910907','910909','910979','910326','910960','91137','910511','910821','910940','91139','90839','90877','91223','910400','910410','910955','90837','910953','90840','910678','910914','40199','40200','910971','910399','910969','91165','910661','40488','910972','40640','40599','910411','910913','91177','910973','40639','910954','910348','910650','911022','40602','91162','91163','910666','40600','910951','91176','910349','911024','910984','910702','910580','910784','910952','911023','910983','910970','910581','910733','910785','910976','910579','910701','910601','910611','910977','910610','910598','910786','910959','910609','910608','910961','910597','910600','910599','910536','910820','910664','910735','910512','910513','40747','910912','61522','90838','910911','91136','910390','910668','40801','60977','61615','61820','61821','90860','90886','90942','91132','91134','91143','91144','91145','91171','91173','91203','91232','91233','91278','91279','91305','91306','91307','91308','91309','91313','91314','91317','910324','920040','920041','920042','920043','910327','910439','910440','910441','910442','910443','910509','910510','910515','910516','910564','910568','910578','910585','910586','910596','910622','910637','910654','910655','910656','910657','910658','910662','910663','910665','910674','910679','910788','910832','910939','910963','910989','910990','910991','911025','911026','911027','CTT00002437','146-4448','40297A','CTT00002437','1310074567','1310781701','1310076208','1310266302','1310729900','1310265453','1310464250','1310971900','1310114620','1310081120','1310781602','1002328641','131052200','1310514400','1002577184','1002554525','1002638139','1002707335','1002553246','1001455147');
             if(in_array($parte,$busqueda)){
                $update="UPDATE `registro` SET `count`='10', `donde`='En espera de prueba electrica' WHERE `info`='$info'";
                $up = mysqli_query($con, $update);
                $calidad = "INSERT INTO `calidad`(`np`, `client`, `wo`, `po`, `info`, `qty`, `parcial`) VALUES('$parte','$cliente','$wo','$po','$info','$Qty','no')";
                $qrycalidad=mysqli_query($con,$calidad);
                $insertar=mysqli_query($con,"INSERT INTO `ppap`( `info`, `fecha`, `codigo`,`area`) VALUES ('$info','$today','$code','ensamble')");
             }else{
        $calidad = "INSERT INTO `calidad`(`np`, `client`, `wo`, `po`, `info`, `qty`, `parcial`) VALUES('$parte','$cliente','$wo','$po','$info','$Qty','no')";
        $qrycalidad=mysqli_query($con,$calidad);
        $insertar=mysqli_query($con,"INSERT INTO `ppap`( `info`, `fecha`, `codigo`,`area`) VALUES ('$info','$today','$code','ensamble')");
        $update=mysqli_query($con,"UPDATE `registro` SET `count`='8', `donde`='En espera de loom' WHERE `info`='$info'");}
             }else if($count=='14'){
        $insertar=mysqli_query($con,"INSERT INTO `ppap`( `info`, `fecha`, `codigo`,`area`) VALUES ('$info','$today','$code','loom')");
       

    $update=mysqli_query($con,"UPDATE `registro` SET `count`='10', `donde`='En espera de prueba electrica' WHERE `info`='$info'");}
    else if($count=='16'){
        $insertar=mysqli_query($con,"INSERT INTO `ppap`( `info`, `fecha`, `codigo`,`area`) VALUES ('$info','$today','$code','liberacion')");
        $update=mysqli_query($con,"UPDATE `registro` SET `count`='6', `donde`='En espera de ensamble' WHERE `info`='$info'");}
        else if($count=='18'){
            $insertar=mysqli_query($con,"INSERT INTO `ppap`( `info`, `fecha`, `codigo`,`area`) VALUES ('$info','$today','$code','prueba electrica')");
            $update=mysqli_query($con,"UPDATE `registro` SET `count`='12', `donde`='En espera de embarque' WHERE `info`='$info'");
            $llamarPPAP="SELECT * FROM registro WHERE info='$info'";
        $qryemail=mysqli_query($con,$llamarPPAP);
        while ($rowemail = mysqli_fetch_array($qryemail)) {
            $rev=$rowemail['rev'];
            $emailcliente=$rowemail['cliente'];
            $emailpn=$rowemail['NumPart'];
            $emailwo=$rowemail['wo'];
            $emailpo=$rowemail['po'];
            $emailQty=$rowemail['Qty'];
        }
        $revin=substr($rev,0,4);
if($revin=="PPAP" or $revin=="PRIM" ){
$revf=substr($rev,4);
date_default_timezone_set('America/Mexico_City');
$date = date('d-m-Y');
$time=date('H:i');

$message = '<div align="center"><h1>'.$revin.' liberada en embarque </h1></div> <br><br>';
$message .= '<br><p> Buen dia, <br> Les comparto que el dia '.$date. ' A las '.$time.'<br> Salio de prueba la PPAP siguiente<br>';
$message .='<br><h2>Cliente:</h2>'.$emailcliente;
$message .='<br><h2>Numero de parte:</h2>'.$emailpn;
$message .='<br><h2>PPAP en revision:</h2>'.$revf;
$message .='<br><h2>Con Work order:</h2>'.$emailwo;
$message .='<br><h2>Con Sono order:</h2>'.$emailpo;
$message .='<br><h2>Por la cantida de:</h2>'.$emailQty;
$message .='<br><br><br>Con las siguientes anotaciones:';
$defectos="SELECT * FROM regsitrocalidad WHERE info='$info'";
$defectosqry=mysqli_query($con,$defectos);
while($rowdef=mysqli_fetch_array($defectosqry)){
$defectos=$rowdef['codigo'];
$message.='<br>'.$defectos;
}

$message .='<br><br><br><br><p>Si tienen alguna duda con gusto la resolvemos<br> ATTE: J.G.</p>';




$subject = $revin.' PRUEBA ELECTRICA  '.$emailcliente.'NP '.$emailpn.' en REV '.$revf ;

$headers = "From: jorgegarrdio@gmail.com\r\n";
$headers .= "Content-Type: text/html; charset=utf-8\r\n";
$recipientEmail = 'jcervera@mx.bergstrominc.com,vestrada@mx.bergstrominc.com,egaona@mx.bergstrominc.com, mvaladez@mx.bergstrominc.com,jolaes@mx.bergstrominc.com,lramos@mx.bergstrominc.com,emedina@mx.bergstrominc.com';

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
}}
            if($update){
                header("location:../seguimiento/checker_estacion.php");
            }else{
    echo "<h1><br><br><br><br><br><br><br> Su codigo no pertenece a ingenieria</h1>";}
}}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../seguimiento/checker.css">
    <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
    <meta http-equiv="refresh" content="10;url=../seguimiento/checker_estacion.php">
    <title>Ingenieria Check</title>
</head>
<style>
    body{
        width: 100%;
        background-color: lightskyblue;
    }
</style>
<body>
<br><br><br><br><br><br><br>
    <div align="center">
        
    <form action="chequeo.php" method="GET">
        <label for="codeing"><h1>Ingrese Su codigo de ingeniero</h1></label> <input type="text" name="codeing" id="codeing" required autofocus>
            <input type="hidden" name="info" id="info" value="<?php echo $info; ?>">
            <input type="hidden" name="count" id="count" value="<?php echo $count; ?>">
        </form>
    </div>
</body>
</html>
