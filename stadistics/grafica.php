<!DOCTYPE html>
<html lang="es">
<?php
require "../app/conection.php";
$area=isset($_POST['area'])?$_POST['area']:"";
$i=0;
$salesT=0;

if($area=="cutting"){
$search="SELECT DISTINCT cliente FROM registro WHERE  (count='2' or count='3')";}
else if($area== "terminals"){
    $search="SELECT DISTINCT cliente FROM registro WHERE  (count='4' or count='5')";}
    else if($area== "assembly"){
        $search="SELECT DISTINCT cliente FROM registro WHERE  (count='6' or count='7')";}
else if($area== "looming"){
    $search="SELECT DISTINCT cliente FROM registro WHERE  (count='8' or count='9')";}
else if($area== "testing"){
    $search="SELECT DISTINCT cliente FROM registro WHERE  (count='10' or count='11')";}
    else if($area== "special wire"){
        $search="SELECT DISTINCT cliente FROM registro WHERE  (count='15')";}
        else if($area== "shipping"){
            $search="SELECT DISTINCT cliente FROM registro WHERE  (count='12')";}
            else{
                $search="SELECT DISTINCT cliente FROM registro";}
          $qryclient=mysqli_query($con, $search);
while($row = mysqli_fetch_array($qryclient)){
$etiquetas[$i]= $row["cliente"];
if($area=="cutting"){
    $sales="SELECT * FROM registro WHERE cliente='$etiquetas[$i]' and (count='2' or count='3')";}
        else if($area== "terminals"){
            $sales="SELECT * FROM registro WHERE cliente='$etiquetas[$i]' and (count='4' or count='5')";}
             else if($area== "assembly"){
                $sales="SELECT * FROM registro WHERE cliente='$etiquetas[$i]' and (count='6' or count='7')";}
    else if($area== "looming"){
        $sales="SELECT * FROM registro WHERE cliente='$etiquetas[$i]' and (count='8' or count='9')";}
    else if($area== "testing"){
        $sales="SELECT * FROM registro WHERE cliente='$etiquetas[$i]' and (count='10' or count='11')";}
        else if($area== "special wire"){
            $sales="SELECT * FROM registro WHERE cliente='$etiquetas[$i]' and (count='15' )";}
            else if($area== "shipping"){
                $sales="SELECT * FROM registro WHERE cliente='$etiquetas[$i]' and (count='12')";}
                else{
                    $sales="SELECT * FROM registro WHERE cliente='$etiquetas[$i]' ";}
$qrysales=mysqli_query($con, $sales);
while($rowsale = mysqli_fetch_array($qrysales)){
$qty=$rowsale["Qty"];
$price=$rowsale['price']; 

$sale=$qty*$price;
$salesT=$salesT+$sale;
$salesT=round($salesT,2);
}
$datosVentas[$i]=$salesT;
$salesT=0;
$i++;
}



?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
    <!-- Importar chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
</head>

<body>
    <div><small><a href="../main/principal.php"><button>Home</button></a></small></div>
    <h1 align="center">Sales <?php if($area==""){ echo "General";}else{
        echo $area;    } ?> Area</h1>
    <div align="center"><form action="grafica.php" method="POST">
        <label for="area"><h2>AREA:  </label><select name="area" id="are">
            <option value=""></option>
            <option value="cutting">Cutting</option>
            <option value="terminals">Terminals</option>
            <option value="assembly">Assembly</option>
            <option value="looming">Looming</option>
            <option value="testing">Electric Testing</option>
            <option value="special wire">Special Wire</option>
            <option value="shipping">Shipping</option>
        </select></h2>
    <input type="submit" id="reuqest" name="request" value="Request">
    </div>
    <canvas id="grafica"></canvas>
    <script type="text/javascript">
        
        const $grafica = document.querySelector("#grafica");
        
        const etiquetas = <?php echo json_encode($etiquetas) ?>;
        
        const datosVentas2020 = {
            label: "Sales by customer",
            
            data: <?php echo json_encode($datosVentas) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.2)', 
            borderColor: 'rgba(54, 162, 235, 1)', 
            borderWidth: 1, 
        };
        new Chart($grafica, {
            type: 'line', 
            data: {
                labels: etiquetas,
                datasets: [
                    datosVentas2020,
                    
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                },
            }
        });
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
