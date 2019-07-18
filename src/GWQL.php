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

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using
class GWQL 
{
private $whereclause="";

function __construct ($WHERE_CLAUSE = "")
{
$this->whereclause=$WHERE_CLAUSE;
}
//construct the $data Array  from xmo

function find($arry)
{
//return the indexes in $array (as an array) that match $WHERE_CLAUSE
//echo "W: " . $this->whereclause;
//print_r($arry);
$ret=Array();
for ($i=0;$i<count($arry);$i++)
 {
// print_r($arry[$i]);
if ($this->parse_compare_outside($this->whereclause,$arry[$i]))
   {
  //   echo "Found one! $i\n";
     array_push($ret,$i);
   }
//echo "No Match: $i\n";
 }
return $ret;
}

private function compare ($cmp,$arry)
{
$FD="NULL";
$OP="NULL";
$VL="NULL";
$r=$this->parse_compare($cmp,$FD,$OP,$VL);
if ($r === true)
 {
if (! array_key_exists($FD,$arry)) throw new GWException("ARRAY MISSING FIELD $FD",30);
//echo "FD is $FD\n";
//echo "OP is $OP\n";
//echo "VL is $VL\n";

if ($OP == "_EQ_")
  {
  // echo $arry[$FD] . " = " . $VL . "\n";
  if ($arry[$FD] == $VL) return true;
  else return false;
  }


elseif ($OP == "_GT_")
  {
//   echo $arry[$FD] . " > " . $VL . "\n";
  if ($arry[$FD] > $VL) return true;
  else return false;
  }


elseif ($OP == "_LT_")
  {
  // echo $arry[$FD] . " < " . $VL . "\n";
  if ($arry[$FD] < $VL) return true;
  else return false;
  }

elseif ($OP == "_GE_")
  {
  // echo $arry[$FD] . " >= " . $VL . "\n";
  if ($arry[$FD] >= $VL) return true;
  else return false;
  }

elseif ($OP == "_LE_")
  {
  // echo $arry[$FD] . " <- " . $VL . "\n";
  if ($arry[$FD] <= $VL) return true;
  else return false;
  }

elseif ($OP == "_NE_")
  {
  // echo $arry[$FD] . " <> " . $VL . "\n";
  if ($arry[$FD] != $VL) return true;
  else return false;
  }

elseif ($OP == "_LIKE_")
  {
  // echo $arry[$FD] . " LIKE " . $VL . "\n";
   $p = strpos($arry[$FD],$VL);
  if ($p === false) return false;
  else return true;
  }



 }
else
{
throw new GWException("INVALID OPERATOR: $OP",31);
}
}

private function rec_compare($cmp,$arry)
{
$s = strpos($cmp," _AND_ ");
if ($s === false) 
 { 
$s = strpos($cmp," _OR_ ");
if ($s === false) return $this->compare($cmp,$arry);
$v = explode (" _OR_ ",$cmp);
return $this->rec_compare($v[0],$arry) || $this->rec_compare($v[1],$arry);
 }
$v = explode (" _AND_ ",$cmp);
return $this->rec_compare($v[0],$arry) && $this->rec_compare($v[1],$arry);


}


 

private function parse_compare_outside($inputstr,$arry)
{
$ret = false;
$v = preg_split("/[\[\]]+/", $inputstr, -1, PREG_SPLIT_NO_EMPTY);
//echo $inputstr;
//print_r($v);
if (count($v) == 1) return $this->rec_compare($inputstr,$arry);
$item=0;
for ($i=0;$i<count($v);$i++ )
 {
  if ($v[$i] != "" && $v[$i] != " " && $v[$i] != "[" && $v[$i] != "(" && $v[$i] != "]" && $v[$i] != ")")
    {
     if ($item == 0) $LHS=$v[$i];
     if ($item == 1) $comp=$v[$i];
     if ($item == 2) $RHS=$v[$i];
    $item++;
    }
 }

if (trim($comp) == "_AND_") return $this->rec_compare($LHS,$arry) && $this->rec_compare($RHS,$arry);
if (trim($comp) == "_OR_") return $this->rec_compare($LHS,$arry) || $this->rec_compare($RHS,$arry);
}
function parse_compare($inputstr,&$field,&$operator,&$value)
{
$FD="NULL";
$OP="NULL";
$VL="NULL";
//$v =explode(" ",$inputstr);
preg_match_all('/\'(?:\\\\.|[^\\\\"])*\'|\S+/', $inputstr, $matches);
$v=$matches[0];
//print_r($v);
$item=0;
for ($i=0;$i<count($v);$i++ )
 {
  if ($v[$i] != "" && $v[$i] != " " && $v[$i] != "[" && $v[$i] != "(" && $v[$i] != "]" && $v[$i] != ")")
    {
     if ($item == 0) $FD=$v[$i];
     if ($item == 1) $OP=$v[$i];
     if ($item == 2) $VL=$v[$i];
    $item++;
    }
 }
if ($item > 3) throw new GWException("GWQL SYNTAX ERROR at " . $v[$item],23);
if ($FD == "NULL") throw new GWException("GWQL SYNTAX ERROR MISSING FIELD IN " . $inputstr,24);
if ($OP == "NULL") throw new GWException("GWQL SYNTAX ERROR MISSING OPERATOR IN " . $inputstr,25);
if ($VL == "NULL") throw new GWException("GWQL SYNTAX ERROR MISSING VALUE IN " . $inputstr,26);
$field=$FD;
$operator=$OP;
$value=str_replace("'","",$VL);
return true;
}


}
?>
