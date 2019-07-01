<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using
use \org\geekwisdom\GWLogger;
use \org\geekwisdom\LogType;

$mylog = new GWLogger(5,"main"); //loglevel 5
$mylog->WriteLog(1,LogType::Warning,"This is a warning message","");
$mylog->WriteLog(4,LogType::Info,"This is a information message","");
?>
