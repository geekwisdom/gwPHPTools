<?php
//require_once "vendor/autoload.php";
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using 
use \org\geekwisdom\GWDataIO;
use \org\geekwisdom\GWDataFileIO;
$myobject = new GWDataIO();
$configFile = __DIR__ . "/../tests/dataIOTest.config";
$myobject->insert('{"Name":"Brad","Address":"Test","ID":"4"}',$configFile);
$f=$ret=$myobject->search("Name='Brad'",$configFile);
echo $f->toXML();

?>
