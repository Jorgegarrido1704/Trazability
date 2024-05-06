<?php 
require "../app/conection.php";
$tt1=0;
$tt2= 0;
$tt3= 0;
$tt4= 0;
$tt5= 0;  
$tt6= 0;
$tt7= 0;
$tt8= 0;
$tt9= 0;
$tt10= 0;
$tt11= 0;
$tt12= 0;  
$tt13= 0;
$tt14= 0;
$tt15= 0;

date_default_timezone_set("America/Mexico_City");
$date=date("d-m-Y");
$hour=date("H");

$SearchT="SELECT * FROM tiempos  WHERE calidad like '$date%%'";
$qryt=mysqli_query($con, $SearchT);
while($rowt=mysqli_fetch_array($qryt)) {
    $parcial=0;
$info=$rowt['info'];
$time=$rowt['calidad'];
$times=substr($time,0,10);
$h=substr($time,11,2);


$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%07:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt1=$tt1+$total;
$datos[0]=$tt1;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%08:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt2=$tt2+$total;
$datos[1]=$tt2;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%09:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt3=$tt3+$total;
$datos[2]=$tt3;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%10:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt4=$tt4+$total;
$datos[3]=$tt4;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%11:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt5=$tt5+$total;
$datos[4]=$tt5;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%12:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt6=$tt6+$total;
$datos[5]=$tt6;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%13:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt7=$tt7+$total;
$datos[6]=$tt7;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%14:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt8=$tt8+$total;
$datos[7]=$tt8;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%15:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt9=$tt9+$total;
$datos[8]=$tt9;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%16:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt10=$tt10+$total;
$datos[9]=$tt10;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%17:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt11=$tt11+$total;
$datos[10]=$tt11;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%18:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt12=$tt12+$total;
$datos[11]=$tt12;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%19:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt13=$tt13+$total;
$datos[12]=$tt13;
}$lookinreg="SELECT * FROM regsitrocalidad WHERE info='$info' and fecha like '$date%20:%%'";
$qrypar=mysqli_query($con, $lookinreg);
$numrowsparcial=mysqli_num_rows($qrypar);
    $SearchParcial="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con, $SearchParcial);
    while($rowreg=mysqli_fetch_array($qryreg)) {
        $pn=$rowreg['NumPart'];
        $price=$rowreg['price'];
        $total=$numrowsparcial*$price;
$tt14=$tt14+$total;
$datos[13]=$tt14;
}

}
$datos[14]=$tt13+$tt14+$tt12+$tt11+$tt10+$tt9+$tt8+$tt7+$tt6+$tt5+$tt4+$tt3+$tt2+$tt1;




$horarios=['7:00','8:00','9:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','Total'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="120">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <title>Times per part number </title>
</head>
<body>
    <div><small><a href="../main/principal.php"><button>Home</button></a></small></div>
        <div align="center"><a href="tabla.php"><button>Tabla de arneses</button></a></div>
        <div>

        <canvas id="grafica"></canvas>
    <script type="text/javascript">
        
        const $grafica = document.querySelector("#grafica");
        
        const etiquetas = <?php echo json_encode($horarios) ?>;
        
        const datosVentas2020 = {
            label: "SALES :  "+<?php echo json_encode($date) ?>,
            
            data: <?php echo json_encode($datos) ?>,
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
        </div>

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