<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("location:../main/index.html");
    exit(); 

}

require "../app/conection.php";
$sum=0;
$page = $_SERVER['PHP_SELF'];
$sec = "900";
$client=isset($_POST['client'])?$_POST['client']:"";
$area=isset($_POST['area'])?$_POST['area']:"";
$paros=isset($_POST['paro'])?$_POST['paro']:"";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="<?php echo $sec ?>;URL='<?php echo $page ?>'">
    <link rel="stylesheet" href="seguimiento.css">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
    <title>Ordenes Creadas</title>
   
</head>
<body>
<small><button><a href="../main/principal.php" id="principal">home</a></button> </small>
<br><br><br><br>    
<div >
    <form action="seguimiento.php" method="POST" class="Forma">
        <label for="area">Busqueda por area</label>
        <select name="area" id="area">
            <option value="">   </option>
            <option value="corte">Corte</option>
            <option value="liberacíon">Liberacíon</option>
            <option value="ensamble">Ensamble</option>
            <option value="cajas">Cajas</option>
            <option value="cables especiales">Cables especiales</option>
            <option value="loom">Loom</option>
            <option value="pruebas electricas">Prueba elctrica</option>
            <option value="embarque">Embarque</option>
        </select>
        <label for="client">Busqueda por cliente</label>
        <select name="client" id="client">
            <option value="">   </option>
            <option value="SHYFT">Shyft</option>
            <option value="EL DORADO">California</option>
            <option value="SPARTAN">Spartan</option>
            <option value="TICO">Tico</option>
            <option value="PROTERRA">Proterra</option>
            <option value="ATLAS">Atlas</option>
            <option value="UTILIMASTER">Utilimaster</option>
            <option value="PLASTIC OMNIUM">Plastic Omnium</option>
            <option value="NILFISK">Nilfisk</option>
            <option value="COLLINS">Collins</option>
            <option value="FOREST RIVER">Forest River</option>
            <option value="BERGSTROM">Bergstrom</option>
            <option value="MODINE">Modine</option>
            <option value="KALMAR">Kalmar</option>
            <option value="BLUE BIRD|">Blue Bird</option>
        </select>
        <label for="paro">Busqueda por paro</label>
        <select name="paro" id="paro">
            <option value=""> </option>
            <option value="parcial">Parcial</option>
            <option value="FaltaMaterial">Falta de Material</option>
            <option value="Mantenimiento">Mantenimiento</option>
            <option value="Ingenieria">Ingenieria</option>
        </select>
            <input type="submit" value="Enviar">
    </form>
    </div>
<?php 
    if($area and !$client){
        $sql = "SELECT * FROM registro WHEre donde LIKE '%%$area' ";
        $result = mysqli_query($con, $sql);
    } else if( $client){
        $sql = "SELECT * FROM registro WHERE cliente LIKE '$client%%'";
        $result = mysqli_query($con, $sql);
    } else if( $paros ){
        $sql = "SELECT * FROM registro  WHERE paro Like '$paros'";
        $result = mysqli_query($con, $sql);
    } else if( !$area AND !$client ){
        $sql = "SELECT * FROM registro";
        $result = mysqli_query($con, $sql);       

?>

<div align="center">
    <table style="width:100%;">
        <tr>
            <th>Fecha</th>
            <th>Número de parte</th>
            <th>Cliente</th>
            <th>Work order</th>
            <th>PO</th>
            <th>Cantidad</th>
            <th>Codigo de barras</th>
            <th>Estación</th>
            <th>Tiempo total</th>
            <th>Paro</th>
            
            
        </tr>
    <?php

        while ($row = mysqli_fetch_array($result)) {
            $info = $row['info'];
            if($row['donde']!="Embarcado"){
            date_default_timezone_set('America/Mexico_City');
           
            $num = $row['id'];
            $fecha = strtotime($row['fecha']);
                $info=$row['info'];
            $actual = date('d-m-Y H:i');
            $interval = abs($fecha - strtotime($actual));
            $hours = floor($interval / 3600);
            $minutes = floor(($interval % 3600) / 60);
            $count=$row['count'];
            $color=$row['paro'];
            if($count!=13){
            
            $tiempocomidaQuery = "UPDATE `registro` SET `tiempototal`='$hours horas $minutes minutos' WHERE `id`='$num'";
            $qry4 = mysqli_query($con, $tiempocomidaQuery);}
            if($color){
            echo '<tr align="center" height="3px" id="color" name="color" style="background-color:red;">';
            }else {
             echo   '<tr align="center" height="3px" id="color" name="color" >';
            }?>
                <td><?php echo $row['fecha']; ?></td>
                <td><?php echo $row['NumPart']; ?></td>
                <td><?php echo $row['cliente']; ?></td>
                <td><?php echo $row['wo']; ?></td>
                <td><?php echo $row['po']; ?></td>
                <td><?php echo $row['Qty']; ?></td>
               
                <td><?php echo $row['donde']; ?></td>
                <td><?php echo $row['tiempototal']; ?></td>
                              
                <td id="paro" name="paro"value="<?php echo $color?>" ><?php echo $color;?></td>
            </tr>
                    <?php
        }}}
   
        ?>
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
