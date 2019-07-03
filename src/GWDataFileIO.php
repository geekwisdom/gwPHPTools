<?php
/* *************************************************************************************
' Script Name: GWDataFileIO.php
' **************************************************************************************
' @(#)    Purpose:
' @(#)    This is a shared component available to all PHP applications. It allows a common 
' @(#)    object that can CREATE (INSERT), RETRIEVE (SEARCH) UPDATE, AND DELETE from a variety of IO
' @(#)    sources. Specifically this function read/writes GWDataTables to/from a FILE
' **************************************************************************************
'  Written By: Brad Detchevery
			   2274 RTE 640, Hanwell NB
'
' Created:     2019-07-23 - Initial Architecture
' 
' **************************************************************************************
' **************************************************************************************/
namespace org\geekwisdom;
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using
use org\geekwisdom\GWDataIO;
use \org\geekwisdom\GWDataTable;
use \org\geekwisdom\GWDataRow;
use \SimpleXMLElement;
use \DOMDocument;
class GWDataFileIO extends GWDataIO
{

function __construct ()
{
//construct the $data Array  from xmo
}

function getInstance($configFile=null)
{
//return the applicable object type based on the filename
}


function insert($newrow,$configFile=null)
{
$rowobj = $this->translate($newrow);
if ($this->dataTable == null) $this->loadData($configFile);
$this->dataTable->add($rowobj);
//echo $this->dataTable->toXML();
$this->saveData($configFile);
//echo "Hey I'm inside gwDataFileIO INSERT METHOD $configFile, $newrow - COOL!";
}


function search($whereclause,$configFile=null)
{
$this->loadData($configFile);
$ret=$this->dataTable->find($whereclause);
return $ret;
}
function update($updatedrow,$configFile=null)
{

}



function delete($whereclause,$configfile=null)
{
}

function lock($id,$configFile=null)
{
}

function unlock($id,$configFile=null)
{
}

private function loadData($configFile)
{
$settingsManager = new GWSettings();
$configFile = $settingsManager->GetSetting($configFile,"connectionInfo","");
if (file_exists($configFile))
 {
  $xmlInfo=file_get_contents($configFile);
  $this->dataTable = new GWDataTable();
  $this->dataTable->loadXML($xmlInfo);
 }
}

private function saveData($configFile)
{
$settingsManager = new GWSettings();
$configFile = $settingsManager->GetSetting($configFile,"connectionInfo","");
$xmlout=$this->dataTable->toXML();
file_put_contents($configFile,$xmlout);
}

function open($configFile=null)
{
echo "Config File is $configFile\n";
$this->loadData($configFile);
}


function save($configfile=null)
{
saveData($configfile);
}



}
?>
