<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:../main');
    exit(); 
} else {
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
            <h1>Engineery's Requirement</h1>
            <a href="atendece.php"><button>Requirements</button></a>
        </div>

    </div>

    <div class="divCont">
        <form action="registroIng.php" method="POST" id="formulario">
            <div class="work">
                <label for="work">
                    <h2>Requirement type</h2>
                </label>
                <select name="work" id="work" required autofocus>
                    <option value=""></option>
                    <option value="revChanges">Rev Changes</option>
                    <option value="Quotes">Quotes</option>
                    <option value="newDocs">New Documentation</option>
                    <option value="FloorIssues">Floor Issue</option>
                </select>

                <h2>Describe your Request</h2>

                <div id="contenedor" class="contenedor">
                    <div class="req" id="req" name="req" contenteditable="true"></div>
                </div>

                <label for="quien">
                    <h2>Requestor</h2>
                </label>
                <input type="text" name="quien" id="quien">

                <label for="area">
                    <h2> Area who Requested</h2>
                </label>
                <select name="area" id="area">
                    <option value=""></option>
                    <option value="cutArea">Cut Area</option>
                    <option value="TerminalsArea">Liberation</option>
                    <option value="ASSEMBLY_Saul">Assembly Saul</option>
                    <option value="ASSEMBLY_David">Assembly David</option>
                    <option value="ASSEMBLY_Alejandra">Assembly Alejandra</option>
                    <option value="ASSEMBLY_Brando">Assembly Brando</option>
                    <option value="ASSEMBLY_Jesus">Assembly Jesus</option>
                    <option value="LoomArea">Loom</option>
                    <option value="Quality">Quality Area</option>
                    <option value="Planning">Planning</option>
                    <option value="OficceBob">Robert Smith</option>
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
