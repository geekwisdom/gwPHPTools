<?php
require_once __DIR__ . "/../vendor/autoload.php"; ///auto load
//require_once __DIR__ . "/../tests/test.class.php"; ///auto load
use org\geekwisdom\GWEZWebService;

$myWebService = new GWEZWebService("def","./","adminbrad123");
$Params=Array();
$Params[0]=1;
$Params[1]=2;
$result=$myWebService->Fulfill("Add",$Params,"JSON");
//$rstr=$result->toXML();
echo "Result is $result\n";
?>
