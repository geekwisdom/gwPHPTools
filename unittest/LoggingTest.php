<?php include (dirname(__FILE__) . "/../org/geekwisdom/logging/gwLogger.lib");
$mylog = new GWLogger(5,"main"); //loglevel 5
$mylog->WriteLog(1,LogType::Warning,"This is a warning message","");
$mylog->WriteLog(4,LogType::Info,"This is a information message","");
?>
