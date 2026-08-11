<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <title>agregar simulacion</title>
</head>
<body>
    <div class="row">
     <div class="col-lg-2 mb-4 border">
            <h1>Cargar MPS </h1>
            <form action="updateDatos.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="csv_file" accept=".csv">
                <br>
                <input type="submit" name="upload" value="Cargar MSP">
            </form>
        </div>
    </div>
</body>
</html>