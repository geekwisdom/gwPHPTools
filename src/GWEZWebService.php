<?php
/* *************************************************************************************
' Script Name: GWEZWebService
' **************************************************************************************
' @(#)    Purpose:
' @(#)    This is a shared component available to all PHP applications. It allows an easy 
' @(#)    ability to serve a php class OR stored procedure as a web service
' @(#)    Simple Setup works with XML and JSON formats
' **************************************************************************************
'  Written By: Brad Detchevery
			   2274 RTE 640, Hanwell NB
'
' Created:     2019-11-03 - Initial Architecture
' 
' **************************************************************************************
'This class defines those protected methods common to all extended children
' **************************************************************************************/
namespace org\geekwisdom;
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using
use \org\geekwisdom\GWDataIOInterface;
use \org\geekwisdom\GWDataTable;
use \org\geekwisdom\GWDataRow;
use \org\geekwisdom\GWSettings;
use \org\geekwisdom\GWDBConnection;
use \SimpleXMLElement;
use \DOMDocument;
use \PDO;
use \ReflectionMethod;
class GWEZWebService 
{
protected $ServiceFile;
protected $ServiceName;
protected $UserName;
protected $fault403;
function __construct ($servicename="",$filepath="/tmp/",$_username="")
{
$this->ServiceName= $servicename;
$this->UserName = $_username;
$this->ServiceFile = $filepath . $servicename . ".xml";
$this->fault403 = new GWDataTable("","fault");
$newrow = new GWDataRow();
$newrow->set ("code","Server");
$newrow->set ("faultstring","403 Access Denied");
$this->fault403->Add($newrow);
}

function Fulfill($Method,$Params,$format) { //actual "process function' 
//given a mthod and parameters return the //result in $format 
$FinalOutput=""; $WebServiceConfig = new GWDataTable(); 
$GWServiceResults = new GWDataTable(); 
$GWServiceFile=file_get_contents($this->ServiceFile); 
$WebServiceConfig->loadXml($GWServiceFile); 
$FoundRows=$WebServiceConfig->find_row("[ OperationName _EQ_ \"" . $Method . "\" ]");
$OpType="";
$OpSource="";
$UserName="";
if (count($FoundRows) > 0)
	 {
    $FoundRow = $WebServiceConfig->getRow($FoundRows[0]);
    $OpType=$FoundRow->get("OperationType");
    $OpSource=$FoundRow->get("OperationSource");
    $AuthEnabled = $FoundRow->has_column("AllowedUsers");
    if ($AuthEnabled)
	{
	   $UserAuthNames=$FoundRow->get("AllowedUsers");

        if ($this->UserName == "")
		{
		if ($format == "XML") return $this->fault403->toXML();
		if ($format == "JSON") return $this->fault403->toJSON();
		return $this->fault403;
		}
	$has_semi=strpos($UserAuthNames,";");
	$AllowedUsers = Array();
	if ($has_semi === false)
 		{
		$AllowedUsers[0]=$UserAuthNames;
		}
	else
		{
		$AllowedUsers=explode(";",$UserAuthNames);
		}

	$AccessGranted=false;
	for ($i=0;$i<count($AllowedUsers);$i++)
	  {
	   if (strcasecmp($AllowedUsers[$i],$this->UserName) == 0) $AccessGranted=true;
 	  }
	if ($AccessGranted == false) 
		{
		if ($format == "XML") return $this->fault403->toXML();
		if ($format == "JSON") return $this->fault403->toJSON();
		return $this->fault403;
		}
	}

   $isStoredProcedure = strpos($OpSource,".class.php");
	if ($isStoredProcedure !== false)
		{
		$PArray=Array();
		$MethodData=Array();
		$testParse = $this->parseMethod($OpType,$MethodData,$PArray);
		if ($testParse === false) 
			{
			//return a FAULT error invalid parse of OpData
	  		return "ERROR!";
			}
		require_once($OpSource);
		$ClassName = $MethodData["MethodParts"];
		$MethodName = $MethodData["MethodName"];
		$reflectionMethod = new ReflectionMethod($ClassName,$MethodName);
		$classNameBracket=$ClassName . "()";
		if (strpos ($ClassName,".") == false) $ns="\\" . $ClassName;
		else $ns=$ClassName;
		$result = call_user_func_array(array($ns, $MethodName),$Params);
		return $result;
		}
	else
		{
		//execute stored procedure
		$PD = new GWDBConnection($OpSource);
		$stmt= $PD->prepare($OpType);
		$cnt=1;
		foreach ($Params as $key => $val) 
			{
			$stmt->bindParam($cnt,$Params[$key]);
			$cnt++;
			}
		$stmt->execute();
		$rows=$stmt->fetch(PDO::FETCH_ASSOC);
		
		if (count($rows) > 0)
		{
		$retval = new GWDataTable("","Results");
		$retval->readList($rows);
		if ($format == "XML") return $retval->toXML();
		if ($format == "JSON") return $retval->toJSON();
		return $retval;
		}
}
    echo "Found it!\n";
    echo "MethodType = $OpType\n";
    echo "MethodSource = $OpSource\n";
    echo "UserName = $UserName\n";

 }


}

private function parseMethod($MethodData,&$methods,&$params)
{
//parse Method data eg GWSettings.MySettings.getName(Param1,Param2)
//should create the 'Prefix GWSettings->MySettings, and the MethodName getname
//and an array of parameter Names (Param1, Param2). Trainling semicolins
//are ignored!
$MethodNaneSide="";
$MethodParamsSide="";
$checkOpenBracket = strpos($MethodData,"(");
$checkClosedBracket = strpos($MethodData,")");

//split method name from parameters
if ($checkOpenBracket !== false)
 {
  if ($checkClosedBracket === false) return false; //parse format error
  if ($checkClosedBracket < $checkOpenBracket) return false; //parse format error
  $MethodNameSide = substr($MethodData,0,$checkOpenBracket);
  $MethodParamsSide =substr($MethodData,$checkOpenBracket);
 }
else
 {
  $MethodNameSide=$MethodData;
  $MethodParamsSide="";
 }

//Split method name

$checkdot = strpos($MethodNameSide,".");
if ($checkdot !== false)
 {
  $gparts = explode(".",$MethodNameSide);
  $last=count($gparts);
  $MethodParts=$gparts[0];
  for ($i=1;$i<$last-1;$i++) $MethodParts = $MethodParts . "->" . $gparts[$i];
  $MethodName=$gparts[$last-1];
  $methods["MethodName"] = $MethodName;
  $methods["MethodParts"] = $MethodParts;
 }
else
 { 
 $methods["MethodName"] = $MethodNameSide;
 $methods["MethodParts"] ="";
	
 }

//split params $Params=Array(); if 
if ($this->getParams($MethodParamsSide,$Params))
 {
 $params=$Params;
 return true;
 }
return false; //parseerror

}


private function getParams($ParamSide,&$Params)
{
//given a function containing a format of "(" and ")" get the parameters
//from the inside useful for both STORED PROCEDURS AND REFLECTION

$checkOpenBracket = strpos($ParamSide,"(");
$checkClosedBracket = strpos($ParamSide,")");

//split method name from parameters
if ($checkOpenBracket !== false)
 {
  $checkClosedBracket = strpos($ParamSide,")");

  if ($checkClosedBracket === false) return false; //parse format error
  if ($checkClosedBracket < $checkOpenBracket) return false; //parse format error
  $ParamData = substr($ParamSide,$checkOpenBracket,$checkClosedBracket-$checkOpenBracket+1);
  $ParamData = str_replace("(","",$ParamData);
  $ParamData = str_replace(")","",$ParamData);
  $ParamData = str_replace(";","",$ParamData);
  $Params=Array();
  $checkcomma = strpos($ParamData,",");
  if ($checkcomma === false) 
    {
   $Params[0] = trim($ParamData);
   return true;
  }
  else
 	{
	$parray=explode(",",$ParamData);
	for ($i=0;$i<count($parray);$i++) $Params[$i]=trim($parray[$i]);
	return true;
	}
  
 }
return false; //format/parse error
}

}
