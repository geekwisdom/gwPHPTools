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
use \org\geekwisdom\GWSafePDO;
use \SimpleXMLElement;
use \DOMDocument;
use \PDO;
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
		//execute reflection
		require_once($OpSource);
		//todo:add reflectin code here
		}
	else
		{
		//execute stored procedure
		$PD = new GWSafePDO($OpSource);
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

}
