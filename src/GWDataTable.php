<?php
/* *************************************************************************************
' Script Name: GWDataTable.php
' **************************************************************************************
' @(#)    Purpose:
' @(#)    This is a shared component available to all PHP applications. It allows a common data row / data table
' @(#)    object that for manipulating sets of related data abstractly regardless
' @(#)    of the specific architecture (database, files, xml, json used).
' **************************************************************************************
'  Written By: Brad Detchevery
			   2274 RTE 640, Hanwell NB
'
' Created:     2019-05-20 - Initial Architecture
' 
' **************************************************************************************
'Note: Changing this routine effects all programs that maniuplate data sets
'-------------------------------------------------------------------------------*/
namespace org\geekwisdom;
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using
use \org\geekwisdom\GWRowInterface;
use \org\geekwisdom\GWDataRow;
use \org\geekwisdom\GWQL;
use \SimpleXMLElement;
use \DOMDocument;
class GWDataTable
{
private $data=array();
private $xml="";
private $tablename="root";
private $defobject="\\org\\geekwisdom\\GWDataRow";

function __construct ($_xmlinfo = "",$_tablename="root",$defObj="\\org\\geekwisdom\\GWDataRow")
{
//construct the $data Array  from xmo
$this->tablename=$_tablename;
$this->defobject=$defObj;
}

function getTableName()
{
return $this->tablename;
}

function find($whereclause,$data_type = null)
{
$qry=$whereclause;
if (substr($whereclause,0,2) == "[ ")
 {
$ary=$this->toArray();
$qltester = new GWQL($whereclause);
$qry = $qltester->getXPath();
//echo "Q is $qry\n";
 }

if ($data_type == null) $data_type=$this->defobject;
$data=$this->toXml();
$xml = @simplexml_load_string($data);
$result=$xml->xpath("/xmlDS/" . $this->tablename ."[" .$qry . "]"); 
$json = json_encode($result);
$array = json_decode($json,TRUE);
$retval=new GWDataTable("",$this->tablename);
for ($i=0;$i<count($array);$i++)
 {
  $retval->add(new $data_type($array[$i]));
  }
 
return $retval;
//print_r($result);
//die();

}

function find_row($whereclause)
{
//find the rows whereclause return indexes as array
$ary=$this->toArray();
$qltester = new GWQL($whereclause);
//print_r($this->data);
$retval = $qltester->find($ary);
//if ($r == true) echo "COMPARE TRUE";
//if ($r == false) echo "COMPARE FALSE";
//echo "\nR is $r\n";
return $retval;
}


function loadXml($xmlstring)
{
//$this->data = array();
$xmlfixed = preg_replace('/&(?![A-Za-z0-9#]{1,7};)/','&amp;',$xmlstring);
$xml = simplexml_load_string($xmlfixed);
$json = json_encode($xml);
$array = json_decode($json,TRUE);
$keys = array_keys($array);
$tablename=$keys[0];
$this->tablename=$tablename;
//handle the case of a single item !
$table=array_pop($array);
if ($this->has_string_keys($table))
 {
 //only one row in table
  $row = new GWDataRow($table);
  $this->add($row);
 }
else
 {
 //multiple rows
for ($i=0;$i<count($table);$i++)
 {
 $row=new GWDataRow($table[$i]);
 $this->add($row);
 }
}

}

function getRow($rownum,$data_type=null)
{
//return $data at row $rownum
if ($data_type == null) $data_type=$this->defobject;
//echo "d is $data_type\n";
return new $data_type($this->data[$rownum]->toArray());
}

function setRow($rownum,$row_data,$data_type=null)
{
//return $data at row $rownum
if ($data_type == null) $data_type=$this->defobject;
//echo "d is $data_type\n";
$this->data[$rownum]=$row_data;
return;
}


function add($rowitem)
{
//add a new $rowitem of data to the row (should have same "properites as other ows 'someohw'
if ($this->validatearray($rowitem))
 {
 array_push($this->data,$rowitem);
 return true;
 }
return false;
}

function length()
{
return count($this->data);
}
function validatearray($rowitem)
{
//verify the properties/columns of the array are consistent before insert
return true;
}
function remove($rownum)
{
//remove $rownum item from the table

}

function insert($rowitem,$rownum)
{
//insert rowitem to rownum
}

function array_to_xml( $dataColumn, &$xml_data ) {

    foreach( $dataColumn as $key => $value ) {
        if( is_numeric($key) ){
          //  $key = 'item'.$key; //dealing with <0/>..<n/> issues
              $key = $this->tablename;
        }
        if( is_array($value) ) {
            $subnode = $xml_data->addChild($key);
            $this->array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}

function toArray()
{
$newary = array();
for ($i=0;$i<count($this->data);$i++)
 {
  $item=$this->data[$i];
  $newary[$i]=$item->toArray();
 }
return $newary;
}

function readList($ListInput)
{
foreach ($ListInput as $key => $val) 
{
$newrow = new GWDataRow();
$newrow->set($key,$val);
$this->add($newrow);
}
}

function toJSON()
{
$xml = $this->toXML();
$xmlary = simplexml_load_string($xml);
$json = json_encode($xmlary);
return $json;
}
function toXml()
{
//output result as xml / set xml
$xml_data = new SimpleXMLElement('<?xml version="1.0" ?><xmlDS></xmlDS>');
$data = $this->data;
$n = $this->toArray();
$this->array_to_xml($n,$xml_data);
$result = $xml_data->asXML();
$xmlDocument = new DOMDocument('1.0');
$xmlDocument->preserveWhiteSpace = false;
$xmlDocument->formatOutput = true;
$xmlDocument->loadXML($result);
$retval = $xmlDocument->saveXML();
return $retval;
}

private function has_string_keys(array $array) {
  return count(array_filter(array_keys($array), 'is_string')) > 0;
}

}
?>
