<?php 
session_start();
require "../app/conection.php";
$selectlat=mysqli_query($con,"SELECT * FROM registro ORDER BY id DESC LIMIT 1");
while($row=mysqli_fetch_array($selectlat)){
    
    $part=$row['NumPart'];
    $wo=$row['wo'];
    $qty=$row['Qty'];
    $rev=$row['rev'];
} 
$busccons=mysqli_query($con,"SELECT * FROM impresion WHERE np='$part'");
$rownum=mysqli_num_rows($busccons);
if($rownum>0){
    while($rows=mysqli_fetch_array($busccons)){
        $cons=$rows['cons'];
        $tren=$rows['tren'];
    }
}else{
    $cons=0;
    $tren=0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title>Document</title>
</head>

<body>
    <div><small><a href="../main/principal.php"><button>Home</button></a></small></div>
    <div align="center">
        <h1>Impresion de consecutivos</h1>
        <form action="imp-eti.php" method="POST">
          
        <input type="hidden" name="parte" id="parte" value="<?php echo $part; ?>">
  
        <input type="hidden" name="wo" id="wo" value="<?php echo $wo; ?>">
   
        <input type="hidden" name="rev" id="rev" value="<?php echo $rev; ?>">

   
        <input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">
   
        <div><label for="cons">Consecutivos</label>
        <input type="number" name="cons" id="cons" value="<?php echo $cons; ?>" required autofocus>
        <br>
    </div>
    
    <div><label for="tren">Trenzados</label>
<input type="number" name="tren" id="tren" value="<?php echo $tren; ?>"></div></h2> <br>
<input type="submit" name="enviar" id="enviar" value="Imprimir">
</form>
    </div>
</body>
</html>