<?php
session_start();
$accion=$_SESSION['accion'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="registro.css">
    <title>Registro</title>
</head>
<body>
   
   <br><br><br><br>
    <div>
        <form name="envio" action="registosHerramental.php" method="POST" onsubmit="return validation()" id="envio" >
            <h1>Escane su código</h1>
            <input type="password" name="codigo" id="codigo"> <!-- Changed type to "text" -->
        </form>
    <br><br><br>
    <button><a href="tabla.php">Tabla de registros</a></button>
    </div>
</body>
</html>
<script>
    function validation() {
        var id = document.envio.codigo.value;
        var Herramental = ["PRIMERO","EAPP2","EAPP3","EAPP4","EAPP5","EAPP6","EAPP7","EAPP8","EAPP9","EAPP10","EAPP11","EAPP12","EAPP13"
,"EAPP14","EAPP15","EAPP16","EAPP17","EAPP18","EAPP19","EAPP20","EAPP21","EAPP22","EAPP23","EAPP24","EAPP25","EAPP26","EAPP27","EAPP28","EAPP29",
"EAPP30","EAPP31","EAPP32","EAPP33","EAPP34","EAPP35","EAPP36","EAPP37","EAPP38","EAPP39","EAPP40","EAPP41","EAPP42","EAPP43"
,"EAPP44","EAPP45","EAPP46","EAPP47","EAPP48","EAPP49","EAPP50","EAPP51","EAPP52","EAPP53","EAPP54","EAPP55","EAPP56","EAPP57","EAPP58"
,"EAPP59","EAPP60","EAPP61","EAPP62","EAPP63","EAPP64","EAPP65","EAPP66","EAPP67","EAPP68","EAPP69","EAPP70","EAPP71","EAPP72","EAPP73",
"EAPP74","EAPP75","EAPP76","EAPP77","EAPP78","EAPP79","EAPP80","EAPP81","EAPP82","EAPP83","EAPP84","EAPP85","EAPP86","EAPP87","EAPP88","EAPP89","EAPP90","EAPP91","EAPP92","EAPP93","EAPP94","EAPP95","EAPP96","EAPP97","EAPP98","EAPP99","EAPP100","EAPP101","EAPP102","EAPP103","EAPP104","EAPP105","EAPP106","EAPP107","EAPP108","EAPP109","EAPP110","EAPP111","EAPP112","EAPP113","EAPP114","EAPP115","EAPP116","EAPP117","EAPP118","EAPP119","EAPP120","EAPP121","EAPP122","EAPP123","EAPP124","EAPP125","EAPP126","EAPP127","EAPP128"];
                if (id === "") {
            alert("El campo de código está vacío");
            return false;
        }
        
        var found = false;
        for (var i = 0; i < Herramental.length; i++) {
            if (id === Herramental[i]) {
                found = true;
                break;
            }
        }
        
        if (!found) {
            alert("Herramental no registrado");
            return false;
        }
        
        return true;
    }
</script>
