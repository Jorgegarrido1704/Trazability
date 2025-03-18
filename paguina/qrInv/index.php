<?php 
require "../conector.php";

$buscando= mysqli_query($con,"SELECT DISTINCT invoiceNum FROM `inv`");



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Codes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
        <form action="qrs.php" method="post">
            <div class="mb-9">
                <label for="invoice" class="form-label">Invonce Number</label>
                <select name="invoice" id="invoice">
                    <option value="">Select</option>
                    <?php
                    while($row = mysqli_fetch_array($buscando)){
                        echo '<option value="'.$row['invoiceNum'].'">'.$row['invoiceNum'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-9">    
                <button class="btn btn-primary" type="submit">Imprimir</button>
            </div>

        </form>
        </div>

    </div>
</body>
</html>
