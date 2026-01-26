<?php
 require "../app/conection.php";
    $terminals=[];



    $terminalesCom=mysqli_query($con,"SELECT terminal1,terminal2 from listascorte 
        ORDER BY terminal1 DESC");
    while($row=mysqli_fetch_assoc($terminalesCom)){
        $term1=explode(" ",$row['terminal1'])[0];
        $term2=explode(" ",$row['terminal2'])[0];
    //count of terminals

    if(!array_key_exists($term1,$terminals)){
        $terminals[$term1]=1;
    }else{
        $terminals[$term1]++;
    }
    if(!array_key_exists($term2,$terminals)){
        $terminals[$term2]=1;
    }else{
        $terminals[$term2]++;
    }
       
   
    }
    arsort($terminals);
     foreach($terminals as  $term=>$value){
        echo $term. "|cantidad:|"  .$value."<br>";
    }