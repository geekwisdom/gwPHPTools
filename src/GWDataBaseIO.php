<?php
/* *************************************************************************************
' Script Name: GWDataBaseIO.php
' **************************************************************************************
' @(#)    Purpose:
' @(#)    This is a shared component available to all PHP applications. It allows a common 
' @(#)    object that can CREATE (INSERT), RETRIEVE (SEARCH) UPDATE, AND DELETE from a variety of IO
' @(#)    sources. Specifically this function read/writes GWDataTables to/from a DATABASE
' **************************************************************************************
'  Written By: Brad Detchevery
			   2274 RTE 640, Hanwell NB
'
' Created:     2019-07-23 - Initial Architecture
' 
' **************************************************************************************
' **************************************************************************************/
namespace org\geekwisdom;
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using
use org\geekwisdom\GWDataIO;
use \org\geekwisdom\GWDataTable;
use \org\geekwisdom\GWDataRow;
use \SimpleXMLElement;
use \DOMDocument;
class GWDataBaseIO extends GWDataIO
{
private $dataTable;

function __construct ()
{
//construct the $data Array  from xmo
}

function getInstance($filename)
{
//return the applicable object type based on the filename
}


function insert($filename, $newrow)
{
echo "Hey I'm inside gwDataBASEIO INSERT METHOD $filename, $newrow - COOL!";
}


function search($filename,$whereclause)
{
echo "Hey I'm inside gwDataBASEIO SEARCH METOD $filename, $newrow - COOL!";
}

}
?>
