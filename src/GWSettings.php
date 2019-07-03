<?
/* *************************************************************************************
' Script Name: GWSettings.php
' **************************************************************************************
' @(#)    Purpose:
' @(#)    This is a shared component available to all .PHP applications. It allows simple
' @(#)    settings system that can be used to store and retrieve an application's settings
' @(#)    You can store settings databases,Property, an INI file, etc.
' **************************************************************************************
'  Written By: Brad Detchevery
              2274 RTE 640, Hanwell NB
'
' Created:     2019-05-26 - Initial Architecture
' 
' **************************************************************************************
'Note: Changing this routine effects all programs that change system settings
'-------------------------------------------------------------------------------*/
namespace org\geekwisdom;
use \SimpleXMLElement;
use \PDO;
class GWSettings 
{
private $ApplicationName;

function __construct($AppName="")
{
$this->ApplicationName = $AppName;
}

function GetSetting($FromLocation,$SettingName,$DefaultValue="")
{
if (is_file($FromLocation))
 {
//  echo $FromLocation;
//cho (strpos($FromLocation,".mdb" == FALSE) && strpos($FromLocation,".accdb") == FALSE);
  if (strpos($FromLocation,".mdb" !== FALSE) || strpos($FromLocation,".accdb") !== FALSE)
	{
	echo "TODO: Something with MSAccess";
	}
  else
	{
	//We know it might be a flie we can read
	if (strpos($FromLocation,".properties") !== FALSE)
  	{
	  //Read a prpoerties bag
	$inifile = parse_ini_file($FromLocation,false);
	if (array_key_exists($SettingName,$inifile)) return $inifile[$SettingName];
	return $DefaultValue;
	}
	if (strpos($FromLocation,".ini") !== FALSE)
 	{
	$inifile = parse_ini_file($FromLocation,true);
	if (array_key_exists($SettingName,$inifile)) return $inifile[$SettingName];
	if (array_key_exists($SettingName,$inifile[$this->ApplicationName])) return $inifile[$this->ApplicationName][$SettingName];
	return $DefaultValue;
	}

	if ((strpos($FromLocation,".config") !== FALSE) || (strpos($FromLocation,".xml") !== FALSE))
 	{
	  //Read a xml/config flie
	$xml = simplexml_load_file($FromLocation);
	if  (property_exists($xml,$SettingName)) return $xml->$SettingName;
	else
	{
	//Try app settings xpath
	$xmlstr = file_get_contents($FromLocation);
	$xmlpath = new SimpleXMLElement($xmlstr);
	$result = $xmlpath->xpath("/configuration/appSettings/add[@key='" . $SettingName . "']");
//	print_r($result[0]);
	
	if (count($result) == 0 ) return $DefaultValue;
	//if (!(array_key_exists("value",$result[0]))) return $DefaultValue;
	$retval = $result[0]['value'];
	if ($retval == "") return $DefaultValue;	
	return (string) $retval;
	}	
	return $DefaultValue;
	}
    }

	}
else
{
//echo "Try DB Connection here";
try {
$PD = new GWSafePDO($FromLocation);
$stmt = $PD->prepare("CALL GetSetting (?);");
$item="LogVerbosity";
$stmt->bindParam(1,$item);
$stmt->execute();
$rows=$stmt->fetch(PDO::FETCH_ASSOC);
//$rows=$stmt->fetchAll(PDO::FETCH_NUM);
if ($rows) 
{
$retval = $rows["SettingValue"];
if ($retval == "") return $DefaultValue;
return $retval;
}
return $DefaultValue;
}
catch (PDOException $e)
{
return $DefaultValue;
}

}
 
	//If we got here nothing else matched
	return $DefaultValue;
}

function GetSettingReverse($FromLocation,$SettingName,$DefaultValue="")
{

	//Try app settings xpath
	$xmlstr = file_get_contents($FromLocation);
	$xmlpath = new SimpleXMLElement($xmlstr);
	$result = $xmlpath->xpath("/configuration/appSettings/add[@value='" . $SettingName . "']");
	if (count($result) == 0 ) return $DefaultValue;
	$retval = $result[0]['key'];
	if ($retval == "") return $DefaultValue;	
	return $retval;
}


}
?>
