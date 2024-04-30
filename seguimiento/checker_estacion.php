<?php
session_start();
require "../app/conection.php";
date_default_timezone_set('America/Mexico_City');
$fecha= date('d-m-Y H:i');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="checker.css">
    <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
    <title>seguimiento de arneses</title>
</head>
<body>
    
<small><button><a href="../main/principal.php" id="principal">home</a></button> </small>

    <form action="checker_estacion.php" method="post">
    <label for="code">Ingrese su codigo de barras: </label>
    <input type="text" id="code" name="code" autofocus> 
    
    </form>
    <br><br><br>
  <?php  if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $info=$_POST['code'];
    $info=str_replace("'","-",$info);
   
$sql = "SELECT * FROM registro WHERE info= '$info' ";

$result = mysqli_query($con, $sql);
    ?>
    <?php
    
    while ($row = mysqli_fetch_array($result)) {
        $rev=$row['rev'];
        $cliente=$row['cliente'];
        $Qty=$row['Qty'];
        $parte=$row['NumPart'];
        $wo=$row['wo'];
        $po=$row['po'];

$control=$row['count'];
 if($row['count']===15 and $_SESSION['user']=="ensa"){
    if(substr($rev,0,4)=='PRIM' or substr($rev,0,4)=='PPAP' ){
        $update="UPDATE `registro` SET `count`='13', `donde`='En espera de liberacion de Ingenieria cables especiales' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
        $tiempos="UPDATE `tiempos` SET `ensamble`='$fecha' WHERE `info`='$info'";
        $time=mysqli_query($con,$tiempos);    

    }else{
        if($parte=='2664-GG5A-007'){
            $update="UPDATE `registro` SET `count`='4', `donde`='En espera de liberacion' WHERE `info`='$info'";
            $up = mysqli_query($con, $update);
            $tiempos="UPDATE `tiempos` SET `corte`='$fecha' WHERE `info`='$info'";
            $time=mysqli_query($con,$tiempos);
        }else{
               $busqueda = array('2664-GG5A-007','621180','1002707335','1001233873','1001234967','1001333091','910985','910987','91304','90863','90843','90844','910966','911021','91318','60744','60745','91267','910958','91277','90833','910988','910992','90836','91315','920628','40742','90943','910956','40741','91175','91164','910980','910982','90834','910508','91194','90835','91583','910968','910350','910651','911028','91195','910886','910965','910962','910824','910887','910964','910659','40304','91222','91518','91518-1','910957','91135','910974','910577','91138','91221','910792','910978','90841','90842','910908','910910','910444','91525','910981','910967','40601','91211','91682','910621','90798','91517','91516','91681','91133','91212','91224','910975','910325','910347','910907','910909','910979','910326','910960','91137','910511','910821','910940','91139','90839','90877','91223','910400','910410','910955','90837','910953','90840','910678','910914','40199','40200','910971','910399','910969','91165','910661','40488','910972','40640','40599','910411','910913','91177','910973','40639','910954','910348','910650','911022','40602','91162','91163','910666','40600','910951','91176','910349','911024','910984','910702','910580','910784','910952','911023','910983','910970','910581','910733','910785','910976','910579','910701','910601','910611','910977','910610','910598','910786','910959','910609','910608','910961','910597','910600','910599','910536','910820','910664','910735','910512','910513','40747','910912','61522','90838','910911','91136','910390','910668','40801','60977','61615','61820','61821','90860','90886','90942','91132','91134','91143','91144','91145','91171','91173','91203','91232','91233','91278','91279','91305','91306','91307','91308','91309','91313','91314','91317','910324','920040','920041','920042','920043','910327','910439','910440','910441','910442','910443','910509','910510','910515','910516','910564','910568','910578','910585','910586','910596','910622','910637','910654','910655','910656','910657','910658','910662','910663','910665','910674','910679','910788','910832','910939','910963','910989','910990','910991','911025','911026','911027','CTT00002437','146-4448','40297A','CTT00002437','1310074567','1310781701','1310076208','1310266302','1310729900','1310265453','1310464250','1310971900','1310114620','1310081120','1310781602','1002328641','131052200','1310514400','1002577184','1002554525','1002638139','1002707335','1002553246','1001455147');
            if(in_array($parte,$busqueda)){
                $update="UPDATE `registro` SET `count`='7', `donde`='En proceso de ensamble' WHERE `info`='$info'";
                $up = mysqli_query($con, $update);
                $calidad = "INSERT INTO `calidad`(`np`, `client`, `wo`, `po`, `info`, `qty`, `parcial`) VALUES ('$parte','$cliente','$wo','$po','$info','$Qty','no')";
                $qrycalidad=mysqli_query($con,$calidad);            
            }  
               
}}
            }else if($row['count']===12 and $_SESSION['user']=="emba"){
            $count=20;
            $info=$row['info'];
        $update="UPDATE `registro` SET `count`='$count', `donde`='Embarcado' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
        
        $updateemb1="UPDATE embarque SET dateout='$fecha' WHERE info='$info'";
        $qryup=mysqli_query($con,$updateemb1); 
            
        $tiempos="UPDATE `tiempos` SET `embarque`='$fecha' WHERE `info`='$info'";
        $time=mysqli_query($con,$tiempos);

    /*else if($row['count']==11 and $_SESSION['user']=="cali"){
            $count=$row['count']+1;
            $info=$row['info'];
        $update="UPDATE `registro` SET `count`='$count', `donde`='En espera de embarque' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
        $updateemb="UPDATE embarque SET datein='$fecha' WHERE info='$info'";
        $qryup=mysqli_query($con,$updateemb); 
        $tiempos="UPDATE `tiempos` SET `calidad`='$fecha' WHERE `info`='$info'";
        $time=mysqli_query($con,$tiempos);
    }else if($row['count']==10 and $_SESSION['user']=="cali"){
            $count=$row['count']+1;
            $info=$row['info'];
        $update="UPDATE `registro` SET `count`='$count', `donde`='proceso de prueba electrica' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
        $inseremb="INSERT INTO `embarque`(`id`, `client`, `pn`, `qty`, `price`, `datein`, `dateout`, `parcial`, `info`) VALUES ('','$cliente','$parte','$Qty','','','','No','$info')";
        $qtyemb=mysqli_query($con,$inseremb); }*/ 
    } else if($row['count']===9 and $_SESSION['user']=="loom"){
            $count=$row['count']+1;
            $info=$row['info'];
           
        $update="UPDATE `registro` SET `count`='$count', `donde`='En espera de prueba electrica' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
      
        $tiempos="UPDATE `tiempos` SET `loom`='$fecha' WHERE `info`='$info'";
        $time=mysqli_query($con,$tiempos);
        }else if($row['count']===8 and  $_SESSION['user']=="loom"){
            $count=$row['count']+1;
            $info=$row['info'];
            if(substr($rev,0,4)=='PRIM' or substr($rev,0,4)=='PPAP' ){
                $update="UPDATE `registro` SET `count`='14', `donde`='En espera de liberacion de Ingenieria Loom' WHERE `info`='$info'";
                $up = mysqli_query($con, $update);
                $tiempos="UPDATE `tiempos` SET `loom`='$fecha' WHERE `info`='$info'";
                $time=mysqli_query($con,$tiempos); 
                         
            }else{
        $update="UPDATE `registro` SET `count`='$count', `donde`='Aplicación de loom' WHERE `info`='$info'";
          $up = mysqli_query($con, $update);}
        }else if($row['count']===7 and $_SESSION['user']=="ensa"){
                   $busqueda = array('2664-GG5A-007','621180','1002707335','1001233873','1001234967','1001333091','910985','910987','91304','90863','90843','90844','910966','911021','91318','60744','60745','91267','910958','91277','90833','910988','910992','90836','91315','920628','40742','90943','910956','40741','91175','91164','910980','910982','90834','910508','91194','90835','91583','910968','910350','910651','911028','91195','910886','910965','910962','910824','910887','910964','910659','40304','91222','91518','91518-1','910957','91135','910974','910577','91138','91221','910792','910978','90841','90842','910908','910910','910444','91525','910981','910967','40601','91211','91682','910621','90798','91517','91516','91681','91133','91212','91224','910975','910325','910347','910907','910909','910979','910326','910960','91137','910511','910821','910940','91139','90839','90877','91223','910400','910410','910955','90837','910953','90840','910678','910914','40199','40200','910971','910399','910969','91165','910661','40488','910972','40640','40599','910411','910913','91177','910973','40639','910954','910348','910650','911022','40602','91162','91163','910666','40600','910951','91176','910349','911024','910984','910702','910580','910784','910952','911023','910983','910970','910581','910733','910785','910976','910579','910701','910601','910611','910977','910610','910598','910786','910959','910609','910608','910961','910597','910600','910599','910536','910820','910664','910735','910512','910513','40747','910912','61522','90838','910911','91136','910390','910668','40801','60977','61615','61820','61821','90860','90886','90942','91132','91134','91143','91144','91145','91171','91173','91203','91232','91233','91278','91279','91305','91306','91307','91308','91309','91313','91314','91317','910324','920040','920041','920042','920043','910327','910439','910440','910441','910442','910443','910509','910510','910515','910516','910564','910568','910578','910585','910586','910596','910622','910637','910654','910655','910656','910657','910658','910662','910663','910665','910674','910679','910788','910832','910939','910963','910989','910990','910991','911025','911026','911027','CTT00002437','146-4448','40297A','CTT00002437','1310074567','1310781701','1310076208','1310266302','1310729900','1310265453','1310464250','1310971900','1310114620','1310081120','1310781602','1002328641','131052200','1310514400','1002577184','1002554525','1002638139','1002707335','1002553246','1001455147');
            if(in_array($parte,$busqueda)){
                $update="UPDATE `registro` SET `count`='10', `donde`='En espera de prueba electrica' WHERE `info`='$info'";
                $up = mysqli_query($con, $update);
            }else{
            $count=$row['count']+1;
            $info=$row['info'];
            $panel=array('26013301','0031539-104','0032192-70');
            if(in_array($parte,$panel)){
        $update="UPDATE `registro` SET `count`='10', `donde`='En espera de prueba electrica' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
        $tiempos="UPDATE `tiempos` SET `ensamble`='$fecha' WHERE `info`='$info'";
        $time=mysqli_query($con,$tiempos);
        $tiempos="UPDATE `tiempos` SET `loom`='$fecha' WHERE `info`='$info'";
        $time=mysqli_query($con,$tiempos);}else{
            $update="UPDATE `registro` SET `count`='$count', `donde`='En espera de loom' WHERE `info`='$info'";
            $up = mysqli_query($con, $update);
            $tiempos="UPDATE `tiempos` SET `ensamble`='$fecha' WHERE `info`='$info'";
            $time=mysqli_query($con,$tiempos);
        }
    }
        }else if($row['count']===6 and $_SESSION['user']=="ensa"){
            $count=$row['count']+1;
            $info=$row['info']; if(substr($rev,0,4)=='PRIM' or substr($rev,0,4)=='PPAP' ){
                $update="UPDATE `registro` SET `count`='13', `donde`='En de liberacion de Ingenieria Ensamble' WHERE `info`='$info'";
                $up = mysqli_query($con, $update);
                $tiempos="UPDATE `tiempos` SET `ensamble`='$fecha' WHERE `info`='$info'";
                $time=mysqli_query($con,$tiempos); 
                $calidad = "INSERT INTO `calidad`(`np`, `client`, `wo`, `po`, `info`, `qty`, `parcial`) VALUES ('$parte','$cliente','$wo','$po','$info','$Qty','no')";
                $qrycalidad=mysqli_query($con,$calidad);   
            }else{              
        $update="UPDATE `registro` SET `count`='$count', `donde`='proceso de ensamble' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
        $calidad = "INSERT INTO `calidad`(`np`, `client`, `wo`, `po`, `info`, `qty`, `parcial`) VALUES ('$parte','$cliente','$wo','$po','$info','$Qty','no')";
        $qrycalidad=mysqli_query($con,$calidad);
       }
     }else if($row['count']==5 and $_SESSION['user']=="libe"){
            $count=$row['count']+1;
            $info=$row['info'];
            if(substr($rev,0,4)=='PRIM' or substr($rev,0,4)=='PPAP' ){
                $update="UPDATE `registro` SET `count`='16', `donde`='En de liberacion de Ingenieria Liberacion' WHERE `info`='$info'";
                $up = mysqli_query($con, $update);
                $tiempos="UPDATE `tiempos` SET `liberacion`='$fecha' WHERE `info`='$info'";
                $time=mysqli_query($con,$tiempos);    
        
            }else{
        $update="UPDATE `registro` SET `count`='$count', `donde`='En espera de ensamble' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
        $tiempos="UPDATE `tiempos` SET `liberacion`='$fecha' WHERE `info`='$info'";
        $time=mysqli_query($con,$tiempos);
      }  }else if($row['count']==4 and $_SESSION['user']=="libe"){
            $count=$row['count']+1;
            $info=$row['info'];
        $update="UPDATE `registro` SET `count`='$count', `donde`='Proceso de liberacion' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
        }else if($row['count']==3 and $_SESSION['user']=="cort"){
            $count=$row['count']+1;
            $info=$row['info'];
            if(substr($rev,0,4)=='PRIM' or substr($rev,0,4)=='PPAP' ){
                $update="UPDATE `registro` SET `count`='17', `donde`='En de liberacion de Ingenieria Corte' WHERE `info`='$info'";
                $up = mysqli_query($con, $update);
                $tiempos="UPDATE `tiempos` SET `corte`='$fecha' WHERE `info`='$info'";
                $time=mysqli_query($con,$tiempos);    
        
            }else{
        $update="UPDATE `registro` SET `count`='$count', `donde`='En espera de liberacion' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
        $tiempos="UPDATE `tiempos` SET `corte`='$fecha' WHERE `info`='$info'";
        $time=mysqli_query($con,$tiempos);
       } }else    if($row['count']==2 and $_SESSION['user']=="cort"){
            $count=$row['count']+1;
            $info=$row['info'];
        $update="UPDATE `registro` SET `count`='$count', `donde`='Proceso de corte' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
        }else if($row['count']==1 and $_SESSION['user']=="Este"){
           
            $busqueda = array('2664-GG5A-007','621180','1002707335','1001233873','1001234967','1001333091','910985','910987','91304','90863','90843','90844','910966','911021','91318','60744','60745','91267','910958','91277','90833','910988','910992','90836','91315','920628','40742','90943','910956','40741','91175','91164','910980','910982','90834','910508','91194','90835','91583','910968','910350','910651','911028','91195','910886','910965','910962','910824','910887','910964','910659','40304','91222','91518','91518-1','910957','91135','910974','910577','91138','91221','910792','910978','90841','90842','910908','910910','910444','91525','910981','910967','40601','91211','91682','910621','90798','91517','91516','91681','91133','91212','91224','910975','910325','910347','910907','910909','910979','910326','910960','91137','910511','910821','910940','91139','90839','90877','91223','910400','910410','910955','90837','910953','90840','910678','910914','40199','40200','910971','910399','910969','91165','910661','40488','910972','40640','40599','910411','910913','91177','910973','40639','910954','910348','910650','911022','40602','91162','91163','910666','40600','910951','91176','910349','911024','910984','910702','910580','910784','910952','911023','910983','910970','910581','910733','910785','910976','910579','910701','910601','910611','910977','910610','910598','910786','910959','910609','910608','910961','910597','910600','910599','910536','910820','910664','910735','910512','910513','40747','910912','61522','90838','910911','91136','910390','910668','40801','60977','61615','61820','61821','90860','90886','90942','91132','91134','91143','91144','91145','91171','91173','91203','91232','91233','91278','91279','91305','91306','91307','91308','91309','91313','91314','91317','910324','920040','920041','920042','920043','910327','910439','910440','910441','910442','910443','910509','910510','910515','910516','910564','910568','910578','910585','910586','910596','910622','910637','910654','910655','910656','910657','910658','910662','910663','910665','910674','910679','910788','910832','910939','910963','910989','910990','910991','911025','911026','911027','CTT00002437','146-4448','40297A','CTT00002437','1310074567','1310781701','1310076208','1310266302','1310729900','1310265453','1310464250','1310971900','1310114620','1310081120','1310781602','1002328641','131052200','1310514400','1002577184','1002554525','1002638139','1002707335','1002553246','1001455147');
             if(in_array($parte,$busqueda)){
                $update="UPDATE `registro` SET `count`='15', `donde`='En espera de cables especiales' WHERE `info`='$info'";
                $up = mysqli_query($con, $update);
                $tiempos="UPDATE `tiempos` SET `planeacion`='$fecha' WHERE `info`='$info'";
                $time=mysqli_query($con,$tiempos);  
               

            }else{
                $panel=array('26013301','0031539-104','0032192-70','0032192-175');
                if(in_array($parte,$panel)){
                    $update="UPDATE `registro` SET `count`='6', `donde`='En espera de ensamble' WHERE `info`='$info'";
                    $up = mysqli_query($con, $update);
                    $tiempos="UPDATE `tiempos` SET `planeacion`='$fecha' WHERE `info`='$info'";
                    $time=mysqli_query($con,$tiempos);
                }else{
            $count=$row['count']+1;
            $info=$row['info'];
        $update="UPDATE `registro` SET `count`='$count', `donde`='En espera de corte' WHERE `info`='$info'";
        $up = mysqli_query($con, $update);
        $tiempos="UPDATE `tiempos` SET `planeacion`='$fecha' WHERE `info`='$info'";
        $time=mysqli_query($con,$tiempos);
        $_SESSION['imp']=$info;
        
           } }}else if($row['count']==17 and  $_SESSION['user']=="cort"){
              
                header("location:../ing/chequeo.php?info=$info&count=17");                   
            }else if($row['count']==14 and  $_SESSION['user']=="loom"){
                header("location:../ing/chequeo.php?info=$info&count=14"); 
                
            }else if($row['count']==13 and  $_SESSION['user']=="ensa"){
                header("location:../ing/chequeo.php?info=$info&count=13");     
            }else if($row['count']==16 and  $_SESSION['user']=="libe"){
                header("location:../ing/chequeo.php?info=$info&count=16");      
            }else if($row['count']==18 and  $_SESSION['user']=="cali"){
                header("location:../ing/chequeo.php?info=$info&count=18"); 
            }
        $img=$row['Barcode'];
    
      
    





    }?>

    <br><br><br>
    <div>
        <h1>Ultimo registro</h1>
        <table id="table">
            <thead>
                <th><h2>Cliente</h2></th>
                <th><h2>Número de parte</h2></th>
                <th><h2>Work order</h2></th>
                <th><h2>PO</h2></th>
                <th><h2>Cantidad</h2></th>
                <th><h2>Proceso</h2></th>
            </thead>
          <h2> <tbody>
                <tr>
                    <?php 
                    $searchporcess="SELECT * FROM registro WHERE info='$info'";
                    $qryprocess=mysqli_query($con,$searchporcess);
                    while($process=mysqli_fetch_array($qryprocess)){
                        $np=$process['NumPart'];
                        $wo=$process['wo'];
                        $po=$process['po'];
                        $qtyp=$process['Qty'];
                        $donde=$process['donde'];
                        $client=$process['cliente']
                        ?>
                        <td><h2><?php echo $client;?></h2></td>
                        <td><h2><?php echo $np;?></h2></td>
                        <td><h2><?php echo $wo;?></h2></td>
                        <td><h2><?php echo $po;?></h2></td>
                        <td><h2><?php echo $qtyp;?></h2></td>
                        <td><h2><?php echo $donde;?></h2></td>
                        
                        <?php
                    }
                    
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
    <footer>    <style>
        footer{            background-color: lightslategray;
            color: aliceblue;
            height: 20px;  
            align-items: center;
           width: 100%;                                }
            p{                font: bold italic            } 
            
    </style>    <p>Created by Jorge Garrido</p></footer>
</body>
</html>
    
    <?php
    mysqli_close($con);
        }
    ?>
<script>
if(prom=== '3'){
    return true;
}else false;

</script>