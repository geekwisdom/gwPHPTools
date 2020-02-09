<?php
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using 
use \org\geekwisdom\GWDataIO;
use \org\geekwisdom\GWDataFileIO;
$myobject = new GWDataIO();
$configFile = __DIR__ . "/../tests/dataIOTest.config";
$myobject->insert('{"Name":"Brad","Address":"Test","ID":"4"}',$configFile);
//$myobject->update('{"Name":"Brad","Address":"Test","ID":"4"}',$configFile);
//$result=$myobject->update('[{"Name":"MultiWorks","Address":"Test","ID":"4"},{"Name":"Feaken Cool","Address":"Test","ID":"2"}]',$configFile);
$ret=$myobject->search("Name='Mike Gold'",$configFile);
echo $ret->toJSON();

?>
