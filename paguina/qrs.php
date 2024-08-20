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
        <form action="qr2.php" method="post">
            <div class="mb-3">
                <label for="wo" class="form-label">Wo order</label>
                <input type="text" class="form-control" id="wo" name="wo" focus placeholder="WO" required>
            </div>
            <div class="mb-3">    
                <button class="btn btn-primary" type="submit">Imprimir</button>
            </div>

        </form>
        </div>

    </div>
</body>
</html>
