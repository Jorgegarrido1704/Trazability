<?php
require '../app/conection.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$partes = $vuelta= 0;
$i = 1;  // Start from row 1 to allow header row if needed
$date = date('Y-m-d'); // Add current date for file name

$buscarReportes = mysqli_query($con, "SELECT * FROM timeprocess WHERE registrado='No Aun' ORDER BY partnum ASC");

while ($row = mysqli_fetch_array($buscarReportes)) {
    $part = $row['partnum'];
    $subProcess = $row['subProcess'];
    $laps = $row['laps'];
    $mm = $row['mm'];
    $obs = $row['obs'];
    $id=$row['id_tp'];

    // Check for partnum change to create a new sheet if necessary
    if ($partes == "") {
        $sheet->setTitle($part);
    } elseif ($partes == $part) {
        $i += 5;  // Increment by 5 rows if part is the same
        $vuelta=0;
    } else {
        // Create a new sheet if partnum changes
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle($part);
        $i = 1;  // Reset row counter when creating a new sheet
        $vuelta=0;
    }

    // Add Part Number and SubProcess to the sheet
    $sheet->setCellValue('A' . $i, "Part Number: ");
    $sheet->setCellValue('B' . $i, $part);
    $sheet->setCellValue('C' . $i, "Sub Process: ");
    $sheet->setCellValue('D' . $i, $subProcess);
    $sheet->setCellValue('F' . $i, "MM: ");
    $sheet->setCellValue('G' . $i, $mm);
    $i++;  // Move to the next row




    // Add Laps information to the sheet
    $sheet->setCellValue('A' . $i, "Laps: ");
    $partLap = explode("-", $laps);
    $b = $i;  // Store the row number for merging cells later
    foreach ($partLap as $lap) {
        $registro=explode(":",$lap);
        $tiempo=($registro[0]*60)+ $registro[1]+($registro[2]/1000);
        $sheet->setCellValue('B' . $i, $lap);
        $sheet->setCellValue('C' . $i, ($tiempo-$vuelta));
        $vuelta=$tiempo;
        $i++;  // Move to the next row for each lap
    }

    // Merge cells for the observation part
    $i++;  // Add space between laps and observations
    $sheet->mergeCells('D' . $b . ':E' . $i);  // Merge cells for observations
    $sheet->setCellValue('D' . $b, "Observations: " . $obs);

    // Store the current part for the next iteration
    $partes = $part;
    
$update=mysqli_query($con,"UPDATE `timeprocess` SET `registrado`='Registrado' WHERE `id_tp`='$id' ");
}
// Set headers to prompt the user to download the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="tiempos '. $date . '.xlsx"');
header('Cache-Control: max-age=0');

// Write the file to output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
?>
