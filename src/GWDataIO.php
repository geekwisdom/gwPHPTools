<?php
/* *************************************************************************************
' Script Name: GWDataIO.php
' **************************************************************************************
' @(#)    Purpose:
' @(#)    This is a shared component available to all PHP applications. It allows a common 
' @(#)    object that can CREATE (INSERT), RETRIEVE (SEARCH) UPDATE, AND DELETE from a variety of IO
' @(#)    sources. Specifically using the GWDataTable / GWDataRow format
' **************************************************************************************
'  Written By: Brad Detchevery
			   2274 RTE 640, Hanwell NB
'
' Created:     2019-07-23 - Initial Architecture
' 
' **************************************************************************************
'Note: GWDataIO is the base class. The actual heavy lifting is done by FileIO or 
'DataBaseIO which extend this class for the specific IO ability
'getInstance(FlieName) to return the approperiate type from the file. 
'This class defines those protected methods common to all extended children
' **************************************************************************************/
namespace org\geekwisdom;
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using
use \org\geekwisdom\GWDataIOInterface;
use \org\geekwisdom\GWDataTable;
use \org\geekwisdom\GWDataRow;
use \org\geekwisdom\GWSettings;
use \SimpleXMLElement;
use \DOMDocument;
class GWDataIO implements GWDataIOInterface
{
protected $dataTable;
protected $_configFile;
protected $defaultObj;

function __construct ($ConfigFile = null,$_defaultObj = "\org\geekwisdom\GWDataRow")
{
if ($_defaultObj !=null) $this->defaultObj = $_defaultObj;
if ($ConfigFile != null) $this->_configFile = $ConfigFile;
//construct the $data Array  from xmo
}

function getInstance($configfile = null)
{
//echo "D2: " . $this->defaultObj . "\n\n";
if ($configfile == null) $configfile = $this->_configFile;
//return the applicable object type based on the filename
$settingsManager = new GWSettings();
$type = $settingsManager->GetSetting($configfile,"IOTYPE","");
if ($type == "") return null;
//echo $type;
return new $type($configfile,$this->defaultObj);
}


function insert($newrow,$configfile=null)
{
if ($configfile == null) $configfile = $this->_configFile;

$tmpobj = $this->getInstance($configfile);
return $tmpobj->insert($newrow,$configfile);
}

function update($updatedrow,$configfile=null)
{
if ($configfile == null) $configfile = $this->_configFile;
$tmpobj = $this->getInstance($configfile);
return $tmpobj->update($newrow,$configfile);
}


function search($whereclause,$configfile=null)
{
if ($configfile == null) $configfile = $this->_configFile;
$tmpobj = $this->getInstance($configfile);
return $tmpobj->search($whereclause,$configfile);
}

function delete($whereclause,$configfile=null)
{
if ($configfile == null) $configfile = $this->_configFile;
$tmpobj = $this->getInstance($configfile);
return $tmpobj->delete($newrow,$configfile);
}

function lock($id,$configfile=null)
{
if ($configfile == null) $configfile = $this->_configFile;
$tmpobj = $this->getInstance($configfile);
return $tmpobj->lock($id,$configfile);
}

function unlock($id,$configfile=null)
{
if ($configfile == null) $configfile = $this->_configFile;
$tmpobj = $this->getInstance($configfile);
return $tmpobj->unlock($id,$configfile);
}


function open($configfile=null)
{
if ($configfile == null) $configfile = $this->_configFile;
$tmpobj = $this->getInstance($configfile);
return $tmpobj->open($configfile);
}


function save($configfile)
{
if ($configfile == null) $configfile = $this->_configFile;
$tmpobj = $this->getInstance($configfile);
return $tmpobj->save($configfile);
}



protected function translate($inputJSON)
{
//convert a JSON String to a GWDataROW
$obj=json_decode($inputJSON,true);
$newrow = new GWDataRow($obj);
return $newrow;
}

}
?>
