<?php

require "conection.php";
?>
    <!DOCTYPE html>
  |  <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="refresh" content="40">
        <link rel="stylesheet" href="fallas.css">
        <title>Fallas</title>
    </head>
    <body>
        <Table>
            <thead>
        <th>Fecha de solicitud</th>
        <th>Area</th>
        <th>Equipo</th>
        <th>Daño</th>
        <th>Quien solicita</th>
        <th>Quien atiende</th>
        <th>Iniciar mantenimineto</th>
            </thead>
            <tbody>
                <?php
                $tabla="SELECT * FROM registro_paro_corte";
                $qry=mysqli_query($con,$tabla);
                while($row=mysqli_fetch_array($qry)){
                    $idmant=$row['id'];
                    $fmant=$row['fecha'];
                    $equimant=$row['nombreEquipo'];
                    $damant=$row['dano'];
                    $quienmant=$row['quien'];
                    $areamant=$row['area'];
                    $atiendemant=$row['atiende'];
                    $style = ($atiendemant == "Nadie aun") ? "background-color: red;" : "background-color: orange;";
                ?>
                <tr  style="<?php  echo $style;?>">
                    <td><?php echo $fmant ; ?></td>
                    <td><?php echo $areamant ; ?></td>
                    <td><?php echo $equimant ; ?></td>
                    <td><?php echo $damant ; ?></td>
                    <td><?php echo $quienmant ; ?></td>
                    <td><?php echo $atiendemant ; ?></td>
                    <td><form action="registo_mantenimiento.php" method="POST" >
    <input type="hidden" name="id[]" value="<?php echo $idmant; ?>">
    <input type="text" name="quienInput" id="quienInput" >

</form>
                    </td>
                </tr>

                <?php } ?>
            </tbody>

        </Table>
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

    <script>
function Validation(id) {
    var quien = prompt("¿Quién va a realizar el mantenimiento?");
    if (quien !== null) {
        
        document.getElementsByName("quienInput[]")[id - 1].value = quien;
      
        return true; 
    } else {
        alert("No se registró a nadie para el mantenimiento.");
        return false; 
    }
}

    </script>