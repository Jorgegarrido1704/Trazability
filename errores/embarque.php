<?php
 require "../app/conection.php";
    if(isset($_POST['registro'])){
        
    
    $registro=isset($_POST['registro'])?$_POST['registro']:"";
    if(strpos($registro,',')){
        $registro=explode(',',$registro);
    }else{
        $registro=[$registro];
    }

    //$registro=['011680','011758','011155','011387','011627','011628','011745','010998','011607','011604','011598','011599','011011','011564','011213','011067','010580','010562'];
 foreach($registro as $reg){
    $cambiarRegistro=mysqli_query($con,"UPDATE registro SET count='1' WHERE wo='$reg'");
    if($cambiarRegistro){
        echo "Registro $reg cambiado<br>";}}
 
}else{
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <form action="embarque.php" method="POST">
            <input type="text" name="registro">
            <input type="submit">
        </form>
    </body>
    </html>
    <?php
}
?>