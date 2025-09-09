<?php
 require "../app/conection.php";
        $count=isset($_GET['count'])?$_GET['count']:"";
    
    $registro=isset($_GET['registro'])?$_GET['registro']:"";
    if(strpos($registro,',')){
        $registro=explode(',',$registro);
    }else{
        $registro=[$registro];
    }

    //$registro=['011680','011758','011155','011387','011627','011628','011745','010998','011607','011604','011598','011599','011011','011564','011213','011067','010580','010562'];
 foreach($registro as $reg){
    $cambiarRegistro=mysqli_query($con,"UPDATE registro SET count=$count WHERE wo='$reg'");
    
    
    }
       header("Location: ../corte/busqueda.php"); 
 
  
?>