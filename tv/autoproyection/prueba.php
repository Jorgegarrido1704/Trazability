<?php
if (isset($_POST['foto'])) {
    $foto = $_POST['foto'];

    // Verificamos que el archivo exista y sea un .png válido
    if (file_exists($foto) && pathinfo($foto, PATHINFO_EXTENSION) === 'png') {
        if (unlink($foto)) {
            echo "<script>alert('Imagen eliminada correctamente.')"; 
        } else {
            echo "<script>alert('No se pudo eliminar la imagen.')"; 
        }
    } else {
        echo "<script>alert('Archivo no válido.')"; 
    }
}
if (isset($_FILES['addfoto']) && $_FILES['addfoto']['error'] === UPLOAD_ERR_OK) {
    $archivoTemporal = $_FILES['addfoto']['tmp_name'];
    $nombreOriginal = basename($_FILES['addfoto']['name']);
    $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

    if ($extension === 'png') {
        // Carpeta de destino en tu servidor
        $directorioDestino = 'imagenes/';

        // Asegurarse de que el directorio exista
        if (!file_exists($directorioDestino)) {
            mkdir($directorioDestino, 0755, true);
        }

        // Ruta completa a donde se moverá la imagen
        $rutaDestino = $directorioDestino . $nombreOriginal;

        // Mover archivo desde tmp al directorio del servidor
        if (move_uploaded_file($archivoTemporal, $rutaDestino)) {
            echo "<script>alert('Imagen PNG agregada correctamente.');</script>";
        } else {
            echo "<script>alert('Error al mover el archivo.');</script>";
        }
    } 
}



$files = glob('imagenes/*.png');
$cuenta = count($files);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Visuales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

    <style>
        #imgTv {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container mt-2">
        <div class="row ">
            
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <form action="prueba.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="addfoto" class="form-label">Selecciona una imagen PNG</label>
                        <input type="file" class="form-control" name="addfoto" id="addfoto" accept=".png" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Agregar imagen</button>
                </form>
            </div>
        </div>
            <hr/>
            <?php foreach ($files as $fill): ?>
                <div class="col-md-3 mb-4 ">
                    <div class="card mt-2">
                        <img id="imgTv" src="<?php echo ($fill); ?>" alt="Imagen" class="card-img-top">
                        <div class="card-body text-center">
                            <form action="prueba.php" method="post">
                                <input type="text" name="foto" value="<?php echo $fill; ?>" readonly>
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>
