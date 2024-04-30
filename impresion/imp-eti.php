<?php 
session_start();
 require "../app/conection.php";
 $nump=isset($_POST['parte'])?$_POST['parte']:"";
 $cons=isset($_POST['cons'])?$_POST['cons']:"";
 $tren=isset($_POST['tren'])?$_POST['tren']:"";
 $wo=isset($_POST['wo'])?$_POST['wo']:"";
 $qty=isset($_POST['qty'])?$_POST['qty']:"";
 $rev=isset($_POST['rev'])?$_POST['rev']:"";
 $busccons=mysqli_query($con,"SELECT * FROM impresion WHERE np='$nump'");
 $rownum=mysqli_num_rows($busccons);
 if($rownum<=0){
    $insert=mysqli_query($con,"INSERT INTO `impresion`( `np`, `cons`, `tren`) VALUES ('$nump','$cons','$tren')");


 }

 if($nump!=""){
    $buscar=mysqli_query($con,"SELECT * FROM precios WHERE pn='$nump'");
    $rownum=mysqli_num_rows($buscar);
    if($rownum>0){
    while($row=mysqli_fetch_array($buscar)){
        $client=$row['client'];
        $rev=$row['rev'];
        
        for($i=1;$i<=$cons;$i++){
          $buscarigual=mysqli_query($con,"SELECT * FROM consterm WHERE pn='$nump' and cons='$i'");
          $rowsbus=mysqli_num_rows($buscarigual);
          while($rowbusc=mysqli_fetch_array($buscarigual)){
            $calibre=$rowbusc['calibre'];
            $color=$rowbusc['color'];
            $term1=$rowbusc['term1'];
            $term2=$rowbusc['term2'];

        }if($rowsbus>0){
            echo "<div align='center' style='padding-top:3px;'><b>".$client."<br>".$nump." WO: ".$wo."<br>Rev: ".$rev."  Cant: ".$qty."  Cons:".$i."<br></b><b>Calibre".$calibre."Color".$color." Term1: ".$term1."<br>Term2: ".$term2."<br></b></div>";
        }else{
          echo "<div align='center' style='padding-top:3px;'><b>".$client."<br>".$nump." WO: ".$wo."<br>Rev: ".$rev."  Cant: ".$qty."  Cons:".$i."<br></b></div>";
          echo "<br>";
        }}
        if($tren>0){
            for($i=1;$i<=$tren;$i++){
                echo "<div align='center'><b>".$client."<br>".$nump." WO: ".$wo."<br>Rev: ".$rev."  Cant: ".$qty." Trenzado:".$i."<br></b></div>";
                echo "<br>";
            }
        }

    }}else {
        header("location:consecutivo.php");
    }  
 } 
 ?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="5; url=../registro/work.php">
    <title>Impresiones</title>
 </head>
 <body>
    
 </body>
 </html>
 <script>
           window.onload = function() {
            window.print(); 
        };
    </script>

