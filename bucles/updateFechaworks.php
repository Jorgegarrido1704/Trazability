    <?php 

require "../app/conection.php";

$selection=mysqli_query($con,"SELECT pn,WorkRev FROM `workschedule` ");
while($row = mysqli_fetch_array($selection)){
    $pn = $row['pn'];
    $rev = $row['WorkRev'];
    echo "Part Number: $pn, Revision: $rev <br>";
    // Check if the part number exists in the registro table
    $buscar = mysqli_query($con, "SELECT pn,rev,fecha FROM `po` WHERE `pn` = '$pn' AND (`rev` LIKE 'PPAP%' OR `rev` LIKE 'PRIM%') ");
    while($rows = mysqli_fetch_array($buscar)){
        $np = $rows['pn'];
        $rev = $rows['rev'];
        $fecha = $rows['fecha'];
       echo "t  Part Number: $np, Revision: $rev , Fecha: $fecha<br>"; 
       
}

}