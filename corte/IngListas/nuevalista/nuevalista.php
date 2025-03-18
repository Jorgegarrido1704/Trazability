<?php
require "../../../app/conection.php";
require "../Registros/index.php";
$creatos=strtoupper(isset($_POST['creador'])?$_POST['creador']:"");
$cliente=strtoupper(isset($_POST['cliente'])?$_POST['cliente']:"");
$pn=strtoupper(isset($_POST['pn'])?$_POST['pn']:"");
$rev=strtoupper(isset($_POST['rev'])?$_POST['rev']:"");
$corte=[$creatos, $cliente, $pn, $rev];
if(!empty($creatos) || !empty($cliente) || !empty($pn) || !empty($rev)){
    $movimiento = "Creacion de lista";
    $fecha = intval(strtotime(date("d-m-Y H:i")));
    $stmt = $con->prepare("INSERT INTO `registromovimientoslistas`(`creadorLista`, `pn`, `cliente`, `rev`, `Tipodemovimiento`, `ultimaFechaRegistro`) VALUES (?, ?, ?, ?,?, ?)");
    $stmt->bind_param("ssssss", $creatos, $cliente, $pn, $rev, $movimiento, $fecha);
    $stmt->execute();
    if($stmt){
        echo "<script>var respuesta = confirm('Deseas Continuar', 'Contunuar');
        if(respuesta == true) window.location = ('../normal/listaNew.php?corte=".implode(";", $corte)."&tipos=normal')</script>";
    }else{
        echo "<script>alert('Error al crear la lista');</script>";
    }
  
}

?>
<div class="container">
    <br><br>
<form action="nuevalista.php" method="POST" id="form-lista"> 
    <!-- Creador -->
<div class="input-group mb-3">
    <div class="input-group-prepend"><span class="input-group-text">Creador</span></div>
    <select name="creador" id="creador" class="form-control">
        <option value="" disabled selected>Seleccionar</option>
        <option value="NA">Nancy Aldana</option>
        <option value="PS">Paola Silva</option>
        <option value="JC">Jesus Cervera</option>
        <option value="JR">Jose Rodriguez</option>
        <option value="JG">Jorge Garrido</option>
        <option value="BS">Brandon Sanchez</option>
        <option value="AS">Arturo Santos</option>
        <option value="AV">Alejando Vargas</option>
    </select>
</div>
<!-- Cliente -->
<div class="input-group mb-3">
    <div class="input-group-prepend"><span class="input-group-text">Cliente</span></div>
    <select name="cliente" id="cliente" class="form-control" required>
                                              <option value="" disabled selected>Seleccionar</option>
                                              <option value="MORGAN OLSON">MORGAN OLSON</option>
                                              <option value="JOHN DEERE">JOHN DEERE</option>
                                              <option value="OP MOBILITY">OP MOBILITY</option>
                                              <option value="BROWN">BROWN</option>
                                              <option value="DUR-A-LIFT">DUR-A-LIFT</option>
                                              <option value="BERSTROMG">BERGSTROM</option>
                                              <option value="BLUE BIRD">BLUE BIRD</option>
                                                <option value="ATLAS">ATLAS</option>
                                                <option value="UTILIMASTER">UTILIMASTER</option>
                                                <option value="CALIFORNIA">CALIFORNIA</option>
                                                <option value="TICO MANUFACTURING">TICO MANUFACTURING</option>
                                                <option value="SPARTAN">SPARTAN</option>
                                                <option value="PHOENIX">PHOENIX</option>
                                                <option value="FOREST RIVER">FOREST RIVER</option>
                                                <option value="SHYFT">SHYFT</option>
                                                <option value="KALMAR">KALMAR</option>
                                                <option value="MODINE">MODINE</option>
                                                <option value="NILFISK">NILFISK</option>
                                                <option value="PLASTIC OMNIUM">PLASTIC OMNIUM</option>
                                                <option value="ZOELLER">ZOELLER</option>
                                                <option value="COLLINS">COLLINS</option>
                                                <option value="Proterra Powered LLC">Proterra Powered LLC.</option>
                                                </select>
</div>
<!-- Part Number -->
<div class="input-group mb-3">
    <div class="input-group-prepend"><span class="input-group-text">Numero de Parte</span></div>
    <input type="text" name="pn" id="pn" class="form-control" required>
</div>
<!-- Revision -->
<div class="input-group mb-3">
    <div class="input-group-prepend"><span class="input-group-text">Revision</span></div>
    <input type="text" name="rev" id="rev" class="form-control" required>
</div>
<!-- Boton de envio -->
<div class="input-group mb-3">
    <button type="submit" class="btn btn-primary">Guardar</button>
</div>       
</div>   
</form> 
</div>