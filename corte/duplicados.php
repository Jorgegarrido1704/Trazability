<?php
require "../app/conection.php";

$numerosDeParte = [];
if (isset($_GET['np'])) {
    $numerosDeParte = explode(',', $_GET['np']);
}
$consAnt='';
$idAnt=0;
foreach ($numerosDeParte as  $np) {
    $selctions=mysqli_query($con,"SELECT cons,id FROM listascorte WHERE pn='$np' order by cons desc , id desc");
    if(mysqli_num_rows($selctions)>0){
        while($row=mysqli_fetch_array($selctions)){
            
            $cons=$row['cons'];
            $id=$row['id'];
            if($consAnt==$cons and $idAnt>$id){
                $delete=(mysqli_query($con,"DELETE FROM listascorte WHERE id='$id'"));
            }
            $consAnt=$cons;
            $idAnt=$id;
            
        }
    }
}

header("location:routing/cortes.php?np=" . implode(',', $numerosDeParte));