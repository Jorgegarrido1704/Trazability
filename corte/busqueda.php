<?php
require "../app/conection.php";
$activaListas=isset($_GET['active'])?$_GET['active']:"";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #Lista {
    display: flex;
    flex-wrap: wrap; /* Opcional: permite que los elementos se ajusten en varias filas si no caben en una sola línea */
}

.contenido {
    flex: 1; /* Distribuye el espacio equitativamente entre los elementos */
    min-width: 200px; /* Define un ancho mínimo para los elementos, ajusta según sea necesario */
    padding: 10px; /* Espacio interno alrededor del contenido */
    box-sizing: border-box; /* Incluye el relleno y el borde en el ancho total del elemento */
    text-align: center; /* Centra el texto */
    border: 1px solid #ccc; /* Añade un borde a los elementos para visualizarlos mejor */
    margin: 5px; /* Espacio entre los elementos */
}
.titles{
    text-align: center;

}
table{
    width: 100%;

}
    </style>
    <title>Corte</title>
</head>
<body>
    <div id="Lista">
        <div class="contenido" >
            <h1>Listas de corte</h1>
    <form action="busqueda.php" method="GET">
    <input type="hidden" name="active" id="active" value="active">
    <input type="submit" name="enviar" id="enviar" value="Activar listas">
    </form>
        </div>
        <div class="contenido">
        <h1>Eliminar Lista</h1>
        <form action="eliminarLista.php" method="GET">
            <label for="elim">Numero de parte</label>
            <input type="text" name="elim" id="elim" required>
            <input type="submit" name="send" id="send" value="Eliminar">
        </form>
        </div>
        <div class="contenido">
            <h1>Modificacion de lista</h1>
            <form action="modificacionLista.php" method="GET">
        <label for="Num">Part Number: </label>
        <input type="text" name="Num" id="Num">
        <label for="cons1">Consecutivo: </label>
        <input type="text" name="cons1" id="cons1" placeholder="separados por comas(1,5)">
        <input type="submit" name="value" id="value" value="Buscar">
    </form>    
        </div>
        <div class="contenido">
            <h1>Update listas</h1>
            <form action="registro.php" method="POST">
                <input type="submit" name="relod" id="relod" value="Update">
            </form>
        </div>
        <div class="contenido">
            <h1>Update Bom</h1>
            <form action="datos.php" method="POST">
                <input type="submit" name="relod" id="relod" value="Update Bom">
            </form>
        </div>
       
    </div>
    <br>
    
    <?php
    if($activaListas!=""){
        $i=1;
        echo "<div class='titles'><h1 >Lista en sistema</h1>";
        echo "<table><tbody><tr>";
        $buscar=mysqli_query($con,"SELECT DISTINCT pn FROM listascorte ");
        while($row=mysqli_fetch_array($buscar)){
            $nup=$row['pn'];
            if($i%10==0 ){
            echo "<tr>";
            echo "<td>".$nup."</td>";
            }else{
                echo "<td>".$nup."</td>";
            }
            if(($i+1)%10==0){
                echo "</tr>";
            }
      $i++;
        }
        echo "</tbody></table></div>";
    }
    ?>

</body>
</html>