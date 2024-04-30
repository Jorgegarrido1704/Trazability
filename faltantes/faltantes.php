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
    <title>Faltantes</title>
</head>
<body>
<div><small><button id="Home"><a href="../main/principal.php" id="principal">Home</a></button></small></div>
<div class="scan"><h1>Registro de faltantes</h1>
    <h2>Para registrar un Faltante escane su codigo de barras </h2>
    <form action="faltantes.php" method="POST">
    <input type="text" name="codigo" id="codigo"></form>    </div>  

     <?php 
    if($codigo){
        
        $serch="SELECT * FROM faltantes WHERE info='$codigo'";
        $qrycodigo=mysqli_query($con,$serch);
        $rows=mysqli_num_rows($qrycodigo);
        if($rows>0){
       ?>
          <div class="table">
                <h1>YA REGISTRADOS</h1>
            <Table>
        <Thead>
            <th><h2>Numero de parte</h2></th>
            <th><h2>Item</h2></th>
            <th><h2>Cantidad </h2></th>
            
        </Thead>
                    <tbody>
                    <?php
            while($ser=mysqli_fetch_array($qrycodigo)){
                $numero=$ser['partNum'];
                $it=$ser['item'];
                $cuantos=$ser['qty'];
            ?>
      
      
    <tr>
            <td><h3> <?php echo $numero ;?></h3></td>
            <td><h3><?php echo $it ;?></h3></td>
            <td><h3><?php echo $cuantos;?></h3></td>
             </tr>
        
     <?php
        }}
    ?>



             <div class="table">
            <Table>
        <Thead>
            <th><h2>Numero de parte</h2></th>
            <th><h2>Item</h2></th>
            <th><h2>Cantidad requerida</h2></th>
            <th><h2>Faltanes</h2></th>
        </Thead>
                    <tbody>
    <form action="registrof.php" method="POST">
      <?php
        $buscar="SELECT NumPart,Qty FROM registro WHERE info='$codigo'";
        $qrybus=mysqli_query($con,$buscar);
        while($rowbs=mysqli_fetch_array($qrybus)){
            $np=$rowbs['NumPart'];
            $cantidad=$rowbs['Qty'];
        }
        $selct="SELECT * FROM datos WHERE part_num LIKE '$np'";
        $qrydatos=mysqli_query($con,$selct);
        while($tabla= mysqli_fetch_array($qrydatos)){
            $item=$tabla['item'];
            $qty=$tabla['qty'];
            $qtyt=$cantidad*$qty;
                ?>
    <tr>
            <td><h3> <input type="hidden" name="np" class="np" value="<?php echo $np; ?>"><?php echo $np ;?></h3></td>
            <td><h3><input type="hidden" name="item[]" class="item" value="<?php echo $item; ?>"><?php echo $item ;?></h3></td>
            <td><h3><?php echo $qtyt;?></h3></td>
             <td><h3><input name="faltan[]" class="faltan" type="number" value="0"  style="width: 50px;"> <input type="hidden" name="info" id="info" value="<?php echo $codigo; ?>"> <input type="hidden" name="id" id="id" value="<?php echo $id; ?>"></h3></td>
             </tr>
        
     <?php
        $id=$id+1;}
    ?>
</tbody>
</Table>
<input type="submit" name="falt" id="falt" value="Guardar">
</form>
    </div>
    <?php } ?>
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





