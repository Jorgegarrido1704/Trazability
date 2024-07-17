<?php
session_start();
require "../app/conection.php";
date_default_timezone_set('America/Mexico_City');
$id=isset($_GET['id'])?$_GET['id']:[];
$today=date("d-m-Y H:i");
$quienInput=isset($_GET['quienInput'])?$_GET['quienInput']:"";

echo $quienInput;
if($quienInput!=""){
    $buscar = mysqli_query($con,"SELECT * FROM registro_paro WHERE id='$id[0]'");
    $row = mysqli_fetch_array($buscar);
    $atiende = $row['atiende'];
    $inimant = $row['inimant'];
    if($atiende=="Nadie aun" and $inimant==""){
mysqli_query($con,"UPDATE registro_paro SET inimant='$today',atiende='$quienInput' WHERE id='$id[0]'");
    }else if($atiende!="" and $inimant!=""){
        $inif=strtotime($inimant);
        $finTime=strtotime($today);
        $dif=($finTime-$inif)/60;
        mysqli_query($con,"UPDATE registro_paro SET Tiempo='$dif',finhora='$today' WHERE id='$id[0]'");
        
    }
    header("Location: req.php");}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="60">
    <link rel="stylesheet" href="fallas.css">
    <title>Fallas</title>
</head>
<body>
<div><small><a href="../../main/principal.php" id="principal"><button>Home</button></a></small></div>
    <table>
        <thead>
            <th>Fecha de solicitud</th>
            <th>Equipo</th>
            <th>Area</th>
            <th>Trabajo</th>
            <th>Daño</th>
            <th>Quien solicita</th>
            <th>Quien atiende</th>
            <th>Iniciar mantenimiento</th>
        </thead>
        <tbody>
            <?php
            $qry = mysqli_query($con, "SELECT * FROM registro_paro WHERE equipo = 'Bancos para terminales' and finhora='' ");
            while ($row = mysqli_fetch_array($qry)) {
                $idmant = $row['id'];
                $fmant = $row['fecha'];
                $equipo = $row['equipo'];
                $equimant = $row['nombreEquipo'];
                $damant = $row['dano'];
                $quienmant = $row['quien'];
                $areamant = $row['area'];
                $atiendemant = $row['atiende'];
                $style = ($atiendemant == "Nadie aun") ? "background-color: red;" : "background-color: orange;";
            ?>
                <tr style="<?php echo $style; ?>">
                    <td id='fecha<?php echo $idmant; ?>'><?php echo $fmant; ?></td>
                    <td id='equipo<?php echo $idmant; ?>'><?php echo $equipo; ?></td>
                    <td id='area<?php echo $idmant; ?>'><?php echo $areamant; ?></td>
                    <td id='nombreEquipo<?php echo $idmant; ?>'><?php echo $equimant; ?></td>
                    <td id='dano<?php echo $idmant; ?>'><?php echo $damant; ?></td>
                    <td id='quien<?php echo $idmant; ?>'><?php echo $quienmant; ?></td>
                    <td id='atiende<?php echo $idmant; ?>'><?php echo $atiendemant; ?></td>
                   
                        <form action="req.php" method="GET" >
                        <td> <input type="hidden" name="id[]"  id="id[]" value="<?php echo $idmant; ?>">
                               <input type="text" name="quienInput" id="quienInput">  </td>
                            <td>  <input type="submit" class="btn btn-primary" value="Registro">  </td>
                        </form>
                   
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <script>
      /*  function Validation(id, area, equipo, dano) {
            var quien = prompt("¿Quién va a realizar el mantenimiento?");
            if (quien !== null) {
                // Set the value of the specific quienInput element based on the row id
                document.getElementById("quienInput" + id).value = quien;

                // Read out loud the name of the person performing maintenance
                var speech = new SpeechSynthesisUtterance(quien + " va a realizar el mantenimiento. El área es " + area + ". El equipo es " + equipo + ". El daño es " + dano + ".");
                speech.lang = "es-US";
                window.speechSynthesis.speak(speech);

                return true; // Allow the form submission
            } else {
                alert("No se registró a nadie para el mantenimiento.");
                return false; // Prevent the form submission
            }
        }*/

        // Function to read information for each row when the window loads
        window.onload = function() {
            <?php
            $qry = mysqli_query($con, "SELECT * FROM registro_paro WHERE equipo = 'Bancos para terminales' and finhora='' ");
            while ($row = mysqli_fetch_array($qry)) {
                $idmant = $row['id'];
                $areamant = $row['area'];
                $equimant = $row['nombreEquipo'];
                $damant = $row['dano']; 
                $team = $row['equipo'];
                $atiendemant = $row['atiende'];
                if ($atiendemant == "Nadie aun") {
            ?>
                var area<?php echo $idmant; ?> = "<?php echo $areamant; ?>";
                var equipo<?php echo $idmant; ?> = "<?php echo $equimant; ?>";
                var dano<?php echo $idmant; ?> = "<?php echo $damant; ?>";
                var team<?php echo $idmant; ?> = "<?php echo $team; ?>";
    
                var speech<?php echo $idmant; ?> = new SpeechSynthesisUtterance("Se requiere " + dano<?php echo $idmant; ?> + ". en la maquina " + area<?php echo $idmant; ?> + ". para el herramental " + equipo<?php echo $idmant; ?> );
                speech<?php echo $idmant; ?>.lang = "es-MX";
                window.speechSynthesis.speak(speech<?php echo $idmant; ?>);
            <?php
                }
            }
            ?>
        }
    </script>
</body>
</html>
