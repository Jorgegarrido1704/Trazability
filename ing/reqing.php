<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('location:../main');
    exit(); 
}
if($_SESSION['user']='Plan' || $_SESSION['user']='Boss' || $_SESSION['user']='Adim'){
    header('location:reqingeng.php');
}else {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
    <link rel="stylesheet" href="sol.css">
    <title>Solicitud ingenieria</title>
</head>

<body>
    <div>
        <small><a href="../main/principal.php"><button>Home</button></a></small>
    </div>

    <div class="Cuadro de solicitud">
        <div class="title">
            <h1>Requerimiento de Ingenieria</h1>
            <a href="atendece.php"><button>Requerimientos</button></a>
        </div>
    </div>

    <div class="divCont">
        <form action="registroIng.php" method="POST" id="formulario">
            <div class="work">
                <label for="work">
                    <h2>Tipo de requerimiento</h2>
                </label>
                <select name="work" id="work">
                    <option value=""></option>
                    <option value="revChanges">Cambio de revisión</option>
                    <option value="Quotes">Notas</option>
                    <option value="newDocs">Nuevas documentaciones</option>
                    <option value="FloorIssues">Problemas en piso</option>
                </select>

                <h2>Describa su incidencia</h2>

                <div id="contenedor" class="contenedor">
                    <div class="req" id="req" name="req" contenteditable="true"></div>
                </div>

                <label for="quien">
                    <h2>Quien solicita</h2>
                </label>
                <input type="text" name="quien" id="quien">

                <label for="area">
                    <h2>Area que requiere el trabajo</h2>
                </label>
                <select name="area" id="area">
                    <option value=""></option>
                    <option value="cutArea">Corte</option>
                    <option value="TerminalsArea">Liberacion</option>
                    <option value="ASSEMBLY_Saul">Ensamble Saul</option>
                    <option value="ASSEMBLY_David">Ensamble David</option>
                    <option value="ASSEMBLY_Alejandra">Ensamble Alejandra</option>
                    <option value="ASSEMBLY_Brando">Ensamble Brando</option>
                    <option value="ASSEMBLY_Jesus">Ensamble Jesus</option>
                    <option value="LoomArea">Loom</option>
                    <option value="Quality">Calidad</option>
                </select>
                <br><br>
                <input type="hidden" name="desc" id="desc">
                <input type="submit" name="enviar" id="enviar" value="Send" onclick="return validator()">
            </div>
        </form>
    </div>

    <script>
        document.forms["formulario"].onsubmit = function () {
            document.getElementById("desc").value = document.getElementById("req").innerHTML;
        }

        function validator() {
            var quien = document.getElementById("quien").value;
            var area=document.getElementById("area").value;
            var info=document.getElementById("desc").value  = document.getElementById("req").innerHTML;
            var type=document.getElementById('work').value;
            if (!quien) {
                alert("You need to put something in 'Quien solicita'");
                return false;
            }else  if (!area) {
                alert("You need to put something in 'area'");
                return false;
            }else if(!info){
                alert("You need to put somethin in 'Descripcion'");
                return false;
            }else if(!type){
                alert("You need to put something in 'Tipo de Requerimiento'");
                return false;
            }

            return true; 
        }
    </script>
     <footer>    <style>
        footer{            background-color: lightslategray;
            color: aliceblue;
            height: 20px;  
            align-items: center;
           width: 100%;                                }
            p{                font: bold italic            } 
            
    </style>    <p>Created by Jorge Garrido</p></footer>
</body>

</html>

<?php
}
?>
