<?php

require '../app/conection.php';


$buscar = mysqli_query($con, "SELECT * FROM `registro` WHERE `wo` = '$_POST[wo]'");