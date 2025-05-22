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
        <form action="qrReqCal.php" method="post">
            <div class="mb-3">
                <label for="wo" class="form-label">Wo order</label>
                <input type="text" class="form-control" id="wo" name="wo" autofocus placeholder="WO" required onchange="sobran()">
            </div>
            <div class="mb-3">
                <label for="const" class="form-label">Cantidad (max 10)</label>
                <input type="number" class="form-control" id="const" name="const"  placeholder="10" max="10" min="1" required>
            </div>
            <div class="mb-3">    
                <button class="btn btn-primary" type="submit">Imprimir</button>
            </div>

        </form>
        </div>

    </div>
</body>
</html>
<script>
    function sobran() {
    const woValue = document.getElementById('wo').value;

    fetch('sobran.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'wo=' + encodeURIComponent(woValue)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('const').setAttribute('max', data.max);
            alert("Puedes imprimir " + data.max + " QRs");
        } else {
            alert("No se encontró la orden de trabajo o hubo un error.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Ocurrió un error al consultar.");
    });
}

</script>