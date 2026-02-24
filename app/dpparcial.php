<?php 

require "conection.php";

try{    
    $parcial = mysqli_query($con, "SELECT codeBar FROM registroparcial");
    while($row = mysqli_fetch_array($parcial)){
        echo $row['codeBar'] . "<br>";
      $buscarDiferencias=mysqli_query($con,"SELECT info FROM registro WHERE info='$row[codeBar]'");
      if(mysqli_num_rows($buscarDiferencias) > 0){
        echo "El código de barras " . $row['codeBar'] . " existe en la tabla registro.<br>";
      } else {
        echo "El código de barras " . $row['codeBar'] . " no existe en la tabla registro.<br>";
        mysqli_query($con, "DELETE FROM registroparcial WHERE codeBar='$row[codeBar]'");
      }
    }
}catch(Exception $e){
    echo "Error: " . $e->getMessage();
}