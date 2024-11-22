<?php
require "conection.php";
require 'vendor/autoload.php';
date_default_timezone_set("America/Mexico_City");
if(date("N")=="1"){
    $lastDate=strtotime(date('d-m-Y 17:00',strtotime('-3 day')));
}else{
$lastDate=strtotime(date('d-m-Y 17:00',strtotime('-1 day')));}
$todays=date("d-m-Y");
$today=date("d-m-Y 00:00");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheetwo = $spreadsheet->getActiveSheet();
// Work orders
$sheetwo->setTitle('Work order '.$todays);
$sheetwo->setCellValue('A1','Part Number');
$sheetwo->setCellValue('B1', 'Work Order');
$sheetwo->setCellValue('C1', 'Original Quantity');
$sheetwo->setCellValue('D1', 'Cutting');
$sheetwo->setCellValue('E1', 'Terminals');
$sheetwo->setCellValue('F1', 'Assembly');
$sheetwo->setCellValue('G1', 'Looming');
$sheetwo->setCellValue('H1', 'Testing');
$sheetwo->setCellValue('I1', 'Shipping');
$sheetwo->setCellValue('J1', 'Tiempo en proceso');
$t=2;
$buscarWo=mysqli_query($con,"SELECT * FROM `registroparcial` INNER JOIN `registro` ON registroparcial.codeBar=registro.info ORDER BY `pn` DESC");
While($row=mysqli_fetch_array($buscarWo)){
    $pn=$row['pn'];
    $wo=$row['wo'];
    $orgQty=$row['orgQty'];
    $cortPar=$row['cortPar'];
    $libePar=$row['libePar'];
    $ensaPar=$row['ensaPar'];
    $loomPar=$row['loomPar'];
    $testPar=$row['testPar'];
    $embPar=$row['embPar'];   
    $tiempo=$row['tiempototal'];
$sheetwo->setCellValue('A'.$t, $pn);
$sheetwo->setCellValue('B'.$t, $wo);
$sheetwo->setCellValue('C'.$t, $orgQty);
$sheetwo->setCellValue('D'.$t, $cortPar);
$sheetwo->setCellValue('E'.$t, $libePar);
$sheetwo->setCellValue('F'.$t, $ensaPar);
$sheetwo->setCellValue('G'.$t, $loomPar);
$sheetwo->setCellValue('H'.$t, $testPar);
$sheetwo->setCellValue('I'.$t, $embPar);
$sheetwo->setCellValue('J'.$t, $tiempo);
$t++;        
}
//Movimientos del dia
$sheetMov=$spreadsheet->createSheet();
$sheetMov->setTitle('Movimientos del dia en areas');
$sheetMov->setCellValue('A1','Part Number');
$sheetMov->setCellValue('B1', 'Work Order');
$sheetMov->setCellValue('C1', 'Cantidad de arneses');
$sheetMov->setCellValue('D1', 'Dolares en movimiento');
$sheetMov->setCellValue('E1', 'Quien realizo el movimiento');
$sheetMov->setCellValue('F1', 'Fecha de movimiento');
$t=2;
$buscarWo=mysqli_query($con,"SELECT * FROM `registroparcialtiempo` INNER JOIN `registro` ON registroparcialtiempo.codeBar=registro.info ");
While($row=mysqli_fetch_array($buscarWo)){
    if(strtotime($row['fechaReg'])>strtotime($today)){
    $code=$row['codeBar'];
    $qtyPar=$row['qtyPar'];
    $area=$row['area'];
    $fechaReg=$row['fechaReg'];
    $numPart=$row['NumPart'];
    $wo=$row['wo'];
    $price=$row['price'];
    $sheetMov->setCellValue('A'.$t, $numPart);
    $sheetMov->setCellValue('B'.$t, $wo);
    $sheetMov->setCellValue('C'.$t, $qtyPar);
    $sheetMov->setCellValue('D'.$t, "$ ".$price*$qtyPar);
    $sheetMov->setCellValue('E'.$t, $area);
    $sheetMov->setCellValue('F'.$t, $fechaReg);
    $t++;
        
    }
}
//venta del dia
$partes=[];
$parte=mysqli_query($con,"SELECT * FROM regsitrocalidad  WHERE fecha LIKE '$todays%' ");
foreach($parte as $part){
    $partes[]=$part['pn']; 
}
$partes=array_unique($partes);
$sheetVenta=$spreadsheet->createSheet();
$sheetVenta->setTitle('Venta del dia');
$sheetVenta->setCellValue('A1', 'Fecha');
$sheetVenta->setCellValue('B1', 'Numero de Parte ');
$sheetVenta->setCellValue('C1', 'Precio Unitario');
$sheetVenta->setCellValue('D1', 'Cantidad registrada');
$sheetVenta->setCellValue('E1', 'Total');
$t=2;
$total=0;
foreach($partes as $parte){
    $buscarInfo=mysqli_query($con,"SELECT * FROM regsitrocalidad WHERE pn='$parte' AND fecha LIKE '$todays%'");
    $numRow=mysqli_num_rows($buscarInfo);
    $buscarPrice=mysqli_query($con,"SELECT * FROM precios WHERE pn='$parte'");
    $rowPrice=mysqli_fetch_array($buscarPrice);
    $price=$rowPrice['price'];
    $client=$rowPrice['client'];
   

$sheetVenta->setCellValue('A'.$t, $today);
$sheetVenta->setCellValue('B'.$t, $parte);
$sheetVenta->setCellValue('C'.$t,'$ '. $price);
$sheetVenta->setCellValue('D'.$t, $numRow);
$sheetVenta->setCellValue('E'.$t,'$ '. $numRow*$price);
$total=$total+($numRow*$price);
$t++;
}
$sheetVenta->setCellValue('D'.$t, 'Total');
$sheetVenta->setCellValue('E'.$t,'$ '. $total);
//tiempos por arnes
/*
$sheetTiempo=$spreadsheet->createSheet();
$sheetTiempo->setTitle('Tiempo por arnes');
$sheetTiempo->setCellValue('A1', 'Part Number');



*/
//Paros Semanal
$sheet=$spreadsheet->createSheet();
$sheet->setTitle('Paros Herramentales Semanal');
$sheet->setCellValue('A1','Fecha' );
$sheet->setCellValue('B1', 'Aplicador');
$sheet->setCellValue('C1', 'Terminal');
$sheet->setCellValue('D1', 'Quien Solicito');
$sheet->setCellValue('E1', 'Atendio');
$sheet->setCellValue('F1', 'Tiempo (min) de respuesta');
$sheet->setCellValue('G1', 'Tiempo (min) de atencion'); 
$t=2;
$buscarventa=mysqli_query($con,"SELECT * FROM  registro_paro WHERE equipo='Bancos para terminales' " );
While($row=mysqli_fetch_array($buscarventa)){

    $fecha=$row['fecha'];
    $equipo=$row['nombreEquipo'];
    $quien=$row['quien'];
    $atiende=$row['atiende'];
    $ini=$row['inimant'];
    $fin=$row['finhora'];
   if(strtotime($fecha)>$lastDate)  { 
    if(strpos($equipo,'/')){
        $equipo=explode('/',$equipo);
        $herr=$equipo[0];
        $ter=$equipo[1];
    }else{
    $herr=$equipo;
    $ter="";
    }
    $sheet->setCellValue('A'.$t, $fecha );
    $sheet->setCellValue('B'.$t, $herr );
    $sheet->setCellValue('C'.$t, $ter );
    $sheet->setCellValue('D'.$t, $quien );
    $sheet->setCellValue('E'.$t, $atiende );
    if($ini!=''){
        $tiempoAten=floatval(strtotime($ini)-strtotime($fecha))/60;
    }else{
        $tiempoAten="No se Ha atendido";
    }
    
    $sheet->setCellValue('F'.$t, $tiempoAten );
    if($fin!=''){
        $tiempoFin=floatval(strtotime($fin)-strtotime($ini))/60;
    }else{
        $tiempoFin="No se Ha Completado";
    }
    $sheet->setCellValue('G'.$t, $tiempoFin );
    $t++;
    }}
//Golpes Semanal
$sheet1=$spreadsheet->createSheet(); 
$sheet1->setTitle('Golpes Semanal');
$sheet1->setCellValue('A1', 'Herrmental');
$sheet1->setCellValue('B1', 'Terminal');
$sheet1->setCellValue('C1', 'Golpes Diarios');
$t=2;
$buscarinfo=mysqli_query($con,"SELECT * FROM mant_golpes  ORDER BY id DESC");
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha_reg'];
    $herramental= $row['herramental'];
    $termianl= $row['terminal'];
    $golpesDiarios= $row['golpesDiarios'];
 
if(strtotime($fecha)>$lastDate ){   
$sheet1->setCellValue('A'.$t, $herramental);
$sheet1->setCellValue('B'.$t, $termianl);
$sheet1->setCellValue('C'.$t, $golpesDiarios);
$t++;} }  
//mantenimientos pendientes
$sheet2=$spreadsheet->createSheet(); 
$sheet2->setTitle('Mantenimiento faltante Semanal');
$sheet2->setCellValue('A1', 'Herrmental');
$sheet2->setCellValue('B1', 'Terminal');
$sheet2->setCellValue('C1', 'Numero de Mantenimientos');
$t=2;
$mant3m=strtotime(date('d-m-Y 00:00',strtotime('-3 month')));
$buscarinfo=mysqli_query($con,"SELECT * FROM mant_golpes_diarios  ORDER BY id DESC");
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha_reg'];
    $herramental= $row['herramental'];
    $termianl= $row['terminal'];
    $totalmant= $row['totalmant'];
    $mantenimiento= $row['mantenimiento'];
 
if(strtotime($fecha)<$mant3m or $mantenimiento=='falta' ){   
$sheet2->setCellValue('A'.$t, $herramental);
$sheet2->setCellValue('B'.$t, $termianl);
$sheet2->setCellValue('C'.$t, "Falta Realizar");
$t++;} }
// mantenimientos realizados en la semana
$sheet3=$spreadsheet->createSheet(); 
$sheet3->setTitle('Mantenimiento Realizados ');
$sheet3->setCellValue('A1', 'Herrmental');
$sheet3->setCellValue('B1', 'Terminal');
$sheet3->setCellValue('C1', 'Quien Realizo el Mantenimiento');
$sheet3->setCellValue('D1', 'Tiempo de Mantenimiento (min)');
$sheet3->setCellValue('E1', 'Descripción de Mantenimiento');


$t=2;
$buscarinfo=mysqli_query($con,"SELECT * FROM mant_herramental  ORDER BY id DESC");
while($row=mysqli_fetch_array($buscarinfo)){
    $fecha= $row['fecha_reg'];
    $tiempos= $row['tiempos'];
    $herramental= $row['herramental'];
    $termianl= $row['terminal'];
    $Minutos= $row['Minutos'];
    $quien= $row['quien'];
    $docMant= $row['docMant'];
    $date=$fecha." ".$tiempos;
if(strtotime($date)>$lastDate){   
$sheet3->setCellValue('A'.$t, $herramental);
$sheet3->setCellValue('B'.$t, $termianl);
$sheet3->setCellValue('C'.$t, $quien);
$sheet3->setCellValue('D'.$t, $Minutos);
$sheet3->setCellValue('E'.$t, $docMant);
$t++;} }

$sheet4=$spreadsheet->createSheet();
$sheet4->setTitle('Tiempos Muertos');
$sheet4->setCellValue('A1', 'Cliente');
$sheet4->setCellValue('B1', 'Numero de parte');
$sheet4->setCellValue('C1', 'Defectos');
$sheet4->setCellValue('D1', 'Tiempo de reparacion');
$sheet4->setCellValue('E1', 'Quien detecto');
$sheet4->setCellValue('F1', 'Responsable');
$t=2;
$buscarinfo=mysqli_query($con,"SELECT * FROM timedead WHERE area='Calidad'");
$rows=mysqli_num_rows($buscarinfo);
while($row=mysqli_fetch_array($buscarinfo)){
$fechasControl=strtotime($row['fecha'] );  
$cliente=$row['cliente'];
$np=$row['np'];
$defecto=$row['defecto'];
if($row['timeIni']!="No Aun" && $row['timeFin']!='No Aun'){
$timeIni=date("d-m-Y H:i", ($row['timeIni'] ));
$timeFin=date("d-m-Y H:i", ($row['timeFin'] ));
}else if($row['timeIni']!="No Aun" && $row['timeFin']=='No Aun'){
    $timeIni=date("d-m-Y H:i", ($row['timeIni'] ));
$timeFin="Activa";}
$total=$row['Total'];
$who=$row['whoDet'];
$respArea=$row['respArea'];
if($fechasControl>$lastDate ){    
  //  if($fechasControl>$datefin ){ 
$sheet4->setCellValue('A'.$t, $cliente);
$sheet4->setCellValue('B'.$t, $np);
$sheet4->setCellValue('C'.$t, $defecto);
$sheet4->setCellValue('D'.$t, $total);
$sheet4->setCellValue('E'.$t, $who);
$sheet4->setCellValue('F'.$t, $respArea);
$t++;}}

// Registros de desviaciones
$sheet5=$spreadsheet->createSheet();
$sheet5->setTitle('Desviaciones');
$sheet5->setCellValue('A1', 'Cliente');
$sheet5->setCellValue('B1', 'Supervisor');
$sheet5->setCellValue('C1', 'Numero de parte afectado');
$sheet5->setCellValue('D1', 'Pieza original');
$sheet5->setCellValue('E1', 'Sustituto');
$sheet5->setCellValue('F1', 'Cantidad');
$sheet5->setCellValue('G1', 'Razon');
$sheet5->setCellValue('H1', 'Accion preventiva');
$sheet5->setCellValue('I1', 'Estats');
$sheet5->setCellValue('J1', 'Si fue negada (Razon)');
$t=2;
$buscarventa=mysqli_query($con,"SELECT * FROM  desvation  " );
While($row=mysqli_fetch_array($buscarventa)){

    $fecha=$row['fecha'];
    $client=$row['cliente'];
    $supervisor=$row['quien'];
    $pn=$row['Mafec'];
    $original=$row['porg'];
    $sustituted=$row['psus'];
    $quantity=$row['clsus'];
    $reason=$row['Causa'];
    $action=$row['accion'];
    $status=$row['count'];
    $rejection=$row['rechazo'];
    if(strtotime($fecha)>$lastDate ){
    $sheet5->setCellValue('A'.$t, $client );
    $sheet5->setCellValue('B'.$t, $supervisor );
    $sheet5->setCellValue('C'.$t, $pn );
    $sheet5->setCellValue('D'.$t, $original );
    $sheet5->setCellValue('E'.$t, $sustituted );
    $sheet5->setCellValue('F'.$t, $quantity );
    $sheet5->setCellValue('G'.$t, $reason );
    $sheet5->setCellValue('H'.$t, $action );
    if($status==4){
        $sheet5->setCellValue('I'.$t, "Aproved" );
        $sheet5->setCellValue('J'.$t, "N/A" );
    }else if($status==5){
        $sheet5->setCellValue('I'.$t, "Rejected" );
        $sheet5->setCellValue('J'.$t, $rejection );
    }else{
        $sheet5->setCellValue('I'.$t, "Pending" );
        $sheet5->setCellValue('J'.$t, 'N/A' );
    }        

    $t++;
    }}




$week = date('W');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte General ' . $todays . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


