<?php 
require "app.php";
date_default_timezone_set("America/Mexico_City");
$today=date("d-m-y");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $who=isset($_POST['quien'])?$_POST['quien']:"";
    $who=strtoupper($who);
    $where=isset($_POST['area'])?$_POST['area']:"";
    $where=strtoupper($where);
    

    if (isset($_POST['texthidden'])) {
        echo "<p>Quien: " . $who . "</p>";
        echo "<p>Donde: " . $where . "</p>";
        echo "<hr>";
        $buscfolio=mysqli_query($con,"SELECT folio FROM reqmat ORDER BY id DESC LIMIT 1");
        while($row=mysqli_fetch_array($buscfolio)){
            $folio=$row['folio'];

        }$folio=$folio+1;

        
        $dynamicData = json_decode($_POST['texthidden'], true);

        
        foreach ($dynamicData as $index => $element) {
            echo "<h2>Element $index:</h2>";
            echo "<p>Descripcion o link: " . $element['text'] . "</p>";
            echo "<p>Observacion: " . $element['obs'] . "</p>";
            echo "<p>Cantidad: " . $element['cantOb'] . "</p>";
            echo "<p>Unidad: " . $element['uni'] . "</p>";
            echo "<p>Nota adicional: " . $element['nota'] . "</p>";
            echo "<hr>";
            $text=$element['text']; $obs=$element['obs'];$cantOb=$element['cantOb'];$uni=$element['uni']; $nota=$element['nota'];
            
            $insert=mysqli_query($con,"INSERT INTO `reqmat`( `folio`, `fecha`, `who`, `donde`, `descripcion`, `observacion`, `cantidad`, `unidad`, `notas`, `firmaComp`, `aprovada`, `negada`) VALUES ('$folio','$today','$who','$where','$text','$obs','$cantOb','$uni','$nota','','','')");
        
        }
        header("location:index.html");
    } else {
        echo "<p>No dynamic data submitted.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>