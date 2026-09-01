<?php

require '../conection.php';

try {
    $itemsSimulados = isset($_GET['items']) ? json_decode($_GET['items'], true) : [];
    $maquinaFiltro  = isset($_GET['maquina']) ? $_GET['maquina'] : 'todas';


    $calibres = [];
    $totalCables = 0;
    $tintaNegra = 0;
    $tintaBlanca = 0;
    $tintaNegraOpt = 0;
    $tintaBlancaOpt = 0;
    $tinta = '';
    $tiempo = '';
    $maxtime =135000*3;
    $cables = $terminales = $herramental = [];
    
    // CORRECCIÓN: Inicializar la variable del acumulador de tiempo
    $tiempoTotal = 0; 
    $i = 0;
    foreach ($itemsSimulados as $item) {
        $pn         = strtoupper(trim($item[1] ?? ''));
        $qty        = intval($item[3] ?? 0);
    
    
       $qry ="SELECT  c.color, c.aws, c.tipo, c.tamano, c.terminal1, c.terminal2
                                             FROM listascorte c 
        
                                              WHERE c.tamano >0 AND c.pn = '$pn'
                                             ORDER BY
                                             c.aws ASC,
                                            c.color ASC,
                                            c.tipo ASC,
                                             c.terminal1 ASC,
                                             CASE 
                                                WHEN c.terminal2 LIKE CONCAT('%',c.terminal1,'%') THEN 0 
                                                ELSE 1
                                            END
                                           
                                            ";

    $listasdecorte= mysqli_query($con,$qry);
    while ($rowlistas = mysqli_fetch_array($listasdecorte)) {
        
        $calibre = $rowlistas['aws'];
        $tipo = $rowlistas['tipo'];
        $color = $rowlistas['color'];
        $tamano = round($rowlistas['tamano'], 2)*$qty;
        $terminal1 = $rowlistas['terminal1'];
        $terminal2 = $rowlistas['terminal2'];


            // if exist the key in the array no add key else add key
            if (array_key_exists($calibre."-".$tipo."-".$color, $cables)) {
                $cables[$calibre."-".$tipo."-".$color]+=$tamano;
            }else{
                $cables[$calibre."-".$tipo."-".$color]=$tamano;
            }
            if(!array_key_exists($terminal1, $terminales) && stripos($terminal1, 'Empalme') === false){
                $terminales[$terminal1]=$qty;
            }
            if(!array_key_exists($terminal2, $terminales) && stripos($terminal2, 'Empalme') === false ){
                $terminales[$terminal2]=$qty;
            }
           
   
    }
}
    $datos=array();
    $datos['cables']=$cables;
    $datos['terminales']=$terminales; 
   
    // Devolvemos el JSON con los registros que alcanzaron a entrar en las 27000 unidades de tiempo
    echo json_encode($datos);

} catch (Exception $e) {
    error_log("Error cargando calibres: " . $e->getMessage());
    echo json_encode([]); // Es buena idea retornar un JSON vacío en lugar de nada si hay error
}

?>