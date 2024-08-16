<?php

require "conection.php";
require '../../app/vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$ides=isset($_POST['ides']) ? $_POST['ides'] : "";

if($ides != ""){


        $archivo="../MANTENIMIENTOESX.xlsx";
        $spreadsheet = IOFactory::load($archivo);
        $sheet = $spreadsheet->getActiveSheet();
        $buscarinfo=mysqli_query($con,"SELECT * FROM registro_mant WHERE id=$ides");
        while($row=mysqli_fetch_array($buscarinfo)){
           
        $sheet->setCellValue('J3', $row['id_maquina']);
        $sheet->setCellValue('J4', $row['area']);
        $sheet->setCellValue('J6', $row['id']);
        if($row['tipoMant']=="MAQUINARIA"){
            $sheet->setCellValue('C6', 'X');
        }else if($row['tipoMant']=="SISTEMAS DE INFORMACION"){
            $sheet->setCellValue('C7', 'X');
       }   else if($row['tipoMant']=="ESTRUCTURAS Y PLANTA"){
        $sheet->setCellValue('E6', 'X');
        }    else if($row['tipoMant']=="PREVENTIVO"){
            $sheet->setCellValue('E7', 'X');
       }   else if($row['tipoMant']=="PRUEBA ELECTRICA"){
        $sheet->setCellValue('G6', 'X');
        }    else if($row['tipoMant']=="CORRECTIVO"){
            $sheet->setCellValue('G7', 'X');
        }
        if($row['periMant']=="UNA VEZ"){
            $sheet->setCellValue('E11', 'X');
        }else if($row['periMant']=="SEMANAL"){
            $sheet->setCellValue('C10', 'X');
       }   else if($row['periMant']=="MENSUAL"){
        $sheet->setCellValue('E10', 'X');
        }      else if($row['periMant']=="TRIMESTRAL"){
        $sheet->setCellValue('G10', 'X');
        }    else if($row['periMant']=="SEMESTRAL"){
            $sheet->setCellValue('C11', 'X');
        }  else if($row['periMant']=="ANUAL"){
            $sheet->setCellValue('G11', 'X');
        }
        $sheet->setCellValue('A16', $row['descTrab']);
        $sheet->setCellValue('F16', $row['equipo']);
        $sheet->setCellValue('H16', $row['estatus']);
        $sheet->setCellValue('I16', $row['comentarios']);
        $sheet->setCellValue('C31', $row['fechReq']);
        $sheet->setCellValue('C32', $row['horaIniServ']);
        $sheet->setCellValue('E32', $row['horaFinServ']);
        $sheet->setCellValue('H31', $row['fechReq']);
        $sheet->setCellValue('H32', $row['ttServ']);

        $sheet->setCellValue('A34', $row['solPor']);
        $sheet->setCellValue('C34', $row['SupMant']);
        $sheet->setCellValue('F34', $row['tecMant']);
        $sheet->setCellValue('I34', $row['ValGer']);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Mantenimiento folio: ' .$ides.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
        
        if(exit()){
            header("location:pendientes.php");
        }
    }
}
?>