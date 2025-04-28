<?php

require '../../app/conection.php';
const datos1= [
    ['20-01-2025','15:45','APPL-50','ET1-37','80','ANGEL','Sin novedad'],
    ['29-01-2025','15:45','APPL-6','ET1-35','110','JAVIER','Sin novedad'],
    ['05-02-2025','15:45','APPL-131','DT2-119','120','BETO','Sin novedad'],
    ['13-02-2025','15:45','APPL-64','DT1-54','145','BETO','Sin novedad'],
    ['17-02-2025','15:45','APPL-25','DT2-16','100','JAVIER','Sin novedad'],
    ['26-02-2025','15:45','APPL-93','DT2-36','140','BETO','Sin novedad'],
    ['05-03-2025','15:45','APPL-98','TT2-180','110','JAVIER','Sin novedad'],
    ['10-03-2025','15:45','APPL-55','ET1-5','180','BETO','Sin novedad'],
    ['13-03-2025','15:45','APPL-28','DT2-15','120','JAVIER','Sin novedad'],
    ['17-03-2025','15:45','APPL-36','-','60','JAVIER','Sin novedad'],
    ['21-03-2025','15:45','APPL-92','TT2-311','130','BETO','Sin novedad'],
    ['25-03-2025','15:45','APPL-48','ET2-34','180','JAVIER','Sin novedad'],
    ['01-04-2025','15:45','APPL-40','DT2-58','180','JAVIER','Sin novedad'],
    ['03-04-2025','15:45','APPL-19','DT2-45','140','BETO','Sin novedad'],
    ['07-04-2025','15:45','APPL-3','DT1-3','140','JAVIER','Sin novedad'],
    ['11-04-2025','15:45','APPL-47','DT2-33','160','BETO','Sin novedad'],
    ['14-04-2025','15:45','APPL-89','ET2-27','80','JAVIER','Sin novedad'],
    ['16-04-2025','15:45','APPL-37','DT1-5','100','JAVIER','Sin novedad'],
    ['21-04-2025','15:45','APPL-98','TT2-180','110','JAVIER','Sin novedad'],
    
];

foreach(datos1 as $dato){
mysqli_query($con,"INSERT INTO `mant_herramental`( `fecha_reg`, `tiempos`, `herramental`, `terminal`, `Minutos`, `quien`, `docMant`) VALUES ('$dato[0]','$dato[1]','$dato[2]','$dato[3]','$dato[4]','$dato[5]','$dato[6]')");
}
    

?>
