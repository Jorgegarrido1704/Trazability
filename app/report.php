<!DOCTYPE html>
<html>
<head>
    <title>Reporte enviado</title>
    <script>
        // Redirect to GeneralDiario.php after 3 seconds
        setTimeout(function() {
            window.location.href = './GeneralDiario.php';
        }, 3000);

        // Optional: Redirect again after 8 seconds (5 seconds after the first one)
        /*
        setTimeout(function() {
            window.location.href = './embarque.php';
        }, 8000);
        */
    </script>
</head>
<body>
    <div style="margin-top: 100px; text-align: center;">
        <h1>Reporte enviado correctamente</h1>
        <p>Redirigiendo a la página...</p>
    </div>
</body>
</html>
