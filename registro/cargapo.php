<?php 
session_start();
require "../app/conection.php";

if ($_SESSION['admin'] == "Admin" || $_SESSION['user'] == 'plan') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if ( empty($_POST['pn']) || empty($_POST['po']) || empty($_POST['qty']) || empty($_POST['Orday']) || empty($_POST['Reqday'])) {
            echo "<script>alert('Por favor, complete todos los campos');</script>";
        } else {
            $client = $_POST['client'];
            $np = $_POST['pn'];
            $po = $_POST['po'];
            $qty = $_POST['qty'];
            $rev = $_POST['Rev'];
            $desc = $_POST['Description'];
            $price = $_POST['Uprice'];
            $send = $_POST['Enviar'];
            $orday = $_POST['Orday'];
            $reqday = $_POST['Reqday'];
            $count=0;
            $id="";

            date_default_timezone_set("America/Mexico_City");
            $fecha = date("d-m-Y H:i");

            
            $selectduplicate = $con->prepare("SELECT * FROM po WHERE  po=? ");
            $selectduplicate->bind_param("s",  $po);
            $selectduplicate->execute();
            $selectduplicate->store_result();
            $rowcount = $selectduplicate->num_rows;
            $selectduplicate->close();

            if ($rowcount > 0) {
                echo "<script>alert('Arnes ya registrado, Revíselo y vuelva a intentarlo');</script>";
                
            } else {
                
                $insert = $con->prepare("INSERT INTO po (id,client, pn, fecha, rev, po, qty, description, price, send, orday, reqday, count) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
                $insert->bind_param("sssssssssssss",$id, $client, $np, $fecha, $rev, $po, $qty, $desc, $price, $send, $orday, $reqday, $count);
                if ($insert->execute()) {
                    echo "<script>alert('Registro exitoso');</script>";
                } else {
                    echo "<script>alert('Error al registrar');</script>";
                }
                $insert->close();
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
    <link rel="stylesheet" href="cargapo.css">
    <title>Registro de PO</title>
</head>
<body>
        <h1>Alta de PO en ordenes </h1>
        <div class="home-button"> <button   ><a href="../main/principal.php">Home</a></button></div>
         <form action="cargapo.php" method="POST">
         <div>
           
           <label for="pn">Número de parte</label>
            <input type="text" name="pn" id="pn" required onchange="return obtenerInformacion()" autofocus>
           <label for="client">Cliente</label>
           <input type="text" id="client" name="client" required>
            <label for="Rev">REV</label>
            <input type="text" name="Rev" id="Rev" required>
            <label for="Description">Descripcion</label>
            <input type="text" id="Description" name="Description">
            <label for="Uprice">precio unitario</label>
            <input type="number" name="Uprice" id="Uprice" step="0.01" min="0" required>
            <label for="Enivar">Enviar a</label>
            <input type="text" id="Enviar" name="Enviar">
            <label for="po">PO</label>
           <input type="text" id="po" name="po" required>
            <label for="qty">Cantidad req</label>
            <input type="number" name="qty" id="qty" min="1" required>
           <label for="Orday">Dia que se ordeno(Formato dd/mm/YY)</label>
            <input type="text" id="Orday" name="Orday" required>
            <label for="Reqday">Dia requerido(Formato dd/mm/YY)</label>
            <input type="text" name="Reqday" id="Reqday" required>
            
         </div>         
            <input type="submit" name="enviar" id="enviar" value="Crear">
         </form>

         <footer>    <style>
        footer{            background-color: lightslategray;
            color: aliceblue;
            height: 40px;  
            align-items: center;
           width: 100%;                                }
            p{                font: bold italic            } 
            
    </style>    <p>Created by Jorge Garrido</p></footer>

</body>
</html>
<script>
   
      function obtenerInformacion() {
    var pn = document.getElementById('pn').value;

    
    if (pn && pn.trim() !== '') {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../app/consulta.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 400) {
                try {

                    var respuesta = JSON.parse(xhr.responseText);
                    console.log(respuesta);
                    document.getElementById('client').value = respuesta.client || '';

                    document.getElementById('Rev').value = respuesta.Rev || '';
                    document.getElementById('Description').value = respuesta.Description || '';
                    document.getElementById('Uprice').value = respuesta.Uprice || '';
                    document.getElementById('Enviar').value = respuesta.send || '';
                } catch (error) {
                    console.error('Error parsing JSON response:', error);
                }
            } else {
                console.error('Error in XMLHttpRequest. Status:', xhr.status);
            }
        };

        xhr.onerror = function() {
            console.error('Request failed');
        };

        xhr.send('pn=' + pn);
    } else {
        
        console.error('Invalid part number');
        document.getElementById('Rev').value = '';
        document.getElementById('Description').value = '';
        document.getElementById('Uprice').value = '';
        document.getElementById('Enviar').value = '';
    }
}

</script>

<?php
}else{
    header("location:../main/principal.php");
}?>