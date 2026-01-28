<?php
require '../conector.php';

$datos=[
    ["Kinco Cutting Machine","FB022","Cutting","Maintenance, Operator","15"],
    ["EASTONTECH Cutting Machine","FB036","Cutting","Maintenance, Operator","15"],
    ["Kinco EW-801","FB040","Cutting","Maintenance, Operator","15"],
    ["EASTONTECH EW-05F","FB041","Cutting","Maintenance, Operator","15"],
    ["Artos Engineering CR.22","FB045","Cutting","Maintenance, Operator","15"],
    ["EASTONTECH EW801","FB048","Cutting","Maintenance, Operator","15"],
    ["EASTONTECH EW801","FB055","Cutting","Maintenance, Operator","15"],
    ["EASTONTECH EW801","FB059","Cutting","Maintenance, Operator","15"],
    ["EASTONTECH EW8010","FB038","Cutting","Maintenance, Operator","20"],
    ["EASTONTECH EW-09(1.5T-5T)","FB-081","Terminals","Maintenance, Operator","20"],
    ["EASTONTECH EW-09(1.5T-5T)","FB-082","Terminals","Maintenance, Operator","20"],
    ["EASTONTECH EW-09(1.5T-5T)","FB-083","Terminals","Maintenance, Operator","20"],
    ["EASTONTECH EW-09(1.5T-5T)","FB-084","Terminals","Maintenance, Operator","20"],
    ["EASTONTECH EW-09(1.5T-5T)","FB-085","Terminals","Maintenance, Operator","20"],
    ["EASTONTECH EW-09(1.5T-5T)","FB-086","Terminals","Maintenance, Operator","20"],
    ["EASTONTECH EW-09(1.5T-5T)","FB-087","Terminals","Maintenance, Operator","20"],
    ["EASTONTECH EW-09(1.5T-5T)","FB-088","Terminals","Maintenance, Operator","20"],
    ["EASTONTECH EW-09(1.5T-5T)","FB-089","Terminals","Maintenance, Operator","20"],
    ["EASTONTECH EW-09(1.5T-5T)","FB-090","Terminals","Maintenance, Operator","20"],
    ["PICO 500-DEC","PEND","Terminals","Maintenance, Operator","20"],
    ["PICO 500-DEC","PEND","Terminals","Maintenance, Operator","20"],
    ["Hand Crimping Stations","NA","Terminals","Operator","10"],
    ["Hand Crimping Stations","NA","Terminals","Operator","10"],
    ["Hand Crimping Stations","NA","Terminals","Operator","10"],
    ["Hand Crimping Stations","NA","Terminals","Operator","10"],
    ["Hand Crimping Stations","NA","Terminals","Operator","10"],
    ["Hand Crimping Stations","NA","Terminals","Operator","10"],
    ["Hand Crimping Stations","NA","Terminals","Operator","10"],
    ["SOLDERING IRON","NA","Terminals","Operator","5"],
    ["SOLDERING IRON","NA","Terminals","Operator","5"],
    ["SOLDERING IRON","NA","Terminals","Operator","5"],
    ["Electrical Heating Machine","FB-078","Shrinking","Maintenance, Operator","5"],
    ["Electrical Heating Machine","NA","Shrinking","Maintenance, Operator","5"],
    ["Electrical Heating Machine","NA","Shrinking","Maintenance, Operator","5"],
    ["Manual Heating Guns","NA","Shrinking","Operator","5"],
    ["Manual Heating Guns","NA","Shrinking","Operator","5"],
    ["EASTONTECH EW-60B","FB110","Splicing","Operator","8"],
    ["EASTONTECH EW-60B","FB115","Splicing","Operator","8"],
    ["Branzon Amtech 2032 Versagraph","FB077","Splicing","Maintenance, Operator","8"],
    ["Hand Splice Station","NA","Splicing","Operator","5"],
    ["Hand Splice Station","NA","Splicing","Operator","5"],
    ["Hand Splice Station","NA","Splicing","Operator","5"],
    ["Hand Drill","NA","Twisting","Operator","5"],
    ["Hand Drill","NA","Twisting","Operator","5"],
    ["Fan Motor","NA","Twisting","Operator","5"],
    ["Machine #1","RF-021","Loom","Operator","5"],
    ["Machine #2","RF-022","Loom","Operator","5"],
    ["Machine #3","RF-023","Loom","Operator","5"],
    ["Manual Loom","NA","Loom","Operator","5"],
    ["EW-9045","FB-104","Molding Inyection Machine","Maintenance","20"],
    ["Mold #1","GROMMET-37","Molding Inyection Machine","Operator","25"],
    ["Mold #2","GROMMET-7","Molding Inyection Machine","Operator","25"],
    ["Mold #3","GROMMET-9","Molding Inyection Machine","Operator","25"],
    ["Mold #4","GROMMET-40","Molding Inyection Machine","Operator","25"],
    ["Mold #5","GROMMET-38","Molding Inyection Machine","Operator","25"],
    ["Mold #6","GROMMET-16","Molding Inyection Machine","Operator","25"],
    ["Mold #7","GROMMET-28","Molding Inyection Machine","Operator","25"],
    ["Mold #8","GROMMET-16","Molding Inyection Machine","Operator","25"],
    ["Mold #9","GROMMET-1","Molding Inyection Machine","Operator","25"],
    ["Mold #10","GROMMET-27","Molding Inyection Machine","Operator","25"],
    ["Mold #11","NA","Molding Inyection Machine","Operator","25"],
    ["Mold #12","NA","Molding Inyection Machine","Operator","25"]

];

foreach ($datos as $key => $value) {
    echo $value[0]. " ";
    echo $value[1]. " ";
    echo $value[2]. " ";
    echo $value[3]. " ";
    echo $value[4]. " ";
   
$updatelista=mysqli_query($con,"INSERT INTO `assets`( `NameAsset`, `routingNumer`, `typeAsset`, `setUpResposability`, `standarSettingUpTime`) VALUES
 ('$value[0]','$value[1]','$value[2]','$value[3]','$value[4]')");
}