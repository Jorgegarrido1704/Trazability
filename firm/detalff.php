<?php 
require "app.php";
$id=isset($_POST['folio'])?$_POST['folio']:"";
if($id==""){
    header("location:ff.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="firmas.css">
    <title>CVTS By Jorge Garrido</title>
</head>
<body>
    <div ><small><a href="../main/principal.php"><button>Home</button></a></small></div>
    <div align="center" class="links"><a href="ff.php"><button>Regresar</button></a>  
        

     
<div align="center"><h1>Detalles el Requerimiento </h1></div>
 <div>
    <table>
        <thead>
            <th><h2>Folio </h2></th>
            <th><h2>Fecha </h2></th>
            <th><h2>Solicitado por </h2></th>
            <th><h2>Area </h2></th>
            <th><h2>Descripción o Link</h2></th>
            <th><h2>Observación o Detalles</h2></th>
            <th><h2>Cantidad</h2></th>
            <th><h2>Unidad </h2></th>
            <th><h2>Notas </h2></th>
            
    
        <tbody>
            <?php 
            $buscar="SELECT  * FROM reqmat WHERE folio='$id' ORDER BY id DESC ";
            $sqli=mysqli_query($con,$buscar);
            while($row=mysqli_fetch_array($sqli)){
            ?>
            <tr>
                <td><h4><?php echo $row['folio']; ?></h4></td>
                <td><h4><?php echo $row['fecha']; ?></h4></td>
                <td><h4><?php echo $row['who']; ?></h4></td>
                <td><h4><?php echo $row['donde']; ?></h4></td>
                <td><h4><?php echo $row['descripcion']; ?></h4></td>
                <td><h4><?php echo $row['observacion']; ?></h4></td>
                <td><h4><?php echo $row['cantidad']; ?></h4></td>
                <td><h4><?php echo $row['unidad']; ?></h4></td>
                <td><h4><?php echo $row['notas']; ?></h4></td>
             
            <?php } ?>
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