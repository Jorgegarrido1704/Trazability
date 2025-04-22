<?php 

date_default_timezone_set("America/Mexico_City");
require "../conection.php";
$busc="SELECT * FROM calidad";
$qry=mysqli_query($con,$busc);
while($row=mysqli_fetch_array($qry)){
    $pn=$row['np'];
    $info=$row['info'];
    $reg="SELECT * FROM registro WHERE info='$info' ";
    $qryreg=mysqli_query($con,$reg);
    $num_reg=mysqli_num_rows($qryreg);
    while($rowreg=mysqli_fetch_array($qryreg)){
        $pnreg=$rowreg['NumPart'];
        $count=$rowreg['count'];
            
    }
        if($num_reg<=0){
            echo "el pn ".$pn.'  no existe favor de verificarlo '.$info.' <br>';
            $delete="DELETE FROM calidad WHERE info='$info'";
           $qrydelete=mysqli_query($con,$delete);
        }
}
$sql = "SELECT info, COUNT(info) as cantidad_duplicados FROM calidad GROUP BY info HAVING COUNT(info) > 1";


$resultado = mysqli_query($con, $sql);


if ($resultado) {
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $info_duplicado = $fila['info'];
        $cantidad_duplicados = $fila['cantidad_duplicados'];

        echo "El producto con info '$info_duplicado' tiene $cantidad_duplicados duplicados.<br>";
    }

    
    mysqli_free_result($resultado);
} else {
    
    echo "Error al ejecutar la consulta: " . mysqli_error($con);
}
$sql = "DELETE p1 FROM calidad p1
        INNER JOIN calidad p2 ON p1.info = p2.info AND p1.id < p2.id";


$resultado = mysqli_query($con, $sql);


if ($resultado) {
    $num_filas_afectadas = mysqli_affected_rows($con);
    echo "Se eliminaron $num_filas_afectadas duplicados más antiguos.";
} else {
    
    echo "Error al ejecutar la consulta: " . mysqli_error($con);
}


mysqli_close($con);
header("location:../depuracion.php");