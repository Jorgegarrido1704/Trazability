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
        <h1>Cargar listas</h1>
        <form action="uplista.php" method="POST" enctype="multipart/form-data">
        
        <input type="file" name="csv_file" accept=".csv">
        <br>
        <input type="submit" name="upload" value="Cargar CSV">
        </form>
        </div>
        <div class="contenido">
           <h1>Update Boms</h1>
            <form action="guardarprueba.php" method="POST" enctype="multipart/form-data">
            
        <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls">
        <input type="submit" name="upload" value="Subir">
    </form>
        </div>
        <div class="contenido">
        <h1>Lista actividades</h1>
        <form action="../ing/upActividades.php" method="POST" enctype="multipart/form-data">
        
        <input type="file" name="csv_file" accept=".csv">
        <br>
        <input type="submit" name="upload" value="Cargar CSV">
        </form>
        </div>
    </div>
    <div class="titles">
         <div class="contenido">
        <h1>Cargar Routing</h1>
        <form action="routing/cortes.php" method="GET">
        <input type="text" name="np" id="np" required>
        <br>
        <input type="submit" name="upload" value="Cargar Routing">
        </form>
        </div>
        
    </div>
    <br>
    
    <?php
    if($activaListas!=""){
        $i=1;
        echo "<div class='titles'><h1 >Lista en sistema</h1>";
        echo "<table><tbody><tr>";
        $buscar=mysqli_query($con,"SELECT DISTINCT pn,rev FROM listascorte ");
        while($row=mysqli_fetch_array($buscar)){
            $nup=$row['pn'];
            $revs=$row['rev'];
            if($i%10==0 ){
            echo "<tr>";
            echo "<td>".$nup."-".$revs."</td>";
            }else{
                echo "<td>".$nup."-".$revs."</td>";
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