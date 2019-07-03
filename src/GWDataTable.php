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
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using
use \org\geekwisdom\GWDataRow;
use \SimpleXMLElement;
use \DOMDocument;
class GWDataTable
{
private $data=array();
private $xml="";
private $tablename="root";

function __construct ($_xmlinfo = "",$_tablename="root")
{
//construct the $data Array  from xmo
$this->tablename=$_tablename;
}

function find($whereclause)
{
$data=$this->toXml();
$xml = @simplexml_load_string($data);
$result=$xml->xpath("/xmlDS/" . $this->tablename ."[" .$whereclause . "]"); 
$json = json_encode($result);
$array = json_decode($json,TRUE);
$retval=new GWDataTable("",$this->tablename);
for ($i=0;$i<count($array);$i++)
 {
 $retval->add(new GWDataRow($array[$i]));
 }
return $retval;
//print_r($result);
//die();

}

function loadXml($xmlstring)
{
//$this->data = array();
$xml = simplexml_load_string($xmlstring);
$json = json_encode($xml);
$array = json_decode($json,TRUE);
$keys = array_keys($array);
$tablename=$keys[0];
$table=array_pop($array);
$this->tablename=$tablename;
for ($i=0;$i<count($table);$i++)
 {
 $row=new GWDataRow($table[$i]);
 $this->add($row);
 }

}

function getRow($rownum)
{
//return $data at row $rownum
return $this->data[$rownum];
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
}
?>
