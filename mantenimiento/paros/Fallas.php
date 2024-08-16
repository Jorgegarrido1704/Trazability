<?php
require "conection.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="90">
    <link rel="stylesheet" href="fallas.css">
    <title>Fallas</title>
</head>
<body>
    <Table>
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
            $tabla = "SELECT * FROM registro_paro WHERE equipo = 'Mantenimiento' and  finhora = '' ORDER BY id DESC";
            $qry = mysqli_query($con, $tabla);
            while ($row = mysqli_fetch_array($qry)) {
                $idmant = $row['id'];
                $fmant = $row['fecha'];
                $equipo=$row['equipo'];
                $equimant = $row['nombreEquipo'];
                $damant = $row['dano'];
                $quienmant = $row['quien'];
                $areamant = $row['area'];
                $atiendemant = $row['atiende'];
                $style = ($atiendemant == "Nadie aun") ? "background-color: red;" : "background-color: orange;";
            ?>
                <tr style="<?php echo $style; ?>">
                    <td id='fecha<?php echo $idmant; ?>'><?php echo $fmant; ?></td>
                    <td id='fecha<?php echo $idmant; ?>'><?php echo $equipo; ?></td>
                    <td id='area<?php echo $idmant; ?>'><?php echo $areamant; ?></td>
                    <td id='equipo<?php echo $idmant; ?>'><?php echo $equimant; ?></td>
                    <td id='dano<?php echo $idmant; ?>'><?php echo $damant; ?></td>
                    <td id='quien<?php echo $idmant; ?>'><?php echo $quienmant; ?></td>
                    <td id='atiende<?php echo $idmant; ?>'><?php echo $atiendemant; ?></td>
                    <td>
                        <form action="registo_mantenimiento.php" method="POST" onsubmit="return Validation(<?php echo $idmant; ?>, '<?php echo $areamant; ?>', '<?php echo $equimant; ?>', '<?php echo $damant; ?>')">
                            <input type="hidden" name="id[]" value="<?php echo $idmant; ?>">
                            <input type="text" name="quienInput" id="quienInput<?php echo $idmant; ?>">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </Table>

    <script>
        function Validation(id, area, equipo, dano) {
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
        }
        window.onload = function() {
            <?php
            $qry = mysqli_query($con, $tabla);
            while ($row = mysqli_fetch_array($qry)) {
                $idmant = $row['id'];
                $areamant = $row['area'];
                $equimant = $row['nombreEquipo'];
                $damant = $row['dano']; 
                $team=$row['equipo'];
                $atiendemant = $row['atiende'];
                if ($atiendemant == "Nadie aun") {
            ?>
                var area<?php echo $idmant; ?> = "<?php echo $areamant; ?>";
                var equipo<?php echo $idmant; ?> = "<?php echo $equimant; ?>";
                var dano<?php echo $idmant; ?> = "<?php echo $damant; ?>";
                var team<?php echo $idmant; ?> = "<?php echo $team; ?>";
    
                var speech<?php echo $idmant; ?> = new SpeechSynthesisUtterance("Se requiere a " + team<?php echo $idmant; ?> + ".en el área de " + area<?php echo $idmant; ?> + ". para" + equipo<?php echo $idmant; ?> );
                speech<?php echo $idmant; ?>.lang = "es-US";
                window.speechSynthesis.speak(speech<?php echo $idmant; ?>);
            <?php
                }
            }
            ?>
        }
    </script>
</body>
</html>
