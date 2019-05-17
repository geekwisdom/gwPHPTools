<?php include (dirname(__FILE__) . "/../org/geekwisdom/logging/gwLogger.php");
$mylog = new gwLogger(5,"main"); //loglevel 5
$mylog->LogInfo(1,LogType::Warning,"This is a warning message","");
$mylog->LogInfo(4,LogType::Info,"This is a information message","");
?>
