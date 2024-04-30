<?php 
require "../app/conection.php";
session_start();
if (!isset($_SESSION['usuario'])) {
    header("location:../main/index.html");
    exit(); 
}
 $id=0;
if($_SESSION['user']=="Boss" or $_SESSION['admin']=="Admin"){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="audi.css">
    <title>Auditoria</title>
</head>
<body>
<small ><button><a href="../main/principal.php">Home</a></button></small>     
             <div class="title"><h1>Registro de Auditorias</h1></div>
                <div><form action="auditoria.php" method="POST">
                    <label for="area">Buscar area</label>
                    <select name="area" id="area">
                        <option value=" "> </option>
                        <option value="corteSaul">Corte Saul</option>
                        <option value="corteBrando">Corte Brando</option>
                        <option value="corteJesus">Corte Jesus</option>
                        <option value="corteDavid">Corte David</option>
                        <option value="corteAlejandra">Corte Alejandra</option>
                        <option value="corteJavier">Corte Javier</option>
                       
                        <option value="liberacíon">Liberacíon</option>
                        <option value="ensambleSaul">Ensamble Saul</option>
                        <option value="ensambleBrando">Ensamble Brando</option>
                        <option value="ensambleJesus">Ensamble Jesus</option>
                        <option value="ensambleDavid">Ensamble David</option>
                        <option value="ensambleAlejandra">Ensamble Alejandra</option>
                        <option value="ensambleJavier">Ensamble Javier</option>
                        <option value="cables especiales">Cables especiales</option>
                        <option value="loom">Loom</option>
                        
                        <option value="prueba electrica">Prueba Electrica</option>
                        <option value="embarque">Embarque</option>
                    </select>
                    <br>
                    <input type="submit" id="enviar" name="enviar" value="Buscar">
</form></div>
<div>

<?php
$area=isset($_POST["area"])?$_POST["area"]:"";

if($area=="ensambleSaul"){
    $area=substr($area,0,8);$busqueda = "SELECT * FROM registro WHERE donde LIKE '%%$area' And (cliente Like 'COLLINS%' or cliente Like 'SPARTAN%' or  cliente Like 'TICO%' or cliente Like 'PLASTIC OMNIUM%%' or cliente Like 'PROTERRA'  ) ORDER BY cliente";
    $result = mysqli_query($con, $busqueda);
}else if($area=="ensambleBrando"){
    $area=substr($area,0,8);    $busqueda = "SELECT * FROM registro WHERE donde LIKE '%%$area' And (cliente Like 'ATLAS%' or cliente Like 'UTILIMASTER%'  ) ORDER BY cliente";$result = mysqli_query($con, $busqueda);
}else if($area=="ensambleJesus"){
  $area=substr($area,0,8);  $busqueda = "SELECT * FROM registro WHERE donde LIKE '%%$area' And (cliente Like 'BERGSTROM%' or cliente Like 'BLUE BIRD%'  ) ORDER BY cliente";$result = mysqli_query($con, $busqueda);
}else if($area=="ensambleDavid"){
    $area=substr($area,0,8);    $busqueda = "SELECT * FROM registro WHERE donde LIKE '%%$area' And (cliente Like 'EL DORADO%%'   ) ORDER BY cliente";$result = mysqli_query($con, $busqueda);
}elseif($area=="ensambleAlejandra"){
    $area=substr($area,0,8);$busqueda = "SELECT * FROM registro WHERE donde LIKE '%%$area' And (cliente Like 'KALMAR%' or cliente Like 'NILFISK%' or  cliente Like 'MODINE%'  ) ORDER BY cliente";$result = mysqli_query($con, $busqueda);
}else if($area=="ensambleJavier"){
    $area=substr($area,0,8);$busqueda = "SELECT * FROM registro WHERE donde LIKE '%%$area' And (cliente Like 'FOREST%' or cliente Like 'ZOL%%%') ORDER BY cliente";$result = mysqli_query($con, $busqueda);
}else if($area=="corteSaul"){
    $area=substr($area,0,5);$busqueda = "SELECT * FROM registro WHERE donde LIKE '%%$area' And (cliente Like 'COLLINS%' or cliente Like 'SPARTAN%' or  cliente Like 'TICO%' or cliente Like 'PLASTIC OMNIUM%%' or cliente Like 'PROTERRA'  ) ORDER BY cliente";$result = mysqli_query($con, $busqueda);
}else if($area=="corteBrando"){
    $area=substr($area,0,5);    $busqueda = "SELECT * FROM registro WHERE donde LIKE '%%$area' And (cliente Like 'ATLAS%' or cliente Like 'UTILIMASTER%'  ) ORDER BY cliente";$result = mysqli_query($con, $busqueda);
}else if($area=="corteJesus"){
    $area=substr($area,0,5);$busqueda = "SELECT * FROM registro WHERE donde LIKE '%%$area' And (cliente Like 'BERGSTROM%' or cliente Like 'BLUE BIRD%'  ) ORDER BY cliente";$result = mysqli_query($con, $busqueda);
}else if($area=="corteDavid"){
    $area=substr($area,0,5);$busqueda = "SELECT * FROM registro WHERE donde LIKE '%%$area' And (cliente Like 'EL DORADO%%'   ) ORDER BY cliente";$result = mysqli_query($con, $busqueda);
}elseif($area=="corteAlejandra"){
    $area=substr($area,0,5);$busqueda = "SELECT * FROM registro WHERE donde LIKE '%%$area' And (cliente Like 'KALMAR%' or cliente Like 'NILFISK%' or  cliente Like 'MODINE%'  ) ORDER BY cliente";$result = mysqli_query($con, $busqueda);
}else if($area=="prueba electrica"){
$busqueda = "SELECT * FROM registro WHERE count='10' or count='11'";$result = mysqli_query($con, $busqueda);
}else if($area=="embarque"){
    $busqueda = "SELECT * FROM registro WHERE count='12' ";$result = mysqli_query($con, $busqueda);
    }else if($area=="cables especiales"){
        $busqueda = "SELECT * FROM registro WHERE count='15' ";$result = mysqli_query($con, $busqueda);
    }else if($area=="loom"){
        $busqueda = "SELECT * FROM registro WHERE count='8' or count='9' ";$result = mysqli_query($con, $busqueda);
    }else{  $busqueda = "SELECT * FROM registro"; $result = mysqli_query($con, $busqueda);}



$numero=mysqli_num_rows($result);


if($numero> 0){
    echo $numero;
?>
<br><br><br>

<form action="regaudi.php" method="POST"> 
<table>
    <thead>
    <th>Número de parte</th>
    <th>Cliente</th>
    <th>WO</th>
    <th>QTY</th>
    <th>Proceso</th>
    <th>Fecha en que ingreso</th>
    <th>Status</th>
    </thead>
    <tbody>
      
<?php
 while($row=mysqli_fetch_array($result)){
    $cliente=$row['cliente'];
    $np=$row['NumPart'];
    $wo=$row['wo'];
    $qty=$row['Qty'];
    $donde=$row['donde'];
    $fecha=$row['fecha'];
    ?>
    <tr>
    <td><input type="hidden" name="np[]" id="np" value="<?php echo $np ;?>"><?php echo $np ;?></td>
    <td><input type="hidden" name="cliente[]" id="cliente" value="<?php echo $cliente ;?>"><?php echo $cliente ;?></td>
    <td><input type="hidden" name="wo[]" id="wo" value="<?php echo $wo ;?>"><?php echo $wo ;?></td>
    <td><input type="hidden" name="qty[]" id="qty" value="<?php echo $qty ;?>"><?php echo $qty ;?></td>
    <td><input type="hidden" name="donde[]" id="donde" value="<?php echo $donde ;?>"><?php echo $donde ;?><input type="hidden" name="id" id="id" value="<?php echo $id;?>"></td>
    <td><input type="hidden" name="fecha[]" id="fecha" value="<?php echo $fecha ;?>"><?php echo $fecha ;?></td>
    <td><select name="status[]" id="status">
        <option value="">  </option>
        <option value="OK">Ok</option>
        <option value="Search">Search</option>
        <option value="cancelled">cancel</option>

    </select>    </td></tr>
    <?php
    $id=$id+1;
}

?>
    </tbody>

</table>
 <input type="submit" name="guardar " id="guardar " value="Registrar">
</form>
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
<?php }}else{
    header("location:../main/principal.php");
}?>