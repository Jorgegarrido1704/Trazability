<?php
 require "../app/conection.php";

 $terminals= isset($_POST['terminals']) ? $_POST['terminals'] : "";
 $terminals=strtoupper($terminals);
 $terminales=trim($terminals);


 if($terminals){
    echo "<button><a href='terminales.php'>Volver</a></button><br><br>";
    $terminalesCom=mysqli_query($con,"SELECT terminal1,  aws, pn, cons from listascorte WHERE terminal1 LIKE '$terminals' 
           OR terminal1 LIKE '$terminals (%'
           OR terminal1 LIKE '$terminals %'
        ORDER BY id DESC");
    while($row=mysqli_fetch_assoc($terminalesCom)){

        $terminal=$row['terminal1'];
       $pn=$row['pn'];
        $cons=$row['cons'];
        $aws=$row['aws'];

        echo "Terminal: $terminal   calibre: $aws  pn: $pn  cons: $cons <br>";        
 }
 $terminalesCom1=mysqli_query($con,"SELECT  terminal2, aws, pn, cons from listascorte WHERE terminal2 LIKE '$terminals' 
           OR terminal2 LIKE '$terminals (%'
           OR terminal2 LIKE '$terminals %'
        ORDER BY id DESC");
    while($rows=mysqli_fetch_assoc($terminalesCom1)){
        
        $terminal2=$rows['terminal2'];
        $aws=$rows['aws'];
        $pn=$rows['pn'];
        $cons=$rows['cons'];
        echo "Terminal: $terminal2   calibre: $aws pn: $pn  cons: $cons<br>";        
 }
}else{
  ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Terminales</title>
    </head>
    <body>
        <form action="terminales.php" method="post">
            <input type="text" name="terminals">
            <input type="submit" value="Buscar">
        </form>

    </body>
    </html>
  <?php
 
}