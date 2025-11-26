<?php
require "../../app/conection.php";
require "registrosPrevios.php";

$index = rand(0, count($registros) - 1);
$count = $registros[$index]['count'];

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listas de cortes</title>
    <link rel="stylesheet" href="css/estilis.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<style>
body {
    background-color: rgba(87, 230, 252, 0.47);
}

#tableBody {
    scroll-behavior: smooth;
    overflow-y: scroll;
    height: 400px;
}
</style>

<body>
    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <img src="img/begstrsom.jpg" alt="Begstrsom" class="img-fluid" style="max-height: 200px; width: 100%;">
        </header>
        <div class="row">
            <div class="col-md-12 mt-2 mb-2" id="infor">
                <table class="table table-bordered table-striped table-hover table-responsive" style="width: 100%;">
                    <thaed>
                        <tr>
                            <th class="text-center">Zona</th>
                            <th class="text-center">Lider</th>

                        </tr>
                    </thaed>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo $registros[$index]['zona']; ?></td>
                            <td class="text-center"><?php echo $registros[$index]['lider']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 mt-2 mb-2 " id="registros">
                <div class="form-group text-center mt-4 flex-column">
                    <table class="table table-bordered table-striped table-hover table-responsive" style="width: 100%;">
                        <thead>
                            <tr>
                                <th class="text-center">Cliente</th>
                                <th class="text-center">Número de parte</th>
                                <th class="text-center">Order de trabajo</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Fecha solicitada</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider" id="tableBody">
                            <?php
                        $query = "SELECT cliente, wo, NumPart, Qty, reqday
                                    FROM registro
                                    WHERE $count
                                    ORDER BY STR_TO_DATE(reqday, '%d-%m-%Y') ASC, cliente ASC
                                    LIMIT 15;";
                        $result = mysqli_query($con, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $color = "";
                            $dayNow = date("d-m-Y");
                            $reqday = $row['reqday'];
                            if(strtotime($dayNow) > strtotime($reqday)){
                                $color = "table-danger";
                            }else if (strtotime("+7 day", strtotime($reqday)) <= strtotime($reqday) ) {
                                $color = "table-warning";
                            } else {
                                $color = "table-success";
                            }
                            
                            echo "<tr class='$color'>";
                            echo "<td class='text-center'>" . $row['cliente'] . "</td>";
                            echo "<td class='text-center'>" . $row['NumPart'] . "</td>";
                            echo "<td class='text-center'>" . $row['wo'] . "</td>";
                            echo "<td class='text-center'>" . $row['Qty'] . "</td>";
                            echo "<td class='text-center'>" . $row['reqday'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>
</body>

</html>
<script>
function scrollToBottom() {
    // Scroll down by 200px from current position
    window.scrollBy({
        top: 10,
        left: 0,
        behavior: 'smooth'
    });
}

function timerPermin() {
    window.location.reload();


}
setInterval(scrollToBottom, 330);
setInterval(timerPermin, 15000);
</script>