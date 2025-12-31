<?php 
require '../app/conection.php';
$employeesVacations=array();
try {
    $vacLasrYear=mysqli_query($con,"SELECT employeeName,DaysVacationsAvailble, lastYear,currentYear,nextYear FROM personalberg ORDER BY typeWorker  DESC");
    echo "<table border='1'>";
    while($row = mysqli_fetch_array($vacLasrYear)){
        echo "<tr>";
        echo "<td>".$row['employeeName']."</td>";
       
        echo "<td>".$row['lastYear']."</td>";
        echo "<td>".$row['currentYear']."</td>";
        echo "<td>".$row['nextYear']."</td>";
        echo "</tr>";
        $employeesVacations[$row['employeeName']] = array(
            
            'lastYear' => $row['lastYear']+$row['currentYear'],
            'currentYear' => $row['nextYear'],
            'nextYear' => 0
        );

    }
    echo "</table>";
    /*foreach ($employeesVacations as $employeeName => $vacations) {
        $updateVacations=mysqli_query($con,"UPDATE personalberg SET lastYear=".$vacations['lastYear'].", currentYear=".$vacations['currentYear'].", nextYear=".$vacations['nextYear']." WHERE employeeName='".$employeeName."'");
    }*/
        echo "<table border='1'>";
        foreach ($employeesVacations as $employeeName => $vacations) {
            echo "<tr>";
            echo "<td>".$employeeName."</td>";
            echo "<td>".$vacations['lastYear']."</td>";
            echo "<td>".$vacations['currentYear']."</td>";
            echo "<td>".$vacations['nextYear']."</td>";
            echo "</tr>";
        }
        echo "</table>";
        if(date('m-d')=='12-32'){
            foreach ($employeesVacations as $employeeName => $vacations) {
                $updateVacations=mysqli_query($con,"UPDATE personalberg SET lastYear=".$vacations['lastYear'].", currentYear=".$vacations['currentYear'].", nextYear=".$vacations['nextYear']." WHERE employeeName='".$employeeName."'");
            }
        }
      
} catch (Exception $th) {echo $th->getMessage();}