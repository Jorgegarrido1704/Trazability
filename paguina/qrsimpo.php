<?php 
require 'conector.php';
require 'vendor/autoload.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta https-equiv="refresh" content=" url=qrs.php">
    <title>QR Codes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            width:73mm; height:92mm;
        }
        #label {
           
              }
        .row {       width: 70mm;
            height: 32mm;
            flex-direction: column;
            justify-content: space-between;      display: block;            align-items: center;            margin-bottom: none;        }
        .data-container {  display: flex;  align-items: center;  border: solid 1px #000;  width: 69mm;  height: 31mm;            padding-top: 1px;             margin-left: 1px;}
        .etiqueta1 {display: flex;  align-items: left;   width: 24mm;  height: 31mm;            padding-top: 15px;             margin-left: 5px;}
        .space {display: flex;  align-items: center;   width: 20mm;  height: 31mm;            padding-top: 1px;             margin-left: 1px;}
        .etiqueta2 {display: flex;  align-items: right;   width: 24mm;  height: 31mm;            padding-top: 15px;             margin-left: 1px;}
        
    </style>
</head>
<body>
    
    <div style=" display:flex; width:28mm; height:36mm; rotate:90deg;  ">    
    
        <div class="row">
            <div class="data-container">
             <div class="etiqueta1">
                <h6>ONLY USE WITH ACTUATOR 1003381134</h6>
             </div>
             <div class="space">
                            </div>
             <div class="etiqueta2">
                <h6>ONLY USE WITH ACTUATOR 1003381134</h6>
             </div>
            </div>
        </div>
            
       
    </div>
   
    <script>
        window.onload = function() {
            print();
        }
    </script>
</body>
</html>


