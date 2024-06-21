<?php 
require '../../app/vendor/autoload.php';
require '../../app/conection.php';
require 'ingclass.php';
$boms=new boms();
$pn=isset($_POST['pn']) ? $_POST['pn'] : "";
$conteo_cable = [];
$terminales = [];
$sellos = [];
$tipo_cons = isset($_POST['tipo_cons']) ? $_POST['tipo_cons'] : [];
$cons = isset($_POST['cons']) ? $_POST['cons'] : [];
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : [];
$awg=isset($_POST['awg']) ? $_POST['awg'] : [];
$color=isset($_POST['color']) ? $_POST['color'] : [];
$tamano=isset($_POST['tamano']) ? $_POST['tamano'] : [];
$term1=isset($_POST['term1']) ? $_POST['term1'] : [];
$sello1=isset($_POST['sello1']) ? $_POST['sello1'] : [];
$term2=isset($_POST['term2']) ? $_POST['term2'] : [];
$sello2=isset($_POST['sello2']) ? $_POST['sello2'] : [];
$est=isset($_POST['est']) ? $_POST['est'] : [];
$from=isset($_POST['from']) ? $_POST['from'] : [];
$to=isset($_POST['to']) ? $_POST['to'] : [];
$komment=isset($_POST['komment']) ? $_POST['komment'] : [];
$consTotal=isset($_POST['consTotal']) ? $_POST['consTotal'] : "";
for($i=1;$i<=$consTotal;$i++){
    $addLista=new listaCorte();
   $addLista->insert($con,strtoupper($pn),strtoupper($tipo_cons[$i]),$cons[$i],strtoupper($tipo[$i]),$awg[$i],strtoupper($color[$i]),$tamano[$i],strtoupper($term1[$i]),strtoupper($sello1[$i]),strtoupper($term2[$i]),strtoupper($sello2[$i]),strtoupper($est[$i]),strtoupper($from[$i]),strtoupper($to[$i]),strtoupper($komment[$i])); 
   if ($tamano[$i] != 0) {
    $recop = $tipo[$i] . "," . $awg[$i] . "," . $color[$i];
    if (!array_key_exists($recop, $conteo_cable)) {
        $conteo_cable[$recop] = floatval($tamano[$i]);
    } else {
        $conteo_cable[$recop] += floatval($tamano[$i]);
    }
}
if ($term1[$i] != "") {
if(explode(" ",$term1[$i])){
    $term1[$i]=explode(" ",$term1[$i])[0];
}
if (!array_key_exists($term1[$i], $terminales)) {
    $terminales[$term1[$i]] = 1;
} else {
    $terminales[$term1[$i]] += 1;
}
}
if ($term2[$i] != "") {
if(explode(" ",$term2[$i])){
    $term2[$i]=explode(" ",$term2[$i])[0];
}
if (!array_key_exists($term2[$i], $terminales)) {
    $terminales[$term2[$i]] = 1;
} else {
    $terminales[$term2[$i]] += 1;
}
}
if ($sello1[$i] != "") {
if(explode(" ",$sello1[$i])){
    $sello1[$i]=explode(" ",$sello1[$i])[0];
}
if (!array_key_exists($sello1[$i], $sellos)) {
    $sellos[$sello1[$i]] = 1;
} else {
    $sellos[$sello1[$i]] += 1;
}
}
if ($sello2[$i] != "") {
if(explode(" ",$sello2[$i])){
    $sello2[$i]=explode(" ",$sello2[$i])[0];
}
if (!array_key_exists($sello2[$i], $sellos)) {
    $sellos[$sello2[$i]] = 1;
} else {
    $sellos[$sello2[$i]] += 1;
}
}
}

foreach ($conteo_cable as $key => $value) {
    
    $boms->insert($con,strtoupper($pn),strtoupper($key),$value);
    
}

foreach ($terminales as $key => $value) {
    $boms->insert($con,strtoupper($pn),strtoupper($key),$value);
}   
foreach ($sellos as $key => $value) {
    $boms->insert($con,strtoupper($pn),strtoupper($key),$value);
}  
 
?>
<style>
    table {
        width: 100%;
    }
    th, td {
        font-size: 12px;
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        padding: 5px;
    }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Listas</title>
</head>
<body>
    <label for="cons">Indique el número de consecutivos</label>
    <input type="number" name="cons" id="cons" onchange="consect()">
    <div id="consecutivos">

    </div>
</body>
</html>

<script>
    function consect() {
        var contest = document.getElementById('cons').value;
        var htmls = '<form method="POST" action="#"><input type="text" id="pn" name="pn"><table><thead><tr><th>tipo-cons</th><th>consecutivo</th><th>tipo</th><th>awg</th><th>color</th><th>tamaño</th><th>terminal1</th><th>sello1</th><th>terminal2</th><th>sello2</th><th>Estampado</th><th>From</th><th>To</th></tr></thead><tbody>';
        for (var i = 1; i <= contest; i++) {
            htmls += '<tr>' +
                     '<td><input type="text" name="tipo_cons[' + i + ']" id="tipo_cons_' + i + '"></td>' +
                     '<td><input type="text" name="cons[' + i + ']" id="cons_' + i + '" value="'+ i +'"></td>' +
                     '<td><input type="text" name="tipo[' + i + ']" id="tipo_' + i + '"></td>' +
                     '<td><input type="text" name="awg[' + i + ']" id="awg_' + i + '"></td>' +
                     '<td><input type="text" name="color[' + i + ']" id="color_' + i + '"></td>' +
                     '<td><input type="text" name="tamano[' + i + ']" id="tamano_' + i + '"></td>' +
                     '<td><input type="text" name="term1[' + i + ']" id="term1_' + i + '"></td>' +
                     '<td><input type="text" name="sello1[' + i + ']" id="sello1_' + i + '"></td>' +
                     '<td><input type="text" name="term2[' + i + ']" id="term2_' + i + '"></td>' +
                     '<td><input type="text" name="sello2[' + i + ']" id="sello2_' + i + '"></td>' +
                     '<td><input type="text" name="est[' + i + ']" id="est_' + i + '"></td>' +
                     '<td><input type="text" name="from[' + i + ']" id="from_' + i + '"></td>' +
                     '<td><input type="text" name="to[' + i + ']" id="to_' + i + '"></td>' +
                     '<td><input type="text" name="komment[' + i + ']" id="komment_' + i + '"></td>' +
                     '</tr>';
        }
        htmls += '</tbody></table><input type="hidden" name="consTotal" id="consTotal" value="' + contest + '"><input type="submit" value="Guardar"></form>';
        document.getElementById('consecutivos').innerHTML = htmls;
    }
</script>
