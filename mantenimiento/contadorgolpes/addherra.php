<?php
    include "../app/conection.php";
    session_start();
$apli=isset($_GET['ap1'])?$_GET['ap1']:"";
$apli=strtoupper($apli);
$term=isset($_GET['te1'])?$_GET['te1']:"";
$term=strtoupper($term);
if($apli!="" and $term!=""){
    $buscarcons=mysqli_query($con,"SELECT * FROM herramental WHERE herramental='$apli' ");
    $rownum=mysqli_num_rows($buscarcons);
    if($rownum<=0){
    mysqli_query($con,"INSERT INTO herramental (herramental,comp)VALUES('$apli','$term')");
        mysqli_query($con,"INSERT INTO mant_golpes_diarios (herramental,fecha_reg,golpesDiarios,golpesTotales,maquina,totalmant)VALUES('$apli','',0,0,'Bmacen_aplicadores',0)");
        header("location:addherra.php");
    } else {
        ?>
        <script>
            alert("El aplicador ya existe");
        </script>
        <?php
    }  


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Agregar aplicador</title>
</head>
<body>
    <div><small><a href="../../main/principal.php"><button>Home</button></a></small></div>
    <div align="center"><h1>Agregar Aplicador</h1>    
  <div class="col-md-12">
    <label for="aplicador" class="form-label">Nomenglatura aplicador</label>
    <input type="text" class="form-control" id="aplicador" required>
  </div>
  <div class="col-md-12">
    <label for="terminal" class="form-label">Terminales que se utilizan</label>
    <input type="text" class="form-control" id="terminal" required>
  </div>
  <br>
  <div class="col-12">
  <form class="row g-3" action="addherra.php" method="GET" onsubmit="return validate()">
    <input type="hidden" name="ap1" id="ap1">
    <input type="hidden" name="te1" id="te1">
    <input type="submit" class="btn btn-primary" value="Agregar">
    </form>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   </div>
</body>
</html>
<script>
    
    function validate(){
        var aplic=document.getElementById("aplicador").value;
        var terminal=document.getElementById("terminal").value;
        if(aplic=="" || terminal==""){
            alert("Por favor, complete todos los campos");
            return false;
        }else{
            document.getElementById("ap1").value=aplic;
            document.getElementById("te1").value=terminal;
            return true;
        }
    }
   
</script>