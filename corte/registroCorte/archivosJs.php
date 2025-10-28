<?php 
    require '../../app/conection.php';
    $codigo=isset($_POST['code'])?$_POST['code']:"";
    $udm=isset($_POST['udm'])?$_POST['udm']:"";
    $largo=isset($_POST['largo'])?$_POST['largo']:"";
    $largo2=isset($_POST['largo2'])?$_POST['largo2']:"";
    $origlargo=isset($_POST['origlargo'])?$_POST['origlargo']:"";
    $control=isset($_POST['control'])?$_POST['control']:"";
    $cons=isset($_POST['consec'])?$_POST['consec']:"";
    $work=isset($_POST['work'])?$_POST['work']:"";
    $diff=intval($origlargo)-intval($largo);
    $diff2=intval($origlargo)-intval($largo2);
    if($udm=="IN"){ $largo=$largo*25.4;
    $largo2=$largo2*25.4;    }
    $date=date("Y-m-d");
    $time=date("H:i:s");

    
    //insertar en la base de datos
    $datos=mysqli_query($con,"INSERT INTO `registroscorte`(`date`,`timeExe`, `wo`, `cons`, `registradoBy`, `udm`, `largoOperador`, `largolista`, `diferencia`,`largo2`, `diferencia2`) 
    VALUES ('$date','$time','$work','$cons','$control','$udm','$largo','$origlargo','$diff','$largo2','$diff2')");

    $delte=mysqli_query($con,"DELETE FROM corte WHERE codigo ='$codigo' ");
    
    
    if($datos){
        echo "Insertation succesfully";
        header("location:index.php");
    }