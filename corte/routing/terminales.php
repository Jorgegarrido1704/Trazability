<?php
require "../../app/conection.php";
require "timesReg.php";

if (isset($_GET['np'])) {
    $paramNp = $_GET['np'];
    if (strpos($paramNp, ',') !== false) {
        $datos = explode(',', $paramNp); 
    } else {
        $datos = [$paramNp];
    }
}else {
    echo "No se han recibido números de parte.";
    header("location:../registro.php");
}
foreach ($datos as $np) {
    $delete=mysqli_query($con,"DELETE FROM routing_models WHERE pn_routing='$np' and (work_routing='10081' or work_routing='10951')");

    $buscar = mysqli_query($con, "SELECT DataFrom,terminal1 FROM listascorte WHERE pn='$np' AND terminal1 IS NOT NULL and terminal1 !='' and 
    terminal1 not like 'Empalme%' AND terminal1 not like 'EMPALME%' and terminal1 not like 'SPL%' AND terminal1 not like 'SPLICE%'  
    AND   terminal1 not like 'JUMPER%' AND terminal1 not like 'CONECTOR%' AND terminal1 not like 'Blunt%' AND terminal1 not like 'PORTA%'
    AND terminal1 not like 'CORTAR%' AND terminal1 not like 'N/T%' AND terminal1 not like 'BLUNT%' 
    
     order by terminal1 desc");
    if (mysqli_num_rows($buscar) > 0) {
        while ($row = mysqli_fetch_array($buscar)) {
            $terminal = $row['terminal1'];
            $conector = $row['DataFrom'];

            if (strpos($terminal, '(')) {
                $terminal = substr($terminal, 0, strpos($terminal, '('));
            }
            if (isset($terminales[$terminal])) {
                $terminales[$terminal] = $terminales[$terminal] + 1;
            } else {
                $terminales[$terminal] = 1;
            }
            if (!strpos($terminal, 'T3-') and !strpos($terminal, 'T4-')and !in_array($terminal, $NoRequeridas)) {
                $random = rand(0, count($plugIn) - 1);
                $tiempoPlugIn = $plugIn[$random];
                $leyenda = "Plug $terminal Terminal in $conector";
              //  echo $terminal . " = 1   In $conector en $tiempoPlugIn segundos" . "<br>";
                $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
                VALUES ('$np','10951','pend','$leyenda','1','$tiempoPlugIn','300')");
            }
        }
    
    foreach ($terminales as $terminal => $qtyTerminal) {
        $insertar1 = mysqli_query($con, "INSERT INTO `routing_models`( `pn_routing`, `work_routing`, `posible_stations`, `work_description`, `QtyTimes`, `timePerProcess`, `setUp_routing`) 
            VALUES ('$np','10081','FB-081','$terminal','$qtyTerminal','3.084','300')");
    }
}
}

header("location:selloEnTerminal1.php?np=" . implode(',', $datos));
