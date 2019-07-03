<?php
/* *************************************************************************************
' Script Name: GWDataRow.php
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

class GWDataRow
{
private $item=array();

function __construct ($ary=null)
{
if ($ary !== null)
{
$this->item=$ary;
}
}

function set ($name,$val)
{
$this->item[$name]=$val;
}

function get ($name)
{
$a=$this->item;
$r=$a[$name];
return $r;
}

function toArray()
{
return $this->item;
}

}

