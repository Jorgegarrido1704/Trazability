<?php 
session_start();

require "../app/conection.php";
if(!$_SESSION['usuario']){
    header("location:../main/index.html");
}
if($_SESSION['user']=="plan" or $_SESSION['admin']=="Admin"){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../main/fth.ico" type="image/x-icon">
    <link rel="stylesheet" href="workstyle.css">
    <title>Work order</title>
</head>
<body>
    
<small><button><a href="../main/principal.php" id="principal">Home</a></button></small>
    <form action="alta.php" method="POST" onsubmit="return verification()">
        <label for="wo">Seleccione el item que desea dar de alta</label>
        <select name="wo" id="wo" autofocus>
            <option value="">  </option>
            <?php
            $buscar = "SELECT * FROM po";
            $qrybusc = mysqli_query($con, $buscar);
            while($buscarrow = mysqli_fetch_array($qrybusc)) {
                if($buscarrow['count'] <= 0) {
                    $combinedValues = $buscarrow['pn'] . '|' . $buscarrow['po']; 
                    echo "<option value='" .  $combinedValues . "'>" . $buscarrow['pn'] ." ".$buscarrow['po']. "</option>";
                }
            }
            ?>
        </select>
        <input type="submit" name="submit" id="submit" value="submit">
    </form>
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

<script>
    function verification(){
        var valid = document.getElementById('wo').value;
        if(!valid){
            alert("No ha seleccionado nada.");
            event.preventDefault();
            return false;
        }
    }
</script>

<?php
}else{
    header("location:../main/principal.php");
}
?>