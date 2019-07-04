<?php
/* *************************************************************************************
' Script Name: GWDataIOInterface.php
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
'Note: GWDataIOInterface is the interface for GWDataIO. The actual heavy lifting is done by FileIO or 
'DataBaseIO which extend this class for the specific IO ability
'getInstance(FlieName) to return the approperiate type from the file. 
'This class defines those protected methods common to all extended children
' **************************************************************************************/
namespace org\geekwisdom;
//require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using
interface GWDataIOInterface
{
public function getInstance($configfile = null);
public function insert($newrow,$confile = null);
public function update($updatedrow,$configfile=null);
public function search($whereclause,$configfile=null);
public function delete($whereclause,$configfile=null);
public function lock($id,$configfile=null);
public function unlock($id,$configfile=null);
public function open($configfile=null);
public function save($configfile);
}
?>
