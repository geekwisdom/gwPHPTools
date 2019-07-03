<?php
/* *************************************************************************************
' Script Name: GWRowInterface.php
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

interface GWRowInterface
{
public function set ($name,$val);
public function get ($name);
public function toArray();
}

