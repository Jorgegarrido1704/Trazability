<?php 
 require "conection.php";

    $ids=isset($_POST['ids']) ? $_POST['ids'] : "";
    $status=isset($_POST['ESTATUS']) ? $_POST['ESTATUS'] : "";
    $tiempo = isset($_POST['tiempo']) ? $_POST['tiempo'] : "";
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $tipomant=isset($_POST['tipomant']) ? $_POST['tipomant'] : "";
    $komment=isset($_POST['komment']) ? $_POST['komment'] : "";
    $periodo=isset($_POST['PERIODO']) ? $_POST['PERIODO'] : "";
    $desc=isset($_POST['desc']) ? $_POST['desc'] : "";
   
    
    if($id != ""){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row">
        <table class="table table-borderless">
                <thead>
                    <tr>
                        <th scope="col">FOLIO</th>
                        <th scope="col">FECHA</th>
                        <th scope="col">NOMBRE EQUIPO</th>
                        <th scope="col">QUIEN SOLICITO</th>
                        <th scope="col">AREA</th>
                        <th scope="col">ATENDIO POR</th>
                    </tr> 
                </thead>
                <tbody>
                    <?php
                    $buscar=mysqli_query($con,"SELECT * FROM registro_mant WHERE id='$id'");
                        while($row = mysqli_fetch_array($buscar)){
                            $min=$row['ttServ'];
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['fechReq']; ?></td>
                        <td><?php echo $row['equipo']; ?></td>
                        <td><?php echo $row['solPor']; ?></td>
                        <td><?php echo $row['area']; ?></td>
                        <td><?php echo $row['tecMant']; ?></td>                                         
                    </tr>
                    <?php } ?>
                </tbody>       
            </table>
        <form action="completar.php" method="POST">
  <div class="mb-3">
    <label for="tipomant" class="form-label">Tipo de Mantenimiento</label>
    <select name="tipomant" id="tipomant" class="form-select" required>
        <option value=""></option>
        <option value="MAQUINARIA">MAQUINARIA</option>
        <option value="SISTEMAS DE INFORMACION">SISTEMAS DE INFORMACION</option>
        <option value="ESTRUCTURAS Y PLANTA">ESTRUCTURAS Y PLANTA</option>
        <option value="PREVENTIVO">PREVENTIVO</option>
        <option value="PRUEBA ELECTRICA">PRUEBA ELECTRICA</option>      
        <option value="CORRECTIVO">CORRECTIVO</option>
    </select>
    <div id="tipMant" class="form-text">Selecciona el tipo de mantenimiento</div>
  </div>
  <div class="mb-3">
    <label for="PERIODO" class="form-label">Periocidad del Mantenimiento</label>
    <select name="PERIODO" id="PERIODO" class="form-select" required>
        <option value=""></option>
        <option value="UNA VEZ">UNA VEZ</option>
        <option value="SEMANAL">SEMANAL</option>
        <option value="MENSUAL">MENSUAL</option>
        <option value="TRIMESTRAL">TRIMESTRAL</option>      
        <option value="SEMESTRAL">SEMESTRAL</option>
        <option value="ANUAL">ANUAL</option>
    </select>
    <div id="PERIODO" class="form-text">Selecciona el periodo de mantenimiento</div>
  </div>
  <div class="mb-3">
    <label for="desc" class="form-label">Descripción del mantenimiento</label>
    <textarea name="desc" id="desc" class="form-control" rows="3" required></textarea>
  </div>
  <div class="mb-3">
    <label for="ESTATUS" class="form-label">Estatus del mantenimiento</label>
    <select name="ESTATUS" id="ESTATUS" class="form-select" required>
        <option value=""></option>
        <option value="FINALIZADO">FINALIZADO</option>
        <option value="PENDIENTE">PENDIENTE</option>
        <option value="INTERRUMPIDO">INTERRUMPIDO</option>
        <option value="FINALIZADO PERO NO OK">FINALIZADO PERO NO OK</option>
        <option value="PAUSADO">PAUSADO</option>      
    </select>
    <div id="ESTATUS" class="form-text">Seleccione el estatus del mantenimiento</div>
  </div>
  <div class="mb-3">
    <label for="komment" class="form-label">Comentarios adiccionales</label>
    <textarea name="komment" id="komment" class="form-control" rows="3"  required></textarea>
  </div>
  <div class="mb-3 form-check">
    <label class="form-label" for="tiempo">Tiempo total en minutos</label>   
    <input type="number" name="tiempo" id="tiempo" class="form-control" value="<?php echo $min; ?>"  min="0" required>
  </div>
  <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
    <label class="form-check-label" for="exampleCheck1">Todo correcto</label>
  </div>
  <input type="hidden" name="ids" id="ids" value="<?php echo $id; ?>">
  <button type="submit" class="btn btn-primary">Guardar </button>
</form>
        </div>    
    </div>    
</body>
</html>

<?php
    }else if($ids!=""){
    $update=mysqli_query($con,"UPDATE `registro_mant` SET tipoMant='$tipomant',periMant='$periodo',desctrab='$desc',estatus='$status',comentarios='$komment',ttServ='$tiempo',ValGer='David Villalpando' WHERE `id`=$ids");
    header("location:pendientes.php");
    
    }else{
        header("location:pendientes.php");
    }    

