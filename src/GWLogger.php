<?php
/**************************************************************************************
' Script Name: GWLogger.php
' **************************************************************************************
' @(#)    Purpose:
' @(#)    This is a shared component available to all PHP applications. It allows simple
' @(#)    logging to a central location. The log file name is configurable but defaults to the application name.
' @(#)    The Log is to be initialized with a specified LogVerbosity. It wraps the apache log4net component
' @(#)    writes to the log file if the LogLevel <= LogVerbosity as defined by the application
' **************************************************************************************
'  Written By: Brad Detchevery
' Created:     2019-05-29 - Initial Architecture
' 
' **************************************************************************************
'Note: Changing this routine effects all programs that log to a common
'location.
'-------------------------------------------------------------------------------*/
namespace org\geekwisdom;
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using
use \org\geekwisdom\GWSettings;
use \Logger;


abstract class BasicEnum {
    private static $constCacheArray = NULL;

    private static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    public static function isValidName($name, $strict = false) {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    public static function isValidValue($value, $strict = true) {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }
}


class LogType extends BasicEnum
{
const Debug=1;
const Info=2;
const Warning=3;
const Error=4;
const Fatal=5;
const Security=6;
}
    

class GWLogger
{
private $logger;
private $LogVerbosity=10;
private $isInitialized=false;
private $LogName="";

function __construct ($_LogVerbosity,$_LogName)
{
$this->LogVerbosity=$_LogVerbosity;
$this->LogName=$_LogName;
}
function Initialize($configFile="",$Verbosity=10, $LogName="main") 
 {
 if ($this->isInitialized == false)

	{
         global $LOG4PHP;
         global $WEBCONFIG;
         if (!(isset($LOG4PHP))) $LOG4PHP="";
         if (!(isset($WEBCONFIG))) $WEBCONFIG="";
	if (($configFile == "") && ($LOG4PHP != "")) $configfile=$LOG4PHP;
	if ($configFile == "") $configfile=getenv("log4phpconfig");
	if (($configFile == "") && ($WEBCONFIG != ""))
	{	
	$mysm = new gwSettings();
	$configflie = $mysm->GetSetting($WEBCONFIG,"log4php.config","");
	}
	if ($configfile !="")
		{
		Logger::configure($configfile);
		}

	$this->logger = Logger::getLogger($LogName);
	$this->logger->info("Logger Initialized");
	$this->isInitialized=true;
	}
 }


function LogInfo($LogLevel,$ltype,$message,$exception="")
{

if ($LogLevel <= $this->LogVerbosity)
	{
	if ($ltype == LogType::Debug) $this->DoLog ("DebugLogger",$ltype,$message,$exception);
        elseif ($ltpye = LogType::Security) $this->DoLog("SecurityLogger",$ltype,$message,$exception);
	else $this->DoLog("",$lype,$LogItem .",". $LogLevel,$exception);

	}
}

function DoLog($LogFile,$ltype,$message,$exception)
{
if ($this->logger == null) $this->Initialize();
if ($ltype == LogType::Warning) $this->logger->warn($message);
if ($ltype == LogType::Info) $this->logger->info($message);

}

function WriteLog($LogLevel,$ltype,$message,$exception="")
{
$this->LogInfo($LogLevel,$ltype,$message,$exception);
}


}
?>
