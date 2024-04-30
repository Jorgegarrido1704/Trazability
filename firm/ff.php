<?php 
require "app.php";
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
    <div align="center" class="links"><a href="index.html"><button>Registrar Requerimiento</button></a>  
        

     
<div align="center"><h1>Requerimiento pendiente</h1></div>
 <div>
    <table>
        <thead>
            <th><h2>Folio </h2></th>
            <th><h2>Fecha </h2></th>
            <th><h2>Solicitado por </h2></th>
            <th><h2>Area </h2></th>
            <th><h2>Estatus</h2></th>
            <th><h2>Detalles</h2></th>
          
    
        <tbody>
            <?php 
            $buscar="SELECT DISTINCT folio,fecha,who,donde,firmaComp,aprovada,negada FROM reqmat WHERE folio>0 ORDER BY id DESC ";
            $sqli=mysqli_query($con,$buscar);
            while($row=mysqli_fetch_array($sqli)){
                $firm=$row['firmaComp'];
                $ap=$row['aprovada'];
                $nel=$row['negada'];
            ?>
            <tr>
                <td><h4><?php echo $row['folio']; ?></h4></td>
                <td><h4><?php echo $row['fecha']; ?></h4></td>
                <td><h4><?php echo $row['who']; ?></h4></td>
                <td><h4><?php echo $row['donde']; ?></h4></td>
                <td><h4><?php 
                if($firm!="" and $ap!="" ){
                    echo "Aprovada por compras/ pendinte aprovacion Gerencial";
                }else if($firm=="" and $ap==""){
                    echo "Pendiente por aprobar";
                }else if($firm!="" and $nel!=""){
                    echo "Negada / ".$nel;
                }
                
                ?></h4></td>
                <td><h4><form action="detalff.php" method="POST">
                    <input type="hidden" name="folio" id="folio" value="<?php echo $row['folio']; ?>">
                    <input type="submit" name="enviar" id="enviar" value="Detalles">
                </form></h4></td>   
             
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