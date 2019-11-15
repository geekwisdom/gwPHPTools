<?php
require_once __DIR__ . "/../../../autoload.php"; ///auto load
//require_once __DIR__ . "/../tests/test.class.php"; ///auto load
use org\geekwisdom\GWEZWebService;

//$myWebService = new GWEZWebService("def","./","adminbrad123");
$myWebService = new GWEZWebService("abc","./","adminbrad123");
$Params=Array();
$Params[0]="LogVerbosity";
$result=$myWebService->Fulfill("GetSetting",$Params,"JSON");
//$rstr=$result->toXML();
echo "Result is $result\n";
?>
