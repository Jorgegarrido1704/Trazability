<?php 

require "../app/conection.php";
require "../vendor/autoload.php";
$i=0;
$auditado=array();
$datos=array();
$select=isset($_POST['Auditado'])?$_POST['Auditado']:'';
if($select){
    $selectDatos=mysqli_query($con,"SELECT * FROM inventario WHERE Register='$select'");
    while($row=mysqli_fetch_array($selectDatos)){
        $datos[$i][0]=$row['items'];
        $datos[$i][1]=$row['Register'];
        $datos[$i][2]=$row['qty'];
        $datos[$i][3]=$row['id_workOrder'];       
        $i++;
    }
}else{
$buscarLog=mysqli_query($con,"SELECT user FROM login WHERE category='invent' ORDER BY id DESC");
while($row=mysqli_fetch_array($buscarLog)){
    $auditado[$i]=$row['user'];
    $i++;
}
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Inventario</title>
</head>
<body>
    
                <?php if(empty($select)){ ?>   
                    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Buscar auditado</h1>
                <form action="excelInv.php" method="POST">
                    <div class="form-group">
                        <label for="Auditado">Auditado:</label>
                        <select name="Auditado" id="Auditado">
                            <?php foreach($auditado as $aud){ echo "<option value='$aud'>$aud</option>"; } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
                </div>    
        </div>       
    </div>
                <?php } else{ ?>
                    <a href="registro.php?who=<?php echo $select; ?>"><img src="../reportes/img/excel.jpg" alt="logo" style="width: 60px; height: 60px;"></a>
                    <table class="table table-bordered" >
                        <tr><th>Items</th><th>Qty</th><th>Register</th><th>Part number</th>
                        <?php foreach($datos as $dato){ ?>
                            <tr>
                                <td><?php echo $dato[0]; ?></td>
                                <td><?php echo $dato[2]; ?></td>
                                <td><?php echo $dato[1]; ?></td>
                                <td><?php echo $dato[3]; ?></td>
                            </tr>
                        <?php } ?>
                    </table>    


                <?php } ?>
           
</body>
</html>