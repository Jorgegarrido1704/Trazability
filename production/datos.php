<?php 

require "../app/conection.php";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get values from POST
    $wo = isset($_POST['wo']) ? $_POST['wo'] : [];
    

    // Validate if the data is not empty
    if (!empty($wo) ) {
        $CuentaItems = [];
        foreach ($wo as $woValue) {
            $buscarRegistro = mysqli_query($con, "SELECT NumPart,Qty,rev FROM `registro` WHERE `wo` = '$woValue'");
            if (!$buscarRegistro) {
                echo "<script>alert('la WO: " . $woValue . " no existe o no se encuentra activa ');</script>";
                continue; // Skip to the next iteration if there's an error
            }
            $row = mysqli_fetch_array($buscarRegistro);
            $numPart = $row['NumPart'];            
            $qty = $row['Qty'];
            $rev = $row['rev'];
            $buscarDatos = mysqli_query($con, "SELECT item,qty FROM `datos` WHERE `part_num` = '$numPart' and `rev` = '$rev' 
            and (`item` LIKE 'WTXL%' OR `item` LIKE 'WSG%' OR `item` LIKE 'WGX%' OR `item` LIKE 'CA%')");
            echo "<table class='table table-bordered mt-4'>
                    <thead>
                        <tr>
                            <th>NumPart:  ".$numPart."</th>
                            <th>WO: ".$woValue."</th>
                            <th>Rev: ".$rev."</th>
                            
                        </tr>
                        <tr>
                            <th>Item</th>
                            <th>Cantidad</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = mysqli_fetch_array($buscarDatos)) {
                $item = $row['item'];
                $qtitem= $row['qty'];
                $total = $qtitem * $qty;
                echo "<tr>
                        <td>$item</td>
                        <td>$total</td>
                    </tr>";
                    if(isset($CuentaItems[$item])) {
                        $CuentaItems[$item] += $total;
                    } else {
                        $CuentaItems[$item] = $total;
                    }
           }
            echo "</tbody></table> <br><hr><br>";

            
        }
        echo "<h2 class='mt-4'>Total Acumulado</h2>
        <table class='table table-bordered mt-4'>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($CuentaItems as $item => $total) {
            echo "<tr>
                    <td>$item</td>
                    <td>$total</td>
                </tr>";
        }
        echo "</tbody></table>";
        
    } else {
        // Display an error message
        echo "<div class='alert alert-danger'>Please fill out all fields.</div>";
    }
}else{
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
    <title>Total Datos</title>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Datos del WO</h2>

        <!-- Form to submit WO data -->
        <form method="POST">
            <div class="mb-3">
                <label for="qty" class="form-label">Cantidad de WO a revisar</label>
                <input type="number" class="form-control" id="qty" name="qty" required>
            </div>

            <div class="container" id="inHTML"></div>

            <button type="submit" class="btn btn-primary mt-3">Enviar</button>
        </form>
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>

    <script>
        document.getElementById('qty').addEventListener('change', function() {
            var qtyValue = this.value;
            var inHTML = "";

            if (qtyValue > 0) {
                // Generate the input fields for each WO
                for (let i = 0; i < qtyValue; i++) {
                    inHTML += `
                        <div class="mb-3">
                            <label for="wo${i+1}" class="form-label">WO ${i+1}</label>
                            <input type="number" class="form-control" id="wo${i+1}" name="wo[]" required>
                        </div>
                    `;
                }
                document.getElementById('inHTML').innerHTML = inHTML;  // Correctly update the innerHTML
            } else {
                document.getElementById('inHTML').innerHTML = '';  // Clear the fields if qty is 0 or invalid
            }
        });
    </script>
</body>
</html>
    <?php } ?>