<?php
$host = "localhost";
$user = "pcadmin";
$clave = "SupAdmin1212";
$bd = "trazabilidad";
$con = mysqli_connect($host, $user, $clave, $bd);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

class registro    {
        public $parte;
        public function select($con,$from,$where){
            $select=mysqli_query($con,"SELECT * FROM $from WHERE $where");
            $maxrow=mysqli_num_rows($select);
            $i=0;
            while($row=mysqli_fetch_array($select)){
                $parte=$row['NumPart'];
                $qty=$row['Qty'];
                $po=$row['po'];
                $wo=$row['wo'];
                $paro=$row['paro'];
                $count=$row['count'];
                $donde=$row['donde'];
                $rev=$row['rev'];

                $this->parte[$i]=[$parte,$wo,$po,$qty,$rev,$count,$paro,$donde];
                $i++;     }
            $this->maxrow=$maxrow;       }
        public function update($con,$from,$set,$where){
            $up=mysqli_query($con,"UPDATE $from SET $set WHERE $where");
        }
     
        public function getrows(){
            return $this->maxrow;
        }
        public function getInfo($i,$j){
    
            return $this->parte[$i][$j];
            
        }


    }


class corte{
    public $codigo;
    public function select($con,$from,$where){
        $buscar=mysqli_query($con,"SELECT * FROM $from WHERE $where");
        $maxrow=mysqli_num_rows($buscar);
        $i=0;
        while($row=mysqli_fetch_array($buscar)){
            $pn=$row['np'];
            $client=$row['cliente'];
            $wo=$row['wo'];
            $cons=$row['cons'];
            $tipo=$row['tipo'];
            $aws=$row['aws'];
            $color=$row['color'];
           $codigo=$row['codigo'];
           $term1=$row['term1'];
           $term2=$row['term2'];
           $id=$row['id'];
           $dataFrom=$row['dataFrom'];
           $dataTo=$row['dataTo'];
           $qty=$row['qty'];
           $this->codigo[$i]=[$pn,$client,$wo,$cons,$tipo,$aws,$color,$codigo,$term1,$term2,$id,$dataFrom,$dataTo,$qty];
   $i++;     }
        $this->maxrow=$maxrow;    }
    public function maxRow(){
        return $this->maxrow;
    }    
    public function getrow($i,$j){
        return $this->codigo[$i][$j];

    }
        
}    
