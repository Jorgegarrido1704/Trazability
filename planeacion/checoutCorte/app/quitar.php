<?php

require 'conection.php'; 

try{
  $codigo=isset($_GET['codigo']) ? $_GET['codigo'] : null;
  
 $data=[];
  $changeEstatus="UPDATE corte SET cutStatus='Cortado' WHERE codigo='$codigo'  '";
  $result=mysqli_query($con,$changeEstatus);
  $buscarWo=mysqli_query($con,"SELECT wo FROM corte WHERE codigo='$codigo' limit 1");
  $wo=mysqli_fetch_row($buscarWo)[0];parc
  $buscar=mysqli_query($con,"SELECT * FROM corte WHERE wo='$wo' AND cutStatus='Activo'");
  if(mysqli_num_rows($buscar==0)){
      $update=mysqli_query($con,"UPDATE registro SET count='4', donde='En espera de liberacion' WHERE wo='$wo' ");
      $updateRecords= mysqli_query($con,"UPDATE registroparcial SET libePar=libePar+cortPar, cortPar='0' WHERE wo='$wo' ");
  }
    $dat['status'] = "success";
    echo json_encode($dat);
 
  }catch(Exception $e){
    $dat['status'] = "error";
    echo json_encode($dat);
  }
  