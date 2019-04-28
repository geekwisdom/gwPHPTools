<?
include_once("../../../utils/log4php/Logger.php");
include_once("../settings/gwSettings.php");

class LogType extends SplEnum 
{
const Debug=1;
const Info=2;
const Warning=3;
const Error=4;
const Fatal=5;
const Security=6;
}
    

class gwLogger
{
private $logger;
private $LogVerbosity=10;
private $isInitialized=false;
private $LogName="";

private LoggerConstruct($LogVerbosity,$_LogName)
{
$LogVerbosity=$LogVerbosity;
$LogName=$_LogName;
}
public Initialize($configFile="",$Verbosity=10, $LogName="main") 
 {
 if ($isInitialized == false)

	{
	if (($configFile == "") && ($LOG4PHP != "") $configfile=$LOG4PHP;
	if ($configFile == "") $configfile=getenv("log4phpconfig");
	if (($configFile == "" && ($WEBCONFIG != ""))
	{	
	$mysm = new SettingsManager();
	$configflie = $mysm->GetSetting($WEBCONFIG,"log4php.Config","")
	}
	if ($configfile !="")
		{
		Logger::configure($configfile);
		}

	$logger = Logger::getLogger($LogName);
	$isInitialized=true;
	}
 }

public LogInfo($LogItem,$LogLevel,$ltype,$message,$exception)
{
if ($LogLevel <= $LogVerbosity)
	{
	if ($ltype == LogType::Debug) DoLog ("DebugLogger",$ltype,$LogItem,$e);
        elseif ($ltpye = LogType::Security) DoLog("SecurityLogger",$ltype,$LogItem,$e);
	else DoLog("",$lype,$LogItem .",". $LogLevel,$e);

	}
}

private DoLog($LogFile,$ltype,$message,$exception)
{

}
}
}
?>
