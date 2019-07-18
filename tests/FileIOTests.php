<?php
//require_once "vendor/autoload.php";
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using 
use \org\geekwisdom\GWDataIO;
use \org\geekwisdom\GWDataFileIO;
$myobject = new GWDataIO();
$configFile = __DIR__ . "/../tests/dataIOTest.config";
$myobject->insert('{"Name":"Brad","Address":"Test","ID":"4"}',$configFile);
//$myobject->update('{"Name":"Brad","Address":"Test","ID":"4"}',$configFile);
$result=$myobject->update('[{"Name":"MultiWorks","Address":"Test","ID":"4"},{"Name":"Feaken Cool","Address":"Test","ID":"2"}]',$configFile);
echo "result is $result\n";
$f=$ret=$myobject->search("Name='MultiWorks'",$configFile);
echo $f->toXML();

?>
