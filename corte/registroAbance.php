<?php 

require "../app/conection.php";
$codigo=isset($_POST['codigo'])?$_POST['codigo']:"";
$parte="";
$buscarDb=new registro;
$result=new corte;
$similares=new corte;



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>busqueda</title>
</head>
<style>
    table{
        width: 100%;
    }
    table, th, td {
  border: 1px solid black;  
  border-collapse: collapse;
  align-items: center;
  text-align: center;
    }
    .title{
        text-align: center;
    }
    </style>
<body>
    <div class="title">
        <form action="registroAbance.php" method="POST">
            <h1 class="title">Registrar Corte</h1>
            <input type="text" name="codigo" id="codigo">
            <input type="submit" name="buscar" id="buscar" value="Registro">
        </form>
        <br><br><br>
        <?php
      if($codigo!=""){
        $result->select($con,"corte","codigo='$codigo'");
        if($result->maxRow()>0){
        print("<h1 class='title'>INFORMACION GENEREL</h1>");
        echo "<table><thead><th>Numero de parte</th><th>Cliente</th><th>Wo</th><th>consecutivo</th><th>tipo de cable</th><th>calibre</th><th>Color</th><th>Terminal 1</th><th>Terminal 2</th><th>From</th><th>To</th><th>Cantidad</th></thead>";
        echo "<tbody><tr><td>".$result->getrow(0,0)."</td><td>".$result->getrow(0,1)."</td><td>".$result->getrow(0,2)."</td><td>".$result->getrow(0,3)."</td><td>".$result->getrow(0,4)."</td><td>".$result->getrow(0,5)."</td><td>".$result->getrow(0,6)."</td><td>".$result->getrow(0,8)."</td><td>".$result->getrow(0,9)."</td><td>".$result->getrow(0,11)."</td><td>".$result->getrow(0,12)."</td><td>".$result->getrow(0,13)."</td></tr></tbody></table>";
        $tipo=$result->getrow(0,4);
        $aws=$result->getrow(0,5);
        $color=$result->getrow(0,6);
        $id=$result->getrow(0,10);
        
       $similares->select($con,"corte","tipo='$tipo' AND aws='$aws' AND color Like '$color%%%' AND id!='$id'");
       print("<br><br><br><h1 class='title'>Los Cortes activos Que se pueden hacer Son:</h1> <br>");
       echo "<table><thead><th>Numero de parte</th><th>Cliente</th><th>Wo</th><th>consecutivo</th><th>tipo de cable</th><th>calibre</th><th>Color</th><th>Terminal 1</th><th>Terminal 2</th><th>From</th><th>To</th><th>Cantidad</th></thead><tbody>";
        for($i=0;$i<$similares->maxRow();$i++){
            
            echo "<tr><td>".$similares->getrow($i,0)."</td><td>".$similares->getrow($i,1)."</td><td>".$similares->getrow($i,2)."</td><td>".$similares->getrow($i,3)."</td><td>".$similares->getrow($i,4)."</td><td>".$similares->getrow($i,5)."</td><td>".$similares->getrow($i,6)."</td><td>".$similares->getrow($i,8)."</td><td>".$similares->getrow($i,9)."</td><td>".$similares->getrow($i,11)."</td><td>".$similares->getrow($i,12)."</td><td>".$similares->getrow($i,13)."</td></tr>";
            
        }
        echo "</tbody></table>";
        $deleteCorte=mysqli_query($con,"DELETE FROM corte WHERE id='$id'");
      }else{
          echo "<h1>No se encontraron resultados O el Corte ya se encuentra registrado</h1>";
      }
    }

      ?>
    </div>
</body>
</html>
