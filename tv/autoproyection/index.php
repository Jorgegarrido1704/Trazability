<?php 
 //take names of png in folder 
    $files = glob('imagenes/*.png');
    
    if(!empty($files)){
        $cuenta = count($files) ;
    $random = rand(0, $cuenta - 1);
    $image = $files[$random];
    }else{
        $image="";
    }


    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <title>tv</title>
    <style>
       body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        background-color: rgba(247, 75, 75, 0.35);
        }
        img {
            position: center;
            top: 0;
            left: 0;
                       
           width: 95%;
            max-height: 740px;
            
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row" id="tv">
            <div class="col-12-md col-12-sm col-12-lg col-12-xl ">
                     <img id='imgTv'  src="<?php echo $image; ?>" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</body>
</html>
<script>
     function reloadPague(){
        location.reload();
    }
    setInterval(reloadPague, 10000);
</script>