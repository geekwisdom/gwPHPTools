<?php
//require_once "vendor/autoload.php";
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using 
use \org\geekwisdom\GWSettings;

$connectstr=__DIR__ . "/../tests/testconn.dsn";
$mysm = new GWSettings();
$c=$mysm->GetSetting($connectstr,"test","0");
echo "C is $c\n";
$r = $mysm->GetSetting( __DIR__ . "/../tests/test.config","test","0");
echo "R is $r\n";	
$r = $mysm->GetSettingReverse(__DIR__ . "/../tests/test.config","Cool Dude","0");
echo "R is $r\n";

?>
