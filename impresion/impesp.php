<?php 

require "../app/conection.php";
$wo = isset($_GET['wo']) ? $_GET['wo'] : "";
$buscarCode = new corte;
$parte = new registro;

           
$buscarCode->select($con,'corte',"wo='$wo'");   

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="3; url=espcon.php">
    <title>Impresiones</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
</head>
<body>

<?php 
if($buscarCode){ 
for ($i = 0; $i < $buscarCode->maxRow(); $i++) {
    ?>
    <div style='padding-top:3px;'>
        <canvas id="barcode_<?php echo $i; ?>" style="width:180px; max-height:30px;"></canvas><img src='bergs.jpg' alt=''style=" max-height:30px;"> Cons: <?php echo $buscarCode->getrow($i,3); ?>
        <div>
            <b><?php echo $buscarCode->getrow($i,1); ?> Cant: <?php echo $buscarCode->getrow($i,13); ?>  WO: <?php echo $buscarCode->getrow($i,2); ?> AWS: <?php echo $buscarCode->getrow($i,5); ?> </b><br>
        </div>
        <div >
            <b>PN: <?php echo $buscarCode->getrow($i,0); ?>  Color: <?php echo $buscarCode->getrow($i,6); ?> Term1: <?php echo $buscarCode->getrow($i,8); ?> <br> Term2: <?php echo $buscarCode->getrow($i,9); ?> From: <?php echo $buscarCode->getrow($i,11); ?> TO: <?php echo $buscarCode->getrow($i,9); ?></b><br>
        </div>
    </div>
    <script>
        var canvas = document.getElementById("barcode_<?php echo $i; ?>");
        var ctx = canvas.getContext("2d");
        var codigos = "<?php echo $buscarCode->getrow($i,7); ?>";
        
        JsBarcode(canvas, codigos, {
            format: "CODE128",
            displayValue: true,
            fontSize: 12,
            textMargin: 0
        });
    
  


    window.onload = function() {
        window.print(); 
    };
</script>
<?php
}}else{
    ?>
   <div style="background-color:red;">
    <h1>Falta agregar Cosecutivos</h1>
</div>

    <?php  
}
?>
</body>
</html>
