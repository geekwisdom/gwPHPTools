<?php
require_once __DIR__ . "/../vendor/autoload.php"; ///auto load
use org\geekwisdom\GWEZWebService;

$myWebService = new GWEZWebService("abc","./","adminbrad123");
$Params=Array();
$Params["LogVerbosity"]="LogVerbosity";
$result=$myWebService->Fulfill("GetSetting",$Params,"JSON");
//$rstr=$result->toXML();
echo "$result\n";
?>
