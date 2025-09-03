<?php 
session_start();
require "../app/conection.php";
$month=date("m-Y");
$lasrMonth=date("m-Y",strtotime("-1 month"));
$last2Month=date("m-Y",strtotime("-2 month"));

$pn=isset($_POST['pn'])?$_POST['pn']:"";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla Prueba pull</title>
</head>
<body>
    <style>
        body{
            width: 100%;
            background-color: lightgrey;
        }

        table{
            width: 100%;
            text-align: center;
        }
        th{
            border-bottom: solid yellowgreen 5px;
        }
        tr:hover{
            background-color: yellowgreen;
            color: whitesmoke;
        }
    </style>
    <div><small><!--<a href="registro.php"><button>Home</button></a></small></div>-->

    <div align="center">
    <a href="graficas_p/index.html"><button>Grafica de puntos</button></a>
        <a href="graficas/index.html"><button>Grafica de Gauss</button></a>  
    
    <form action="tabla.php" method="POST"> 
        <br>

    <label for="bpn"><h1>Buscar por numero de parte</label>
    <input type="text" name="pn" id="pn" placeholder="B222930" autofocus>
    <input type="submit" name="enviar" id="enviar" value="Buscar"> </h1>
    </form></div>
     
    
    <div align="center">
        <br> <h2>Registros Conformantes</h2>
       <br>
    <table>
        <thead>
            <th>Fecha</th>
            <th>Numero de parte</th>
            <th>Cliente</th>
            <th>Work order</th>
            <th>Calibre</th>
            <th>Forma de crimpado</th>
            <th>Libras de resistencia</th>
            <th>Terminal aplicado</th>
            <th>Quien realizo</th>
            <th>Quien valido</th>
            <th>Resultado</th>
        </thead>
        <tbody>
<?php if($pn!=""){
$select="SELECT * FROM registro_pull WHERE (fecha LIKE '%$month' or fecha LIKE '%$lasrMonth' or fecha LIKE '%$last2Month') AND Num_part LIKE '%%$pn%%' ORDER BY id DESC ";}
else{$select="SELECT * FROM registro_pull WHERE (fecha LIKE '%$month' or fecha LIKE '%$lasrMonth' or fecha LIKE '%$last2Month')  ORDER BY id DESC ";}
$qry=mysqli_query($con,$select);
while($row=mysqli_fetch_array($qry)){
$date=$row['fecha'];
$aws=$row['calibre'];
$client=$row['Cliente'];
$pn=$row['Num_part'];
$wo=$row['wo'];
$pressure=$row['presion'];
$form=$row['forma'];
$term=$row['cont'];
$whop=$row['quien'];
$valid=$row['val'];
$tipo=$row['tipo']

?>
<tr>
    <td><?php echo $date; ?></td>
    <td><?php echo $pn; ?></td>
    <td><?php echo $client; ?></td>
    <td><?php echo $wo; ?></td>
    <td><?php echo $aws; ?></td>
    <td><?php echo $form; ?></td>
    <td><?php echo $pressure; ?></td>
    <td><?php echo $term; ?></td>
    <td><?php echo $whop; ?></td>
    <td><?php echo $valid; ?></td>
    <td><?php echo $tipo; ?></td>

</tr>
<?php } ?>
        </tbody>
    </table>
    
    
    
   
    </div>
</body>
</html>