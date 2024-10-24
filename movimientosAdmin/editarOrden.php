<?php 
require "../app/conection.php";
$wo_post=isset($_POST['wo'])?$_POST['wo']:"";
if($wo_post!=""){
    echo $wo_post;
}
$sel= new registro;
$sel->select($con,'registro',"id>0 order by NumPart asc,wo asc");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="editar.css">
    <title>Edit PO</title>
</head>
<body>
    <div><small><a href="../main/principal.php"><button>Home</button></a></small></div>
    <div align="center">
    <h1>Modificacion de Registros</h1>
    <table>
        <thead>
            <th>Num Part</th>
            <th>WO</th>
            <th>SONO</th>
            <th>Qty</th>
            <th>REV</th>
            <th>Count</th>
            <th>Paro</th>
            <th>Where</th>
            <th>Modificar</th>
        </thead>
        <tbody>
            <?php   for($i=0;$i<$sel->getrows();$i++){        ?>
             <tr> <?php  for($j=0;$j<8;$j++){     ?>
                <td><?php echo $sel->getInfo($i,$j); ?></td> <?php } ?>
                <td><form action="edtitasreg.php" method="POST">
                    <input type="hidden" name="wo" id="wo" value="<?php echo $sel->getInfo($i,1); ?>">
                    <input type="submit" name="enviar" id="enviar" value="Modificar">
                </form></td>    
            </tr>
                    <?php } ?>
        </tbody>
    </table>

    </div>
    
</body>
</html>
