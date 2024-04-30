<?php
session_start();
require "../app/conection.php";
$selectlat=mysqli_query($con,"SELECT info FROM registro ORDER BY id DESC LIMIT 1");
while($row=mysqli_fetch_array($selectlat)){
    $info=$row['info'];
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="5; url=consecutivo.php">
    
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <title>Print barcode</title>
    <style>
     
  </style>
</head>
<body>
  
<script>
var codigos=[
"<?php echo $info; ?>"
];
for (var i = 0; i < codigos.length; i++) {
    var value = codigos[i];
    var container = document.createElement("div");
    container.className = "barcode-container";

    var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("width", "200");
    svg.setAttribute("height", "100");
    svg.setAttribute("class", "barcode");
    svg.setAttribute("jsbarcode-format", "CODE128");
    svg.setAttribute("jsbarcode-value", value);
    svg.setAttribute("jsbarcode-textmargin", "0");
    svg.setAttribute("jsbarcode-fontoptions", "bold");
    JsBarcode(svg, value, {
        format: "CODE128",
        displayValue: true,
        fontSize: 12,
        textMargin: 0
    });

    container.appendChild(svg);
    
    document.body.appendChild(container);
}
      
      window.onload = function() {
            window.print(); 
        };
</script>
</body>
</html>