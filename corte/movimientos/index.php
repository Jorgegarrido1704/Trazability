<?php
require "../../app/conection.php";
$date=date("d-m-Y");

if($date=="10-02-2025"){
   $registroparcial=mysqli_query($con,"SELECT * FROM `registroparcial` WHERE testPar>0 and (wo != '011504' or wo != '011488' or wo != '011497' )");
    while($row=mysqli_fetch_array($registroparcial)){
        $nuevo=0;
        $info=$row['codeBar'];
        $testPar=$row['testPar'];
        $ensaPar=$row['ensaPar'];
        $nuevo=$testPar+$ensaPar;
        $update=mysqli_query($con,"UPDATE `registroparcial` SET `testPar` = 0, `ensaPar` = '$nuevo' WHERE `codeBar` = '$info'");
        print("Se actualizo el registro con el codeBar: ".$info." con el valor de: ".$nuevo."<br>");
        if(!$update){
            print("Error al actualizar el registro con el codeBar: ".$info."<br>");
        }else if($update){
            $delte=mysqli_query($con,"DELETE FROM `calidad` WHERE `info` = '$info'");
            if(!$delte){
                print("Error al eliminar el registro con el codeBar: ".$info."<br>");
            }else if($delte){
                print("Se elimino el registro con el codeBar: ".$info."<br>");
                $updateReg=mysqli_query($con,"UPDATE `registro` SET `count` = 7, `donde` = 'En espera de ensamble' WHERE `info` = '$info'");
                if(!$updateReg){
                    print("Error al actualizar el registro con el codeBar: ".$info."<br>");
                }else if($updateReg){
                    print("Se actualizo el registro con el codeBar: ".$info."<br>");
                    $insert=mysqli_query($con,"INSERT INTO `registroparcialtiempo`(`codeBar`, `qtyPar`, `area`, `fechaReg`) VALUES ('$info','$nuevo','Limpieza CVTS calidad','$date')");
                    if(!$insert){
                        print("Error al insertar el registro con el codeBar: ".$info."<br>");
                    }else if($insert){
                        print("Se inserto el registro con el codeBar: ".$info."<br>");
                    }
                }
            }
        }
        print("<br><br>");
    }

   
}