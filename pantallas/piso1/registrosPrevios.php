<?php
require "../../app/conection.php";
$registros=[
['lider'=>'OLAES FRAGA JUAN JOSE','zona'=>'Corte','count'=>'count = 2 or count = 3 or count = 15'],
['lider'=>'OLAES FRAGA JUAN JOSE','zona'=>'Terminales','count'=>'count = 4 or count = 5'],
['lider'=>'VILLALPANDO RODRIGUEZ DAVID','zona'=>'Ensamble','count'=>'count = 6 or count = 7'],
['lider'=>'VILLALPANDO RODRIGUEZ DAVID','zona'=>'Loom','count'=>'count = 8 or count = 9'],
['lider'=>'CERVERA LOPEZ JOSE DE JESUS','zona'=>'Nuevos productos','count'=>'count = 16 or count = 13 or count = 14 or
count = 17 or count = 18 or count = 19'],
];

$index = rand(0, count($registros) - 1);
$count = $registros[$index]['count'];
$tablaBody = "";
$tableRows = "";
$datos=[];
$datos['zona']=$registros[$index]['zona'];
$datos['lider']=$registros[$index]['lider'];

$query = "SELECT cliente, wo, NumPart, Qty, reqday
FROM registro
WHERE $count
ORDER BY STR_TO_DATE(reqday, '%d-%m-%Y') ASC, cliente ASC
LIMIT 15;";
$result = mysqli_query($con, $query);
while ($row = mysqli_fetch_assoc($result)) {
$color = "";
$dayNow = date("d-m-Y");
$reqday = $row['reqday'];
if(strtotime($dayNow) > strtotime($reqday)){
$color = "table-danger";
}else if (strtotime("+7 day", strtotime($reqday)) <= strtotime($reqday) ) { $color="table-warning" ; } 
else {
    $color="table-success" ; }
     $tableRows .=    "<tr class='$color'>" ; 
     $tableRows .= "<td class='text-center'>" . $row['cliente'] . "</td>";
      $tableRows .= "<td class='text-center'>" . $row['NumPart'] . "</td>" ; 
      $tableRows .=          "<td class='text-center'>" . $row['wo']. "</td>" ; 
      $tableRows .=  "<td class='text-center'>" . $row['Qty'] . "</td>" ; 
    $tableRows .= "<td class='text-center'>" .
    $row['reqday'] . "</td> </tr>" ; }
    
    $datos['tableRows']=$tableRows;

echo json_encode($datos);
?>