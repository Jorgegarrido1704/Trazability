<?php
session_start();


require "../app/conection.php";
if(!$_SESSION['usuario']){
    header("location:../main/index.html");
}
$codigo=isset($_POST['codigo'])?$_POST['codigo']:"";

$id=0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="faltantes.css">
    <title>Baja de Faltantes</title>
</head>
<body>
<div><small><button id="Home"><a href="../main/principal.php" id="principal">Home</a></button></small></div>
<div class="scan"><h1>Baja  de faltantes</h1>
    <h2>Para registrar un Faltante escane su codigo de barras </h2>
    <form action="baja.php" method="POST">
    <input type="text" name="codigo" id="codigo"></form>    </div>  

     <?php 
if($codigo){
   
    $search = "SELECT * FROM faltantes WHERE info='$codigo'";
    $query = mysqli_query($con, $search);
    
   
    $uniqueItems = array();

    while($row = mysqli_fetch_array($query)){
        $numero = $row['partNum'];
        $it = $row['item'];
        $cuantos = $row['qty'];
        
       
        if(array_key_exists($it, $uniqueItems)){
            $uniqueItems[$it] += $cuantos;
        } else {
           
            $uniqueItems[$it] = $cuantos;
        }
    }
    
    if(count($uniqueItems) > 0){
       
        ?>
        <div class="table">
            <h1>Faltantes</h1>
            <table>
                <thead>
                    <th><h2>Numero de parte</h2></th>
                    <th><h2>Item</h2></th>
                    <th><h2>Faltantes </h2></th>
                    <th><h2>Menos </h2></th>
                </thead>
                <tbody>
                    <form action="bajaregistro.php" method="POST">
                        <?php
                        foreach($uniqueItems as $item => $quantity){
                            ?>
                            <tr>
                                <td><h3><?php echo $numero; ?></h3></td>
                                <td><h3><?php echo $item; ?></h3></td>
                                <td><h3><?php echo $quantity; ?></h3></td>
                                <td><h3><input name="faltan[]" class="faltan" type="number" value="0" style="width: 50px; " step="0.01">
                                    <input type="hidden" name="info" id="info" value="<?php echo $codigo; ?>">
                                    <input type="hidden" name="faltantes[]" id="faltantes" value="<?php echo $quantity; ?>">
                                    <input type="hidden" name="item[]" id="item" value="<?php echo $item; ?>">
                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>"></h3></td>
                                    <?php $id=$id+1; ?>
                                </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <input type="submit" name="falt" id="falt" value="Guardar">
            </form>
        </div>
     
        <?php
    } else {
        echo "No hay registro de faltantes";
    }
}

?>
 <footer>    <style>
        footer{            background-color: lightslategray;
            color: aliceblue;
            height: 20px;  
            align-items: center;
           width: 100%;                                }
            p{                font: bold italic            } 
            
    </style>    <p>Created by Jorge Garrido</p></footer>
</body></html>