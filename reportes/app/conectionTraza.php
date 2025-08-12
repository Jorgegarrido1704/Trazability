<?php 
//conection to DB
$host='127.0.0.1';
$user='pcadmin';
$pass='SupAdmin1212';
$db ='trazabilidad';
$con=mysqli_connect($host,$user,$pass,$db);

date_default_timezone_set("America/Mexico_City");

//create a object to get function on DB
class conectionTraza{
  public $what;
  public $from;
  public $where;
  public $datos;
  public $parte;
  
public function __construct($what,$from,$where,$datos){
    $this->what=$what;
    $this->from=$from;
    $this->where=$where;
    $this->datos=$datos;

}
    
//Follow the selection of data
function select($con){
  $what=$this->what;
  $from=$this->from;
  $where=$this->where;
  $datos=$this->datos;
  $j=0;
    $select=mysqli_query($con,"SELECT $what FROM $from WHERE $where");
    $numrows=mysqli_num_rows($select);
    while($row=mysqli_fetch_array($select)){
        for($i=0;$i<count($datos);$i++){
            $this->parte[$j][$i]=$row[$datos[$i]];
        }
        $j++;
    }
   
   
    $this->numrows=$numrows;
    
}
function getRows(){
    return $this->numrows;
}
//getDatos
function getData($i,$j){
    return $this->parte[$i][$j];
}


}