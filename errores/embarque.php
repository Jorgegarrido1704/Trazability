<?php
 require "../app/conection.php";
    if(isset($_POST['registro'])){
        $count=isset($_POST['count'])?$_POST['count']:"";
    
    $registro=isset($_POST['registro'])?$_POST['registro']:"";
    if(strpos($registro,',')){
        $registro=explode(',',$registro);
    }else{
        $registro=[$registro];
    }

    //$registro=['011680','011758','011155','011387','011627','011628','011745','010998','011607','011604','011598','011599','011011','011564','011213','011067','010580','010562'];
 foreach($registro as $reg){
    $cambiarRegistro=mysqli_query($con,"UPDATE registro SET count=$count WHERE wo='$reg'");
    if($cambiarRegistro){
        echo "Registro $reg cambiado correctamente al contador $count<br>";}}
 
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
        <label for="registro">WO(s)</label>    
        <input type="text" name="registro">
        <label for="count">count</label>
        <input type="number" name="count" value="20">
            <input type="submit">
        </form>
    </body>
    </html>
    <?php
}
?>