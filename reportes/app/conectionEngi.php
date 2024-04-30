<?php 
//conection to DB
$host='localhost';
$user='pcadmin';
$pass='SupAdmin1212';
$db ='trazabilidad';
$con=mysqli_connect($host,$user,$pass,$db);
date_default_timezone_set("America/Mexico_City");

class desviacion{
    
    public $what;
    public $from;
    public $where;
    public $parte;

    public $datos;

    public function __construct($what,$from,$where,$datos){
        $this->what=$what;
        $this->from=$from;
        $this->where=$where;
        $this->datos=$datos;
      
    }

    function select($con){
        $what=$this->what;
        $from=$this->from;
        $where=$this->where;
        $datos=$this->datos;
        $last=604800;
        $today=strtotime(date("d-m-y"));
        $lastweek=$today-$last;
        $j=0;

        
        $select=mysqli_query($con,"SELECT $what FROM $from WHERE $where");
        $numrows=mysqli_num_rows($select);
        $this->numRows=$numrows;
        while($row=mysqli_fetch_array($select)){
            
            $Datetime=strtotime($row[$datos[1]]);
            echo $Datetime;
            
            for($i=0;$i<count($datos);$i++){
              $this->parte[$j][$i]=$row[$datos[$i]];
               
            }
            $j++;
        }
    }
    function getRows(){
        return $this->numRows;
    }
    function getinfo($j,$i){
        return $this->parte[$j][$i];
    }

}