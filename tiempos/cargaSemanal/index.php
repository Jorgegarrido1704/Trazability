<?php 

$localhost = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'trazabilidad';

// Create connection
$con = new mysqli($localhost, $username, $password, $database);
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

date_default_timezone_set("America/Mexico_City");

$carga =[
['Bergstrom','1000109371',100],
['Bergstrom','1000230212',7],
['Bergstrom','1000312635',200],
['Bergstrom','1000745097',10],
['Bergstrom','1000873021',100],
['Bergstrom','1000873025',100],
['Bergstrom','1001488939',993],
['Bergstrom','1001489409',834],
['Bergstrom','1001752828',200],
['Bergstrom','1002180286',167],
['Bergstrom','1002618213',100],
['Bergstrom','1002707335',50],
['Bergstrom','1002835044',200],
['Bergstrom','1002961733',5],
['Bergstrom','1003312301',100],
['Bergstrom','1003318064',3],
['Bergstrom','1003359943',130],
['Bergstrom','1003527802',25],
['Bergstrom','1003528808',50],
['Bergstrom','1003544214',400],
['Bergstrom','1003952165',50],
['Bergstrom','1004318685',261],
['Bergstrom','1004478818',6],
['Bergstrom','660320',11],
['Bergstrom','660543',200],
['Bergstrom','B222992',100],
['Bergstrom','PFA12927',3],
['Utilimaster','16519775',23],
['Utilimaster','16519778-C00000',2],
['Utilimaster','16519779-26C200',2],
['Utilimaster','16516385',55],
['Utilimaster','16516467',47],
['Utilimaster','16517689',118],
['Utilimaster','16517865-09200',1],
['Utilimaster','16518256',46],
['Utilimaster','16518485',140],
['Utilimaster','16518486',39],
['Utilimaster','16519282-004',4],
['Utilimaster','16519282-006',58],
['Utilimaster','16519665',94],
['Utilimaster','16519801-GNF002',86],
['Utilimaster','16519801-GNM002',140],
['Utilimaster','16519803-CSM001',104],
['Utilimaster','16519803-GNE002',1],
['Utilimaster','16519803-GNM002',151],
['Utilimaster','16519810-GNF002',40],
['Utilimaster','16519818',210],
['Utilimaster','16519839',69],
['Utilimaster','16519858-28600',250],
['Utilimaster','16519872',5],
['Utilimaster','16520042',12],
['Utilimaster','16519664',123],
['Utilimaster','16519858-22600',109],
['Utilimaster','16519899',1],
['Utilimaster','16519387',2],
['Tico','620806',53],
['Tico','620843',50],
['Tico','621655',25],
['Tico','621695',59],
['Tico','621751',10],
['Tico','621764',25],
['Tico','621805',25],
['Tico','621807',10],
['Tico','621944',25],
['Tico','621945',1],
['Tico','621966',25],
['Tico','621898',5],
['Tico','622049',24],
['Tico','622056',24],
['Tico','622058',24],
['Tico','622075',24],
['Tico','90843',50],
['Tico','910878',50],
['Tico','910907',50],
['Tico','910938',50],
['Tico','91232',75],
['Morgan','7381203',77],
['Morgan','90093202',854],
['Morgan','93093203',57],
['PAL006','TKA-00094',20],
['PAL007','OS5030115',190],
['POL004','W-4371',200],
['Proterra','32736',100],
['Proterra','300-1570-00-R01',2],
['Proterra','53344',588],
['REA002','HARN00003',30],
['Shyft','4075-GG5-001',1],
['Shyft','2606-GG5C',3],
['Shyft','2664-GG5A-010',4],
['Shyft','2666-GG5-007B',4],
['Shyft','3611-GG5A-001',2],
['Shyft','3965-GG5-001',5],
['Shyft','4109-GG5-001',18],
['Shyft','4181-GG5-001',1],
['Shyft','4181-GG5-002',2],
['Shyft','4187-GG5-003',2],
['Shyft','4191-GG5-002',11],
['Shyft','4214-GG5-002B',8],
['Shyft','4217-GG5-001B',2],
['Shyft','4222-GG5-001',1],
];

$tiempototal=0;

foreach($carga as $c){
    $pn = $c[1];
    $resultado=0;
    $buscartiempos = mysqli_query($con,"SELECT COUNT(*) FROM listascorte WHERE  pn = '$pn' AND `tamano` > 0 ");
    if(mysqli_num_rows($buscartiempos)>0){
        $cuenta= mysqli_fetch_array($buscartiempos)[0];
        $tiempo = $cuenta * $c[2]*2.9;
        $routing= $cuenta * 180;
        $resultado=$tiempo+$routing;
        
        $resultado=round($resultado,2);
        $tiempototal+=$resultado;
        
    }
    print($c[0]." ".$c[1]." ".$c[2]." ".$resultado."<br>");
}
print("Total ".$tiempototal);