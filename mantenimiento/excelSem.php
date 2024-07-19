<?php
require "../app/conection.php";
require '../vendor/autoload.php';
date_default_timezone_set("America/Mexico_City");
$lastDate=strtotime(date('d-m-Y 00:00',strtotime('-1 week')));

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Paros Semanal');
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

    //golpes

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
$sheet2->setCellValue('C'.$t, $totalmant);
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

/*
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Ventas-Articulos');
    $sheet2->setCellValue('A1','Fecha' );
    $sheet2->setCellValue('B1', 'Cliente');
    $sheet2->setCellValue('C1', 'Cuenta');
    $sheet2->setCellValue('D1', 'Articulo');
    $sheet2->setCellValue('E1', 'precio');
    $sheet2->setCellValue('F1', 'Saldo');
    
    
    $t=2;
    
    $buscarventa=mysqli_query($con,"SELECT * FROM  venta");
    While($row=mysqli_fetch_array($buscarventa)){
        $fecha=$row['fecha'];
        $cliente=$row['cliente'];
        $cuenta=$row['cuenta'];
        $total=$row['articulo'];
        $enganche=$row['precio'];
        $saldo=$row['saldo'];
          
        $sheet2->setCellValue('A'.$t, $fecha );
        $sheet2->setCellValue('B'.$t, $cliente );
        $sheet2->setCellValue('C'.$t, $cuenta );
        $sheet2->setCellValue('D'.$t, $total );
        $sheet2->setCellValue('E'.$t, $enganche );
        $sheet2->setCellValue('F'.$t, $saldo );
        $t++;
    }
    $sheet3 = $spreadsheet->createSheet();
    $sheet3->setTitle('Existencias');
    $sheet3->setCellValue('A1','Categoria' );
    $sheet3->setCellValue('B1', 'Producto');
    $sheet3->setCellValue('C1', 'Cantidad');
   
    
    
    $t=2;
    
    $buscarventa=mysqli_query($con,"SELECT * FROM  inventario");
    While($row=mysqli_fetch_array($buscarventa)){
        $fecha=$row['category'];
        $cliente=$row['product'];
        $cuenta=$row['qty'];
      
          
        $sheet3->setCellValue('A'.$t, $fecha );
        $sheet3->setCellValue('B'.$t, $cliente );
        $sheet3->setCellValue('C'.$t, $cuenta );
      
        $t++;
    }
    $sheet4 = $spreadsheet->createSheet();
    $sheet4->setTitle('ENtradas - Salidad ');
    $sheet4->setCellValue('A1','Fecha' );
    $sheet4->setCellValue('B1', 'Producto');
    $sheet4->setCellValue('C1', 'Cantidad');
    $sheet4->setCellValue('D1', 'Concepto');  
     $t=2;
    $buscarventa=mysqli_query($con,"SELECT * FROM  ensal");
    While($row=mysqli_fetch_array($buscarventa)){
        $fecha=$row['fecha'];
        $cliente=$row['producto'];
        $cuenta=$row['cantidad'];
        $total=$row['concepto'];
        $sheet4->setCellValue('A'.$t, $fecha );
        $sheet4->setCellValue('B'.$t, $cliente );
        $sheet4->setCellValue('C'.$t, $cuenta );
        $sheet4->setCellValue('D'.$t, $total );
        $t++;
    }
    $sheet5 = $spreadsheet->createSheet();
    $sheet5->setTitle('Cancelaciones');
    $sheet5->setCellValue('A1','Fecha' );
    $sheet5->setCellValue('B1', 'cuenta');
    $sheet5->setCellValue('C1', 'articulo');
    $sheet5->setCellValue('D1', 'motivo');
    $t=2;
    $buscarventa=mysqli_query($con,"SELECT * FROM  cancelaciones");
    While($row=mysqli_fetch_array($buscarventa)){
        $fecha=$row['fecha'];
        $cliente=$row['cuenta'];
        $cuenta=$row['articulo'];
        $total=$row['motivo'];          
        $sheet5->setCellValue('A'.$t, $fecha );
        $sheet5->setCellValue('B'.$t, $cliente );
        $sheet5->setCellValue('C'.$t, $cuenta );
        $sheet5->setCellValue('D'.$t, $total );
        $t++;
    }
    $sheet6 = $spreadsheet->createSheet();
    $sheet6->setTitle('Bonificaciones');
    $sheet6->setCellValue('A1','Fecha' );
    $sheet6->setCellValue('B1', 'cuenta');
    $sheet6->setCellValue('C1', 'Articulo');
    $sheet6->setCellValue('D1', 'precio compra');
    $sheet6->setCellValue('E1', 'precio Bonificacion');
    $sheet6->setCellValue('F1', 'Bonificacion');
    $t=2;
    $buscarventa=mysqli_query($con,"SELECT * FROM  bonif");
    While($row=mysqli_fetch_array($buscarventa)){
        $fecha=$row['fecha'];
        $cliente=$row['cuenta'];
        $cuenta=$row['art'];
        $total=$row['precioAnt'];
        $enganche=$row['PrecioBon'];
        $saldo=$row['ahorro'];
        $sheet6->setCellValue('A'.$t, $fecha );
        $sheet6->setCellValue('B'.$t, $cliente );
        $sheet6->setCellValue('C'.$t, $cuenta );
        $sheet6->setCellValue('D'.$t, $total );
        $sheet6->setCellValue('E'.$t, $enganche );
        $sheet6->setCellValue('F'.$t, $saldo );
        $t++;
    }
    $sheet7 = $spreadsheet->createSheet();
    $sheet7->setTitle('Abonos');
    $sheet7->setCellValue('A1','Fecha' );
    $sheet7->setCellValue('B1', 'Cliente');
    $sheet7->setCellValue('C1', 'Cuenta');
    $sheet7->setCellValue('D1', 'Abono');
    $sheet7->setCellValue('E1', 'No. Recibo');
    $t=2;
    $buscarventa=mysqli_query($con,"SELECT * FROM  abonos");
    While($row=mysqli_fetch_array($buscarventa)){
        $fecha=$row['fechab'];
        $cliente=$row['client'];
        $cuenta=$row['cuenta'];
        $total=$row['abono'];
        $enganche=$row['NoRec'];          
        $sheet7->setCellValue('A'.$t, $fecha );
        $sheet7->setCellValue('B'.$t, $cliente );
        $sheet7->setCellValue('C'.$t, $cuenta );
        $sheet7->setCellValue('D'.$t, $total );
        $sheet7->setCellValue('E'.$t, $enganche );
        
        $t++;
    }
    $sheet8 = $spreadsheet->createSheet();
    $sheet8->setTitle('Clientes');
    $sheet8->setCellValue('A1','Cliente' );
    $sheet8->setCellValue('B1', 'domicilio');
    $sheet8->setCellValue('C1', 'Colonia');
    $sheet8->setCellValue('D1', 'Pariente');
    $sheet8->setCellValue('E1', 'Aval');
    $sheet8->setCellValue('F1','Domicilio Aval' );
    $sheet8->setCellValue('G1', 'Referencia 1');
    $sheet8->setCellValue('H1', 'Domicilio Referencia 1');
    $sheet8->setCellValue('I1', 'Referencia 2');
    $sheet8->setCellValue('J1', 'Domicilio Referencia 2');
    $t=2;
    $buscarventa=mysqli_query($con,"SELECT DISTINCT * FROM  historico");
    While($row=mysqli_fetch_array($buscarventa)){
        $cliente=$row['cliente'];
        $dom=$row['domcli'];
        $col=$row['col'];
        $aval=$row['aval'];
        $domAval=$row['domaval'];
        $pariente=$row['espo'];
        $ref1=$row['ref1'];
        $domref1=$row['domref1'];
        $ref2=$row['ref2'];
        $domref2=$row['domre2'];          
        $sheet8->setCellValue('A'.$t, $cliente );
        $sheet8->setCellValue('B'.$t, $dom );
        $sheet8->setCellValue('C'.$t, $col );
        $sheet8->setCellValue('D'.$t, $pariente );
        $sheet8->setCellValue('E'.$t, $aval );
        $sheet8->setCellValue('F'.$t, $domAval );
        $sheet8->setCellValue('G'.$t, $ref1 );
        $sheet8->setCellValue('H'.$t, $domref1 );
        $sheet8->setCellValue('I'.$t, $ref2 );
        $sheet8->setCellValue('J'.$t, $domref2 );
        $t++;
    }

    $buscarRuta=mysqli_query($con,"SELECT DISTINCT ruta FROM  venta");
    while($row=mysqli_fetch_array($buscarRuta)){
        $ruta=$row['ruta'];
        
    
    $sheet8 = $spreadsheet->createSheet();
    $sheet8->setTitle('Ruta'.$ruta);
    $sheet8->setCellValue('A1','Cantidad de articulos' );
    $sheet8->setCellValue('B1', 'Articulo');
    $sheet8->setCellValue('C1', 'Precio');
    $sheet8->setCellValue('D1', 'enganche');
    $sheet8->setCellValue('E1', 'Mensualidades');
    $sheet8->setCellValue('F1','Pago semanal' );
    $sheet8->setCellValue('G1', 'Cliente');
    $sheet8->setCellValue('H1', 'Numero de Cuenta');
    $sheet8->setCellValue('I1', 'Fecha de Compra');
    $t=2;
    $buscarventa=mysqli_query($con,"SELECT  * FROM  venta WHERE ruta='$ruta'");
    While($row=mysqli_fetch_array($buscarventa)){

        $articulo=$row['articulo'];
        $precio=$row['precio'];
        $enganche=$row['enganche'];
        $semanal=$row['semanal'];
        $meses=$row['meses'];
        $cliente=$row['cliente'];
       $numCuenta=$row['cuenta'];    
       $fecha=$row['fecha'];     
        $sheet8->setCellValue('A'.$t, '1');
        $sheet8->setCellValue('B'.$t, $articulo );
        $sheet8->setCellValue('C'.$t, $precio );
        $sheet8->setCellValue('D'.$t, $enganche );
        $sheet8->setCellValue('E'.$t, $meses );
        $sheet8->setCellValue('F'.$t, $semanal );
        $sheet8->setCellValue('G'.$t, $cliente );
        $sheet8->setCellValue('H'.$t, $numCuenta );
        $sheet8->setCellValue('I'.$t, $fecha );
        $t++;
    }  
    }

    
    $buscarRuta=mysqli_query($con,"SELECT DISTINCT promotor FROM  venta WHERE promotor!=''");
    while($row=mysqli_fetch_array($buscarRuta)){
        $ruta=$row['promotor'];
        $datos=explode(" ", $ruta);
        $name=$datos[0]." ".$datos[1];       
    
    $sheet8 = $spreadsheet->createSheet();
    $sheet8->setTitle('Prom. '.$name);
    $sheet8->setCellValue('A1','Cantidad de articulos' );
    $sheet8->setCellValue('B1', 'Articulo');
    $sheet8->setCellValue('C1', 'Precio');
    $sheet8->setCellValue('D1', 'enganche');
    $sheet8->setCellValue('E1', 'Mensualidades');
    $sheet8->setCellValue('F1','Pago semanal' );
    $sheet8->setCellValue('G1', 'Cliente');
    $sheet8->setCellValue('H1', 'Numero de Cuenta');
    $sheet8->setCellValue('I1', 'Fecha de Compra');
    $t=2;
    $buscarventa=mysqli_query($con,"SELECT  * FROM  venta WHERE promotor='$ruta'");
    While($row=mysqli_fetch_array($buscarventa)){

        $articulo=$row['articulo'];
        $precio=$row['precio'];
        $enganche=$row['enganche'];
        $semanal=$row['semanal'];
        $meses=$row['meses'];
        $cliente=$row['cliente'];
       $numCuenta=$row['cuenta'];    
       $fecha=$row['fecha'];     
        $sheet8->setCellValue('A'.$t, '1');
        $sheet8->setCellValue('B'.$t, $articulo );
        $sheet8->setCellValue('C'.$t, $precio );
        $sheet8->setCellValue('D'.$t, $enganche );
        $sheet8->setCellValue('E'.$t, $meses );
        $sheet8->setCellValue('F'.$t, $semanal );
        $sheet8->setCellValue('G'.$t, $cliente );
        $sheet8->setCellValue('H'.$t, $numCuenta );
        $sheet8->setCellValue('I'.$t, $fecha );
        $t++;
    }  
    }
    
    $buscarRuta=mysqli_query($con,"SELECT DISTINCT vendedor FROM  venta WHERE vendedor!=''");
    while($row=mysqli_fetch_array($buscarRuta)){
        $ruta=$row['vendedor'];
        $datos=explode(" ", $ruta);
        $name=$datos[0]." ".$datos[1];    
        
    
    $sheet8 = $spreadsheet->createSheet();
    $sheet8->setTitle('Vend '.$name);
    $sheet8->setCellValue('A1','Cantidad de articulos' );
    $sheet8->setCellValue('B1', 'Articulo');
    $sheet8->setCellValue('C1', 'Precio');
    $sheet8->setCellValue('D1', 'enganche');
    $sheet8->setCellValue('E1', 'Mensualidades');
    $sheet8->setCellValue('F1','Pago semanal' );
    $sheet8->setCellValue('G1', 'Cliente');
    $sheet8->setCellValue('H1', 'Numero de Cuenta');
    $sheet8->setCellValue('I1', 'Fecha de Compra');
    $t=2;
    $buscarventa=mysqli_query($con,"SELECT  * FROM  venta WHERE vendedor='$ruta'");
    While($row=mysqli_fetch_array($buscarventa)){

        $articulo=$row['articulo'];
        $precio=$row['precio'];
        $enganche=$row['enganche'];
        $semanal=$row['semanal'];
        $meses=$row['meses'];
        $cliente=$row['cliente'];
       $numCuenta=$row['cuenta'];    
       $fecha=$row['fecha'];     
        $sheet8->setCellValue('A'.$t, '1');
        $sheet8->setCellValue('B'.$t, $articulo );
        $sheet8->setCellValue('C'.$t, $precio );
        $sheet8->setCellValue('D'.$t, $enganche );
        $sheet8->setCellValue('E'.$t, $meses );
        $sheet8->setCellValue('F'.$t, $semanal );
        $sheet8->setCellValue('G'.$t, $cliente );
        $sheet8->setCellValue('H'.$t, $numCuenta );
        $sheet8->setCellValue('I'.$t, $fecha );
        $t++;
    }  
    }

*/
$week = date('W');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte Semenal herramentales  WEEK: ' . $week . '.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


