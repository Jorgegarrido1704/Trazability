<?php
session_start();
require "../app/conection.php";
if (!isset($_SESSION['usuario'])) {
    header("location:../main/index.html");
    exit(); 
}
date_default_timezone_set('America/Mexico_City');
$fecha = ""; 
$np = "";    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
    <link rel="stylesheet" href="tiempos.css">
    <title>Tiempos</title>
</head>
<body>
    <small><button><a href="../main/principal.php" id="principal">Home</a></button></small>
    <br><br><br>
    
    <?php 
        $updateqty = "SELECT * FROM registro ";
        $conection = mysqli_query($con, $updateqty);

        if ($conection && mysqli_num_rows($conection) > 0) {
            $row = mysqli_fetch_assoc($conection);
            $np = $row['NumPart'];
            $fecha = $row['fecha'];
            $info = $row['info'];
            $cliente = $row['cliente'];
            $po = $row['po'];
            $wo = $row['wo'];
        }

        
        
    ?>
   
        <table>
            <thead>
                <th>Cliente</th>
                <th>Numero de parte</th>
                <th>WO</th>
                <th>Corte</th>
                <th>Liberacion</th>
                <th>Ensamble</th>
                <th>Loom</th>
                <th>Calidad</th>
                <th>Embarque</th>
                
            </thead>
            <tbody>
                <?php
         $updateqty = "SELECT * FROM registro order by cliente ";
         $conection = mysqli_query($con, $updateqty);
 
         while($row=mysqli_fetch_array($conection)){
             $np = $row['NumPart'];
             $fecha = $row['fecha'];
             $info = $row['info'];
             $cliente = $row['cliente'];
             $po = $row['po'];
             $wo = $row['wo'];
         
                    $fecha = strtotime($fecha);
                 $time = "SELECT * FROM tiempos WHERE info Like '$info' ";
                 $qry7 = mysqli_query($con, $time);
 
                 while ($row = mysqli_fetch_array($qry7)) {
                     $finalkits=strtotime($row['kitsfinal']);
                      $planeacion = strtotime($row['planeacion']);
                      $lib = strtotime($row['liberacion']); 
                      $corte = strtotime($row['corte']);
                      $loom = strtotime($row['loom']);
                      $ensamble = strtotime($row['ensamble']); 
                      $embarque = strtotime($row['embarque']);
                        $calidad = strtotime($row['calidad']);
                        $inicialkits = strtotime($row['kitsinicial']);
                        echo '<tr><td>'.$cliente.'</td><td>'.$np.'</td>'.'<td>'.$wo.'</td><td>';
                     if($corte){ $intercort = abs($planeacion - $corte);
                     $hcut = floor($intercort / 3600);
                     $mcut = floor(($intercort % 3600) / 60);
                     echo $hcut.' horas '.$mcut.' minutos';}else{ echo "Corte no iniciado"; }echo "</td><td>";
                     if($lib){ $interlib = abs($corte - $lib);
                     $hlib = floor($interlib / 3600);
                     $mlib = floor(($interlib % 3600) / 60);
                      echo $hlib.' horas '.$mlib.' minutos'; }else{echo "Liberacion no iniciada";  }echo "</td><td>";
                     if($ensamble){ $interens = abs($lib - $ensamble);
                     $hens = floor($interens / 3600);
                     $mens = floor(($interens % 3600) / 60);
                    echo $hens.' horas '.$mens.' minutos'; }else{  echo "Ensamble no inciado"; } echo "</td><td>";
                     if($loom){ $interloom = abs($ensamble - $loom);
                     $hloom = floor($interloom / 3600);
                     $mloom = floor(($interloom % 3600) / 60);
                 echo $hloom.' horas '.$mloom.' minutos';}else{echo "Loom no iniciado";}echo "</td><td>";
                 if($calidad){ $intercal = abs($loom - $calidad);
                     $hcal = floor($intercal / 3600);
                     $mcal = floor(($intercal % 3600) / 60);
              echo $hcal.' horas '.$mcal.' minutos'; }else{echo "Prueba electrica no iniciada";}echo "</td><td>";
                     if($embarque){
              $interemb = abs($calidad - $embarque);
                     $hemb = floor($interemb / 3600);
                     $memb = floor(($interemb % 3600) / 60);
                      echo $hemb.' horas '.$memb.' minutos';}else{echo" Item no embarcado aun";}echo "</td></tr>";}?>
                     <?php }?>
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
