<?php 

require "conection.php";
 date_default_timezone_set("America/Mexico_City");
 $date=date("d-m-Y");
 $today=date("d-m-Y H:i");
$select="SELECT DISTINCT  * FROM registro ";
$qry=mysqli_query($con,$select);
while($row=mysqli_fetch_array($qry)){
    $pn=$row['NumPart'];
    $client=$row['cliente'];
    $rev=$row['rev'];
    $desc=$row['description'];
    $price=$row['price'];
    $where=$row['sento'];
if(substr($rev,0,4)=="PPAP" or substr($rev,0,4)=="PRIM"){
    $rev=substr($rev,5);
}
$buscarprice="SELECT * FROM precios WHERE pn='$pn'";
$qryprecio=mysqli_query($con,$buscarprice);
$numRow=mysqli_num_rows($qryprecio); 

if($numRow<=0){
    $inserInPrice="INSERT INTO `precios`(`id`,`client`, `pn`, `desc`, `rev`, `price`, `send`) VALUE  ('','$client','$pn','$desc','$rev','$price','$where')";
    $qryg=mysqli_query($con,$inserInPrice);     
     echo $pn."<br> ";}  
     while($rowp=mysqli_fetch_array($qryprecio)){
        $precio=$rowp['price'];
        $des=$rowp['desc'];
        $revp=$rowp['rev'];
     }
     if($price>$precio){
        $update="UPDATE precios SET price='$price' WHERE pn='$pn'";
        $sqyup=mysqli_query($con,$update);
        echo 'El num->'.$pn.'-Cambio de ->'.$precio.'/a->'.$price.'<br>';
     }
     if(($des=='0' and $desc!='0')){
        echo $pn.'--  Hay->   '.$des.' -y se va a ->  '.$desc.'<br>';
        $update="UPDATE precios SET `desc`='$desc' WHERE pn='$pn'";
        $sqyup=mysqli_query($con,$update);
}


if((substr($rev,0,4)=='PPAP' or substr($rev,0,4)=='PRIM') and (substr($revp,0,4)=='PPAP' or substr($revp,0,4)=='PRIM')  ){
    $nrev=substr($rev,5);
           $nrevp=substr($revp,5);
        if($nrev>$nrevp){
          echo $pn.'pasa de ->'.$nrevp.'--a--'.$nrev.'<br>';
          $update="UPDATE precios SET `rev`='$rev' WHERE pn='$pn'";
            $sqyup=mysqli_query($con,$update);
          }}
     if((substr($rev,0,4)=='PPAP' or substr($rev,0,4)=='PRIM') and (substr($revp,0,4)!=='PPAP' or substr($revp,0,4)!=='PRIM')  ){
            $nrev=substr($rev,5);
           
                if($nrev>$revp){
                 echo $pn.' pasa de -> ' .$revp.'--a--'.$nrev.'<br>';
                  
                 $update="UPDATE precios SET `rev`='$nrev' WHERE pn='$pn'";
                    $sqyup=mysqli_query($con,$update);
                 }
                 if((substr($rev,0,4)!=='PPAP') and (substr($rev,0,4)!=='PRIM')){
                    if((substr($revp,0,4)!=='PPAP') and (substr($revp,0,4)!=='PRIM')){
                        if($rev>$revp){
                            echo $pn.'-pasa de ->'.$revp.'-a-'.$rev.'<br>';
                                $update="UPDATE precios SET `rev`='$nrev' WHERE pn='$pn'";
                                $sqyup=mysqli_query($con,$update);
                        }  }           } }}        


$buscarTimesharn=mysqli_query($con,"SELECT * FROM timesharn INNER JOIN registro  WHERE timesharn.wo=registro.wo ");
while($rowdepu=mysqli_fetch_array($buscarTimesharn)){
    $fechain=$rowdepu["fecha"];
    if($rowdepu['cut']==NULL and ($rowdepu['count']=='2' or $rowdepu['count']=='3' )){
        $update=mysqli_query($con,"UPDATE timesharn SET cut='$fechain' WHERE wo='$rowdepu[wo]'");
    }else if($rowdepu['cutF']==NULL and ($rowdepu['count']=='2' or $rowdepu['count']=='3' )){
        $info=$rowdepu['info'];
        $buscarTiempos=mysqli_query($con,"SELECT * FROM tiempos WHERE info='$info'");
        $rowt=mysqli_fetch_assoc($buscarTiempos);
        $corte=$rowt["corte"];
        $update=mysqli_query($con,"UPDATE timesharn SET cutF='$corte' WHERE wo='$rowdepu[wo]'");
    }else if($rowdepu['term']==NULL and ($rowdepu['count']=='4' or $rowdepu['count']=='5' )){
        $info=$rowdepu['info'];
        $buscarTiempos=mysqli_query($con,"SELECT * FROM tiempos WHERE info='$info'");
        $rowt=mysqli_fetch_assoc($buscarTiempos);
        $corte=$rowt["corte"];
        $update=mysqli_query($con,"UPDATE timesharn SET ensa='$corte' WHERE wo='$rowdepu[wo]'");
    }else if($rowdepu['termF']==NULL and ($rowdepu['count']=='4' or $rowdepu['count']=='5' )){
        $info=$rowdepu['info'];
        $buscarTiempos=mysqli_query($con,"SELECT * FROM tiempos WHERE info='$info'");
        $rowt=mysqli_fetch_assoc($buscarTiempos);
        $corte=$rowt["liberacion"];
        $update=mysqli_query($con,"UPDATE timesharn SET ensa='$corte' WHERE wo='$rowdepu[wo]'");
    }else if($rowdepu['ensa']==NULL and ($rowdepu['count']=='6' or $rowdepu['count']=='7' )){
        $info=$rowdepu['info'];
        $buscarTiempos=mysqli_query($con,"SELECT * FROM tiempos WHERE info='$info'");
        $rowt=mysqli_fetch_assoc($buscarTiempos);
        $corte=$rowt["liberacion"];
        $update=mysqli_query($con,"UPDATE timesharn SET ensa='$corte' WHERE wo='$rowdepu[wo]'");
    }else if($rowdepu['ensaF']==NULL and ($rowdepu['count']=='6' or $rowdepu['count']=='7' )){
        $info=$rowdepu['info'];
        $buscarTiempos=mysqli_query($con,"SELECT * FROM tiempos WHERE info='$info'");
        $rowt=mysqli_fetch_assoc($buscarTiempos);
        $corte=$rowt["ensamble"];
        $update=mysqli_query($con,"UPDATE timesharn SET ensa='$corte' WHERE wo='$rowdepu[wo]'");
    }else if($rowdepu['loom']==NULL and ($rowdepu['count']=='8' or $rowdepu['count']=='9' )){
        $info=$rowdepu['info'];
        $buscarTiempos=mysqli_query($con,"SELECT * FROM tiempos WHERE info='$info'");
        $rowt=mysqli_fetch_assoc($buscarTiempos);
        $corte=$rowt["liberacion"];
        $update=mysqli_query($con,"UPDATE timesharn SET ensa='$corte' WHERE wo='$rowdepu[wo]'");
    }else if($rowdepu['loomF']==NULL and ($rowdepu['count']=='8' or $rowdepu['count']=='9' )){
        $info=$rowdepu['info'];
        $buscarTiempos=mysqli_query($con,"SELECT * FROM tiempos WHERE info='$info'");
        $rowt=mysqli_fetch_assoc($buscarTiempos);
        $corte=$rowt["ensamble"];
        $update=mysqli_query($con,"UPDATE timesharn SET ensa='$corte' WHERE wo='$rowdepu[wo]'");
    }
}
                


$busq="SELECT * FROM registro WHERE  count= '20' ";
$qrydepu=mysqli_query($con,$busq);
While($rowdepu=mysqli_fetch_array($qrydepu)){
    $fechain=$rowdepu["fecha"];
    $cliente=   $rowdepu["cliente"];
    $np= $rowdepu["NumPart"];
    $wo=$rowdepu['wo'];
    $sono= $rowdepu['po'];
    $codigo= $rowdepu['info'];
    $embarque=$rowdepu['tiempototal'];
    $buscarpo=mysqli_query($con,"SELECT qty FROM po WHERE po='$sono'");
    $rowpo=mysqli_fetch_assoc($buscarpo);
    $qty= $rowpo["qty"];

    $guardar="INSERT INTO `retiradad`( `cliente`, `np`, `wo`, `sono`, `qty`, `fechaing`, `fechaout`, `fecharetiro`, `codigo`) VALUES ('$cliente','$np','$wo','$sono','$qty','$fechain','$embarque','$date','$codigo')";
    $qtyguardar = mysqli_query($con, $guardar) or die(mysqli_error($con));

    $insertarnumeros=mysqli_query($con,"SELECT * FROM timesharn INNER JOIN tiempos WHERE bar='$codigo' ");
    $rowinsertar=mysqli_fetch_assoc($insertarnumeros);
    if(is_null($rowinsertar["cut"])  or $rowinsertar["cut"]==''){
        $plan=$rowinsertar["planeacion"];
        $inserta=mysqli_query($con,"UPDATE timesharn SET `cut` = '$plan' WHERE bar = '$codigo' ");
    }if(is_null($rowinsertar["cutF"]) or $rowinsertar["cutF"]==''){
        $plan=$rowinsertar["corte"];
        $inserta=mysqli_query($con,"UPDATE timesharn SET `cutF` = '$plan' WHERE bar = '$codigo' ");
    }if(is_null($rowinsertar["term"])  or $rowinsertar["term"]==''){
        $plan=$rowinsertar["corte"];
        $inserta=mysqli_query($con,"UPDATE timesharn SET `term` = '$plan' WHERE bar = '$codigo' ");
    }if(is_null($rowinsertar["termF"]) or $rowinsertar["termF"]==''){
        $plan=$rowinsertar["liberacion"];
        $inserta=mysqli_query($con,"UPDATE timesharn SET `termF` = '$plan' WHERE bar = '$codigo' ");
    }if(is_null($rowinsertar["ensa"])  or $rowinsertar["ensa"]==''){
        $plan=$rowinsertar["liberacion"];
        $inserta=mysqli_query($con,"UPDATE timesharn SET `ensa` = '$plan' WHERE bar = '$codigo' ");
    }if(is_null($rowinsertar["ensaF"]) or $rowinsertar["ensaF"]==''){
        $plan=$rowinsertar["ensamble"];
        $inserta=mysqli_query($con,"UPDATE timesharn SET `ensaF` = '$plan' WHERE bar = '$codigo' ");
    }if(is_null($rowinsertar["loom"])  or $rowinsertar["loom"]==''){
        $plan=$rowinsertar["ensamble"];
        $inserta=mysqli_query($con,"UPDATE timesharn SET `loom` = '$plan' WHERE bar = '$codigo' ");
    }if(is_null($rowinsertar["loomF"]) or $rowinsertar["loomF"]==''){
        $plan=$rowinsertar["loom"];
        $inserta=mysqli_query($con,"UPDATE timesharn SET `loomF` = '$plan' WHERE bar = '$codigo' ");
    }if(is_null($rowinsertar["qly"])  or $rowinsertar["qly"]==''){
        $plan=$rowinsertar["loom"];
        $inserta=mysqli_query($con,"UPDATE timesharn SET `qly` = '$plan' WHERE bar = '$codigo' ");
    }if(is_null($rowinsertar["qlyF"]) or $rowinsertar["qlyF"]==''){
        $plan=$rowinsertar["calidad"];
        $inserta=mysqli_query($con,"UPDATE timesharn SET `qlyF` = '$plan' WHERE bar = '$codigo' ");
    }if(is_null($rowinsertar["emba"])  or $rowinsertar["emba"]==''){
        $plan=$rowinsertar["calidad"];
        $inserta=mysqli_query($con,"UPDATE timesharn SET `emba` = '$plan' WHERE bar = '$codigo' ");
    }
   $update=mysqli_query($con,"UPDATE timesharn SET `embaF` = '$today' WHERE bar = '$codigo' "); 
    $delteregistro="DELETE FROM registro WHERE info='$codigo' ";
    $qrydelteregistro=mysqli_query($con, $delteregistro) or die(mysqli_error($con));
    $quitarDreg=mysqli_query($con,"DELETE FROM `registroparcial` WHERE `codeBar`='$codigo'");
}


    
    /*$deltepo="DELETE FROM po WHERE po='$sono' and pn='$np' and client='$cliente'";
    $qrydeltepo=mysqli_query($con, $deltepo) or die(mysqli_error($con));*/

    /*$deltetiempos="DELETE FROM tiempos WHERE info='$codigo'";
    $qrydeltetiempo=mysqli_query($con, $deltetiempos) or die(mysqli_error($con));

}

$actualpo= "SELECT DISTINCT * FROM po ";
$actualqry=mysqli_query($con, $actualpo) or die(mysqli_error($con));
while($rowdepo=mysqli_fetch_array($actualqry)){
    
    $actualpn=$rowdepo["pn"];
    $actualclient=$rowdepo["client"];
    $actualdesc=$rowdepo["description"];
    $actualprice=$rowdepo["price"];
    $actualsendto=$rowdepo["send"];
    $actualRev=$rowdepo["rev"];
   
    $preactual="SELECT * FROM precios WHERE pn='$actualpn'";
    $qryprecio=mysqli_query($con, $preactual) or die(mysqli_error($con));
   $countRows=mysqli_num_rows($qryprecio);
    if($countRows<=0){
        $inserInPrice="INSERT INTO `precios`(`id`,`client`, `pn`, `desc`, `rev`, `price`, `send`) VALUE ('','$actualclient','$actualpn','$actualdesc','$actualRev','$actualprice','$actualsendto')";
        $qryg=mysqli_query($con,$inserInPrice);      echo $actualpn." ";
    }
}*/




$sql = "SELECT pn, COUNT(pn) as cantidad_duplicados FROM precios GROUP BY pn HAVING COUNT(pn) > 1";


$resultado = mysqli_query($con, $sql);


if ($resultado) {
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $pn_duplicado = $fila['pn'];
        $cantidad_duplicados = $fila['cantidad_duplicados'];

        echo "El producto con PN '$pn_duplicado' tiene $cantidad_duplicados duplicados.<br>";
    }

    
    mysqli_free_result($resultado);
} else {
    
    echo "Error al ejecutar la consulta: " . mysqli_error($con);
}





$sql = "DELETE p1 FROM precios p1
        INNER JOIN precios p2 ON p1.pn = p2.pn AND p1.id < p2.id";


$resultado = mysqli_query($con, $sql);


if ($resultado) {
    $num_filas_afectadas = mysqli_affected_rows($con);
    echo "Se eliminaron $num_filas_afectadas duplicados más antiguos.";
} else {
    
    echo "Error al ejecutar la consulta: " . mysqli_error($con);
}



mysqli_close($con);
header("location:../reportes/reportes/index.html");

