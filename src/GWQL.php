<?php
/* *************************************************************************************
' Script Name: GWQL.PHP
' **************************************************************************************
' @(#)    Purpose:
' @(#)    This is the GEEK WISDOM QUERY LANGUAGE. It is designed to be a language
' @(#)    independant queyr language for use with SQL, JSON, and the GeekWisdom
' @(#)    table object.
' **************************************************************************************
'  Written By: Brad Detchevery
			   2274 RTE 640, Hanwell NB
'
' Created:     2019-07-23 - Initial Architecture
' 
' **************************************************************************************/
namespace org\geekwisdom;
use org\geekwisdom\GWException;
use org\geekwisdom\GWXPathBuilder;

require_once __DIR__ . '/../../../autoload.php'; // Autoload files using
class GWQL 
{
private $Clause="";
private $Params;
private $allowedFields =  array();
private $cFlags = array();

function __construct ($clause)
{
$this->Clause=$clause;
$Params = array();
}
//construct the $data Array  from xmo

function setClause($newclause)
{
$this->Clause=$clause;
$Params = array();
}

private function setFlags($clause_input)
{
$last = strrpos($clause_input,"]");
if ($last == false) return $clause_input;
$retval = substr($clause_input,0,$last+1);
if ($last+1 < strlen($clause_input))
 {
 $flagsStr = substr($clause_input,$last+2);
 $flags = explode(" ",$clause_input);
 for ($i=0;$i<count($flags);$i++)
   {
    $v=true;
    $flagname=$flags[$i];
    if (substr($flagname,0,1) == "!") 
	{
	 $v=false;
	$flagname = substr($flagname,1);
	}
     $this->cFlags[$flagname]=$v;
   }
 }
return $retval;
}


public function getFlag($flagname)
{
if (!(array_key_exists($flagname,$this->cFlags))) return false;
return $this->cFlags[$flagname];
}

public function setAllowedFields($Fields)
{
$this->allowedFields=$Fields;
}

public function getCommand($cmdObj)
{
$this->build_outer_string($this->Clause,$cmdObj);
return $cmdObj->getFinalCmd();
}

private function build_outer_string($inputstr,$cmdObj)
{
//echo "I is $inputstr\n";
$retval = false;
$regExp="/[\[\]]+/";
$LHS="";
$RHS="";
$comp="";
//$v = preg_split($regExp, $inputstr, -1, PREG_SPLIT_NO_EMPTY);
//preg_match($regExp, $inputstr, $matches, PREG_OFFSET_CAPTURE);
//preg_match($regExp, $inputstr, $matches);
$itemCount=-1;
/*
for ($i=0;$i<count($v);$i++ )
 {
  if ($v[$i] != "" && $v[$i] != " " && $v[$i] != "[" && $v[$i] != "(" && $v[$i] != "]" && $v[$i] != ")")
    {
    $itemCount++;
     if ($itemCount == 0) $LHS= "[ " . $v[$i] . " ]";
     if ($itemCount == 1) $comp=$v[$i];
     if ($itemCount == 2) $RHS="[ " . $v[$i] . " ]";
    }
 }

if (trim($comp) == "_AND_") 
 { 
$LHS_SIDE=array();
$RHS_SIDE=array();
array_push($LHS_SIDE,"AND");
$this->rec_build_string($LHS,$cmdObj,$LHS_SIDE);
$this->rec_build_string($RHS,$cmdObj,$RHS_SIDE);
return true;
}
*/

if ($itemCount == -1) return $this->rec_build_string($inputstr,$cmdObj,array());
return $retval;
}

private function rec_build_string($cmp,$cmdObj,$substs)
{
//echo "CMP is $cmp\n";
$and_test = strpos($cmp," _AND_ ");
if ($and_test === false) 
 { 
$or_test = strpos($cmp," _OR_ ");
if ($or_test === false) {
$cmdObj->buildString($cmp,$substs,$this->allowedFields);
return true;
}
$parts = explode (" _OR_ ",$cmp);
$the_or=array();
array_push($the_or,"OR");
$this->rec_build_string($parts[0],$cmdObj,$the_or);
$this->rec_build_string($parts[1],$cmdObj,array());
return true ;
 }

$parts = explode (" _AND_ ",$cmp);
$the_and=array();
array_push($the_and,"AND");
$this->rec_build_string($parts[0],$cmdObj,$the_and);
$this->rec_build_string($parts[1],$cmdObj,array());
return true ;

}

public function getParams()
{
return $this->Params;
}
}
?>
