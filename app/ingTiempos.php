<?php
require "conection.php";
require 'vendor/autoload.php'; 

date_default_timezone_set("America/Mexico_City");
$datefin = strtotime(date("d-m-Y 23:59"));
$dateIni = strtotime(date("d-m-Y 00:00"));

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Ingeniero');
$sheet->setCellValue('B1', 'Fecha de inicio');
$sheet->setCellValue('C1', 'Fecha de fin');
$sheet->setCellValue('D1', 'Tiempo de actividad');
$sheet->setCellValue('E1', 'Actividad');
$sheet->setCellValue('F1', 'Descripción de la actividad');

$t = 2;
$buscarinfo = mysqli_query($con, "SELECT * FROM ingactividades ORDER BY Id_request DESC");

while ($row = mysqli_fetch_array($buscarinfo)) {
    $fecha = $row['fecha'];
    $quien = $row['Id_request'];
    $fint = $row['finT'];
    $actividad = $row['actividades'];
    $desciption = $row['desciption'];
    $tiempo = floatval(strtotime($fint) - strtotime($fecha)) / 60;

    if ($dateIni < strtotime($fecha) && $tiempo > 0) {
        $sheet->setCellValue('A' . $t, $quien);
        $sheet->setCellValue('B' . $t, $fecha);
        $sheet->setCellValue('C' . $t, $fint);
        $sheet->setCellValue('D' . $t, $tiempo);
        $sheet->setCellValue('E' . $t, $actividad);
        $sheet->setCellValue('F' . $t, $desciption);
        $t++;
    }
}

// Create the data series for the chart
$dataSeriesLabels = [
    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$1', null, 1),
];

$xAxisTickValues = [
    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$' . ($t-1), null, ($t-2)),
];

$dataSeriesValues = [
    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$2:$D$' . ($t-1), null, ($t-2)),
];

// Build the data series
$series = new DataSeries(
    DataSeries::TYPE_BARCHART, // chart type
    DataSeries::GROUPING_STANDARD, // chart grouping
    range(0, count($dataSeriesValues) - 1), // plotOrder
    $dataSeriesLabels, // plotLabel
    $xAxisTickValues, // plotCategory
    $dataSeriesValues // plotValues
);

// Set additional parameters for the data series
$series->setPlotDirection(DataSeries::DIRECTION_COL);

// Create a layout for the chart
$layout = new Layout();
$layout->setShowVal(true);

// Create the plot area for the chart
$plotArea = new PlotArea($layout, [$series]);

// Create the legend for the chart
$legend = new Legend(Legend::POSITION_RIGHT, null, false);

// Create the title for the chart
$title = new Title('Tiempo de Actividad por Ingeniero');

// Create the chart
$chart = new Chart(
    'chart1', // name
    $title, // title
    $legend, // legend
    $plotArea, // plotArea
    true, // plotVisibleOnly
    DataSeries::EMPTY_AS_GAP, // displayBlanksAs
    null, // xAxisLabel
    null // yAxisLabel
);

// Set the position where the chart should appear in the worksheet
$chart->setTopLeftPosition('H2');
$chart->setBottomRightPosition('P20');

// Add the chart to the worksheet
$sheet->addChart($chart);

// Output the spreadsheet with the chart
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Engineering_Activities.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->setIncludeCharts(true); // Include charts in the output file
$writer->save('php://output');
exit();
?>
