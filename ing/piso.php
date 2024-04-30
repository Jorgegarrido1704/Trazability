<?php

require "../app/conection.php";
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="refresh" content="10; url=../../ing/email/index.html">
        <link rel="stylesheet" href="fallas.css">
        <title>PPAP Piso</title>
    </head>
    <style>
        body{
            width: 100%;
            background-color: azure;
        }
        table
        {
            width: 100%;
            border: 2px solid lightgray;
        }
        td{
            border: 2px solid lightgray;
            text-align: center;
            font-size: xxx-large;
        }
    </style>
    <body>
        <Table>
       <thead>
        <th>Numero de parte</th>
        <th>Cliente</th>
        <th>Rev</th>
        <th>Area Requerida</th>
        
            </thead>
            <tbody>
                <?php
                $tabla="SELECT * FROM registro WHERE count='13' or count='14' or count='16' or count='17' or count='18'";
                $qry=mysqli_query($con,$tabla);
                while($row=mysqli_fetch_array($qry)){
                  $part=$row['NumPart'];
                  $cliente=$row['cliente'];
                  $where=$row['donde'];
                  $rev=$row['rev'];

                  if(substr($rev,0,4)=='PRIM'){
                    $style='yellow';
                  }else if(substr($rev,0,4)=='PPAP'){
                    $style="lightgreen";
                  }
                    
                ?>
                <tr  style="background-color: <?php echo $style; ?>">
                    <td><?php echo $part ; ?></td>
                    <td><?php echo $cliente ; ?></td>
                    <td><?php echo $rev ; ?></td>
                    <td><?php echo $where ; ?></td>
                    


                 </td>
                </tr>

                <?php } ?>
            </tbody>

        </Table>    
        <div align="center"> <h1>Cuando termines de Validar, escanea la orden y tu codigo de ingeniero</h1></div>   
    </body>
    </html>

  