<?php
session_start();
require "../app/conection.php";


$fecha = isset($_POST['date']) ? $_POST['date'] : "";
$numpart=isset($_POST['numpart'])? $_POST['numpart']:"";

if ($_SESSION['admin'] == "Admin" || $_SESSION['user'] == 'plan') {

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="configuration.css">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
    <title>Ordenes Creadas</title>
</head>
<body>
    <small><button><a href="../main/principal.php" id="principal">home</a></button></small>
<br><br>
<form action="confirmation.php" method="POST" id="fe" name="fe">
<label for="date">Ingrese la fecha a buscar(dia-mes-año) </label>
<input type="text" id="date" name="date">
<label for="numpart"> Busque por número de parte</label>
<input type="text" name="numpart" id="numpart">

<input type="submit" name="enviar" id="enviar" value="Buscar">
</form>



<div align="center"><h1>Ordenes creadas</h1></div>
<table>
    <tr>
        <th> Fecha </th>
        <th> Número de parte </th>
        <th> Cliente </th>
        <th> Work order </th>
        <th> PO </th>
        <th> cantidad </th>
        <th> Codigo de barras </th>
    </tr>
    <?php
  if($fecha>0){
    $sql = "SELECT * FROM registro WHERE fecha like '$fecha%'   ";
    $result = mysqli_query($con, $sql);
  }elseif($numpart) {
    $sql = "SELECT * FROM registro WHERE  NumPart like '%$numpart%'";
    $result = mysqli_query($con, $sql);   
  } else {
    $sql = "SELECT * FROM registro";
    $result = mysqli_query($con, $sql);}
        while ($row = mysqli_fetch_array($result)) {
        $img=$row['Barcode'];
        ?>
        <tr align="center" height="3">
            <td><?php echo $row['fecha']; ?></td>
            <td><?php echo $row['NumPart']; ?></td>
            <td><?php echo $row['cliente']; ?></td>
            <td><?php echo $row['wo']; ?></td>
            <td><?php echo $row['po']; ?></td>
            <td><?php echo $row['Qty']; ?></td>
            <td style="width: 30px; height:30px; ">
                <?php
                
                if (!empty($img)) {
                    
                    
                    echo "<img src='data:image/jpeg;base64," . base64_encode(file_get_contents($img)) . "' />";
                    
                } else {
                    
                    echo 'No Barcode';
                }
                ?>
                
            </td>
         
        </tr>
        <?php
    }
    mysqli_close($con);
    ?>
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
<?php
}else{
    header("location:../main/principal.php");
}?>