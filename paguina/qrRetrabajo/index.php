<?php

require '../conector.php';
require '../vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};
try{
$pn=isset($_GET['pn'])?$_GET['pn']:"";
$cons=isset($_GET['cons'])?$_GET['cons']:"";
$today = date('mdY');
$datos = null;
$desc = null;
$codigo = null;
    if($pn!="" and $cons !=""){
      //  echo "Part Number: $pn <br>";
        //echo "Consignment: $cons <br>";
    $buscar = mysqli_query($con, "SELECT `CodigoIdentificaicon` FROM `registroqrs` where `CodigoIdentificaicon` Like '%$pn%$cons' order by id_qr desc limit 1");
    if (mysqli_num_rows($buscar) > 0) {
        $rows = mysqli_fetch_array($buscar);
        $codigo = $rows['CodigoIdentificaicon'];
        $datos = explode("|", $codigo);
    }
    //echo $codigo;
    $budcardesc=mysqli_query($con,"SELECT `description` FROM `registro` where `NumPart` = '$pn' order by id desc limit 1");
    if(mysqli_num_rows($budcardesc)>0){
        $rowsdesc = mysqli_fetch_array($budcardesc);
        $desc = $rowsdesc['description'];
    }
    }


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reimpresion de Qrs</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
                #label {
                    width: 38mm;
                    height: 24mm;
                    border-radius: 1px;
                    box-sizing: border-box;
                    display: inline-flex;
                    margin-top: 75px;
                    margin-left: 15px;



                }

                .row {
                    display: block;
                    align-items: flex-start;
                    margin-bottom: 1px;
                    margin-right: 2px;
                }

                .row1 {
                    display: block;
                    align-items: flex-start;
                    margin-bottom: 1px;
                }

                .qrs {
                    margin-top: 2mm;
                    width: 20mm;
                    height: 19mm;
                }

                .qrss {
                    margin-top: 1mm;
                    width: 20mm;
                    height: 12mm;
                }

                .data-container {
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-start;
                    padding-top: 15px;
                    margin-left: 2px;
                }

                .textarea-container {
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-start;

                    margin-left: 2px;
                }

                .datos {
                    width: 30mm;
                    border-radius: 1px;
                    border: solid 1px #000;
                    text-align: center;
                    margin: 1px 0;
                    padding-left: 1px;


                    box-sizing: border-box;
                }

                textarea {
                    width: 30mm;
                    height: 16mm;
                    border-radius: 1px;
                    border: solid 1px #000;
                    box-sizing: border-box;
                    resize: none;
                    font-size: xx-small;
                    overflow: hidden;
                    margin: 0;
                }

                #qrs_img {
                    width: 18mm;
                    height: 11mm;
                    margin-left: 3-2px;
                }

                .textarea-container {
                    margin-top: 0;
                }
    </style>
</head>

<body>
   <?php 
    if($datos){
        ?>
    <div style="display:flex; width: 38mm; height: 162mm">
        <div id="label" class="container">
            <div class="row">
                <div class="qrs">
                    <?php
                  
                $data = $codigo;
                $qrcode = (new QRCode)->render($data);
                ?>
                    <?php printf('<img src="%s" alt="QR Code" class="img-fluid" />', $qrcode);?>
                </div>
                <div class="qrss">
                    <img id="qrs_img" src="../proterra_dark.png" alt="codigo" class="img-fluid">
                </div>

            </div>
            <div class="row1">
                <div class="data-container">
                    <h6 class="datos"><?php echo $datos[1]; ?>|<?php echo $datos[2]; ?></h6>
                    <h6 class="datos"><?php echo $datos[4]; ?></h6>
                </div>

                <div class="textarea-container">
                    <textarea name="datost" id="datost"><?php echo $desc; ?></textarea>
                </div>
            </div>
        </div>
    </div>
     <script>
    window.onload = function() {
        print();
    }

    function returnqr() {
        setTimeout(function() {
            window.location.href = "index.php";
        }, 5000);
    }

    returnqr();
    </script>
    <?php }else{ ?>
    <div style="display:flex; width: 38mm; height: 162mm">
        <form action="index.php" method="GET">
            <input type="text" name="pn" placeholder="Enter Part Number">
            <input type="text" name="cons" placeholder="Enter Consignment">
            <button type="submit">Search</button>
        </form>
    </div>
   <?php } ?>

   
</body>

</html>



<?php
}catch(Exception $e){
    echo "Error: " . $e->getMessage();
}
?>