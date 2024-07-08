<?php
require "conection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla</title>
</head>
<body>
    <small><button ><a href="multiparo.php">Home</a></button></small>

    <table>
        <thead>
            <th>Fecha y hora</th>
            <th> Equipo</th>
            <th>Nombre del equipo</th>
            <th>Daño</th>
            <th>Quien reporta</th>
            <th>Area</th>
            <th>Quien atiendio</th>
            <th>Tiempo de respuesta</th>
            <th>Tiempo de mantenimiento</th>
            <th>Tiempo total</th>
        </thead>
            <tbody>
                <?php
                date_default_timezone_set("America/Mexico_City");
                                $buscar="SELECT * FROM registro_paro";
                    $qry=mysqli_query($con,$buscar);
                    while($row=mysqli_fetch_array($qry)){
                        $id=$row['id'];
                        $fecha= $row['fecha'];
                        $equipo=$row['equipo'];
                        $Nomequipo=$row['nombreEquipo'];
                        $dano=$row['dano'];
                        $quien=$row['quien'];
                        $area=$row['area'];
                        $atiende=$row['atiende'];
                        $inman=$row['inimant'];
                        $fin=$row['finhora'];
                        
              
                ?>
                <tr>
                      <td><?php echo $fecha; ?></td>
                      <td><?php echo $equipo ; ?></td>
                      <td><?php echo $Nomequipo ; ?></td>
                      <td><?php echo $dano ; ?></td>
                      <td><?php echo $quien ; ?></td>
                      <td><?php echo $area ; ?></td>
                      <td><?php echo $atiende ; ?></td>
                      <?php  $fecha=strtotime($fecha);
                        $inman=strtotime($inman);
                        $fin=strtotime($fin);
                        if($inman){
                      $tresp = abs($fecha - $inman);
                    $hres = floor($tresp / 3600);
                    $mres = floor(($tresp % 3600) / 60);
                     echo "<td>".$hres ."hrs". $mres. "min </td>"; }else{ echo "<td>En espera de proceso</td>";}
                     if($fin){
                    $tman = abs($inman - $fin);
                    $hman = floor($tman / 3600);
                    $mman = floor(($tman % 3600) / 60);
                    $ttotal = abs($fecha - $fin);
                    $htt = floor($ttotal / 3600);
                    $mtt = floor(($ttotal % 3600) / 60);
                      
                      echo "<td>".$hman. " hrs". $mman. "min</td>" ; 
                      echo "<td>".$htt. "hrs". $mtt. "min</td>" ;                    
                 }else {
                    echo "<td>No finalizado</td>";
                    echo "<td>No finalizado</td>";
                 }  }?>
            </tbody>
    </table>
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