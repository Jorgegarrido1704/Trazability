<?php 
require "../app/conection.php";
$num=isset($_GET['Num']) ? $_GET['Num'] : "";
$cons1=isset($_GET['cons1'])? $_GET['cons1'] :"";
$id=isset($_POST['id']) ? $_POST['id'] :[];
$cons=isset($_POST['cons']) ? $_POST['cons'] :[];
$tipo=isset($_POST['tipo']) ? $_POST['tipo'] :[];
$aws=isset($_POST['aws']) ? $_POST['aws'] :[];
$color=isset($_POST['color']) ? $_POST['color'] :[];
$tamano=isset($_POST['tamano']) ? $_POST['tamano'] :[];
$strip1=isset($_POST['strip1']) ? $_POST['strip1'] :[];
$terminal1=isset($_POST['terminal1']) ? $_POST['terminal1'] :[];
$strip2=isset($_POST['strip2']) ? $_POST['strip2'] :[];
$terminal2=isset($_POST['terminal2']) ? $_POST['terminal2'] :[];
$conector=isset($_POST['conector']) ? $_POST['conector'] :[];
$dataFrom=isset($_POST['dataFrom']) ? $_POST['dataFrom'] :[];
$dataTo=isset($_POST['dataTo']) ? $_POST['dataTo'] :[];
if($id!=""){
    
    for($i=0;$i<count($id);$i++){
        echo    "id: ".$id[$i]." tipo: ".$tipo[$i]." cons: ".$cons[$i]." aws: ".$aws[$i]." color: ".$color[$i]." tamano: ".$tamano[$i]." strip1: ".$strip1[$i]." terminal1: ".$terminal1[$i]." strip2: ".$strip2[$i]." terminal2: ".$terminal2[$i]." conector: ".$conector[$i]." detaFrom: ".$dataFrom[$i]." dataTO: ".$dataTo[$i]."<br>";     
     }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AjusteLista</title>
</head>
<body>
    <?php 
    if($num=="" ){ ?>
    <form action="modificacionLista.php" method="GET">
        <label for="Num">Part Number: </label>
        <input type="text" name="Num" id="Num">
        <label for="cons1">Consecutivo: </label>
        <input type="text" name="cons1" id="cons1">
        <input type="submit" name="value" id="value" value="Buscar">
    </form>    
      <?php }else { ?>
        <table>
            <thead>
                <tr>
                    <th>Part Number</th>
                    <th>cons</th>
                    <th>tipo</th> 
                    <th>aws</th>
                    <th>color</th>
                    <th>tamano</th>
                    <th>strp1</th>
                    <th>term1</th>
                    <th>strp2</th>
                    <th>term2</th>
                    <th>conector</th>
                    <th>from</th>
                    <th>to</th>    
                </tr>
            </thead>
            <tbody>
            <?php if($cons1==''){
            $BuscarInfo=mysqli_query($con,"SELECT * from listascorte WHERE pn='$num' ");
      }else{$BuscarInfo=mysqli_query($con,"SELECT * from listascorte WHERE pn='$num' AND cons='$cons1' ");}
      while($row=mysqli_fetch_array($BuscarInfo)){?>
                <form action="modificacionLista.php" method="POST">
                <tr>
                    <input type="hidden" name="id[]" id="id" value="<?php echo $row['id']; ?>">
                    <td><input type="text" name="pn[]" id="pn" value="<?php echo $row['pn']; ?>"></td>
                    <td><input type="text" name="cons[]" id="cons" value="<?php echo $row['cons']; ?>"></td>
                    <td><input type="text" name="tipo[]" id="tipo" value="<?php echo $row['tipo']; ?>"></td>
                    <td><input type="text" name="aws[]" id="aws" value="<?php echo $row['aws']; ?>"></td>
                    <td><input type="text" name="color[]" id="color" value="<?php echo $row['color']; ?>"></td>
                    <td><input type="text" name="tamano[]" id="tamano" value="<?php echo $row['tamano']; ?>"></td>
                    <td><input type="text" name="strip1[]" id="strip1" value="<?php echo $row['strip1']; ?>"></td>
                    <td><input type="text" name="terminal1[]" id="terminal1" value="<?php echo $row['terminal1']; ?>"></td>
                    <td><input type="text" name="strip2[]" id="strip2" value="<?php echo $row['strip2']; ?>"></td>
                    <td><input type="text" name="terminal2[]" id="terminal2" value="<?php echo $row['terminal2']; ?>"></td>
                    <td><input type="text" name="conector[]" id="conector" value="<?php echo $row['conector']; ?>"></td>
                    <td><input type="text" name="dataFrom[]" id="dataFrom" value="<?php echo $row['dataFrom']; ?>"></td>
                    <td><input type="text" name="dataTo[]" id="dataTo" value="<?php echo $row['dataTo']; ?>"></td>
               </tr>     
               <?php }?>
               <input type="submit" name="values" id="values" value="Guardar">
                </form>
            </tbody>
        </table>
        
        
        <?php }?>
</body>
</html>