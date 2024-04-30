<?php 
session_start();
require '../app/conection.php';

if(!$_SESSION['usuario']){
    header('location:../main');
} else {
    $i=0;
    date_default_timezone_set('America/Mexico_City');
    $date=date('d-m-Y H:i');
    $date=strtotime($date);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="60">
    <link rel="stylesheet" href="tablas.css">
    <title>Engineery Times</title>
</head>
<body>
<div>
        <small><a href="../main/principal.php"><button>Home</button></a></small>
    </div>
    <div class="btn"> <a href="reqing.php"><button>Make a Requiment</button></a>
    <a href="atendido.php"><button>Waiting for proccess</button></a>
</div>
  
    <table>
        <thead>
            <th>WORK</th>
            <th>Description</th>
            <th>Who Request</th>
            <th>Area</th>
            <th>Application time</th>
            <th>Atendido por</th>
            <th>Timer</th>
        </thead>
        <tbody>
            <?php 
            $conbase = "SELECT * FROM reqing";
            $qry = mysqli_query($con, $conbase);
          
            while($row = mysqli_fetch_array($qry)){
                $type = $row['type'];
                $info = $row['info'];
                $who = $row['who'];
                $donde = $row['donde'];
                $timeIni[$i] = $row['timeIni'];
                $tiempoint = strtotime($timeIni[$i]);
                $atiende=$row['atiende'];
                $id= $row['id'];
                $count=$row['count'];
                if($atiende and  $count==1){
            ?>
            <tr>
            <td id="green"><?php echo $type; ?></td>
                <td id="green"><?php echo $info; ?></td>
                <td id="green"><?php echo $who; ?></td>
                <td id="green"><?php echo $donde; ?></td>
                <td id="green"><?php echo $timeIni[$i]; ?></td>
                
                   
              <td id="green"><?php echo $atiende;?></td>

                <td id="green" class="crono<?php echo $row['id'];  ?>" ></td>

                    
            </tr>
            <?php  }} ?> 
        </tbody>
    </table>
    <script>
        <?php
        $x=0;
        mysqli_data_seek($qry, 0); 
        while ($row = mysqli_fetch_array($qry)) {
            $tiempoint= strtotime($row['respTime']);
            $tiempoint= $date-$tiempoint;
        
        ?>
        var timer<?php echo $row['id']; ?> = <?php echo $tiempoint; ?>;
        setInterval(function() {
            timer<?php echo $row['id']; ?>++;
            document.querySelector('.crono<?php echo $row['id']; ?>' ).innerHTML = formatTime(timer<?php echo $row['id']; ?>);
        }, 1000);
        <?php  $x++;} ?>

        function formatTime(seconds) {
            var hours = Math.floor(seconds / 3600);
            var minutes = Math.floor((seconds % 3600) / 60);
            var remainingSeconds = seconds % 60;

            return hours + "h " + minutes + "m " + remainingSeconds + "s";
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

<?php } ?>
