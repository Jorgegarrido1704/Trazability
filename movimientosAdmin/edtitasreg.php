<?php
require "../app/conection.php";
$wo_post=isset($_POST['wo'])?$_POST['wo']:"";
$var=isset($_POST['var'])?$_POST['var']:[];
$upreg= new registro;

if(!empty($var)){
    $upreg->update($con,"registro","Qty='$var[0]',rev='$var[1]',count='$var[2]',paro='$var[3]',donde='$var[4]'","wo='$wo_post'");
    header("location:editarOrden.php");
}



$upreg->select($con,"registro","wo='$wo_post'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="editar.css">
    <title>Modificar registro</title>
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
            <th>Guardar</th>
        </thead>
        <tbody>
        <form action="edtitasreg.php" method="POST">
             <tr> <?php  for($j=0;$j<8;$j++){   if($j<=2){  ?>
                <td><?php echo $upreg->getInfo(0,$j); ?></td> <?php }else{ ?>
                    <td><input type="text" name="var[]" id="var[]"value="<?php echo $upreg->getInfo(0,$j); ?>"></td>
                    <?php }} ?>
                <td>
                    <input type="hidden" name="wo" id="wo" value="<?php echo $wo_post; ?>">
                    <input type="submit" name="enviar" id="enviar" value="Modificar">
                </form></td>    
            </tr>
                    <?php ?>
        </tbody>
    </table>

    </div>
    
</body>
</html>