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
private $SQLPart="";
private $params=array();

function __construct ($WHERE_CLAUSE = "")
{
$this->whereclause=$WHERE_CLAUSE;
}
//construct the $data Array  from xmo

function setWhereClause($newclause)
{
$this->SQLPart="";
$this->whereclause=$newclause;
$this->params=array();
}
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
//echo "HELLOOOO!!!\n";
$FD="NULL";
$OP="NULL";
$VL="NULL";
$r=$this->parse_command($cmp,$FD,$OP,$VL);
$VL=str_replace("\"","",$VL);
if ($r === true)
 {
if (! array_key_exists($FD,$arry)) throw new GWException("ARRAY MISSING FIELD $FD",30);

if ($OP == "_EQ_")
  {
   // echo $arry[$FD] . " = " . $VL . "\n";
  if ($arry[$FD] == $VL) return true;
  else return false;
  }


elseif ($OP == "_GT_")
  {
   // echo $arry[$FD] . " > " . $VL . "\n";
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


function getXPath()
{
//convert $whereclause to valid XPath Equilvent
$subst=array();
$subst["OR"]=" or ";
$subst["AND"]=" and ";
$subst["LEFTBRACKET"]=" ( ";
$subst["RIGHTBRACKET"]=" ) ";
//$subst["OR"]=" or ";
$this->build_outer_string($this->whereclause,"build_xpath",$subst);
return $this->SQLPart;
}

function getSQLCommand($start_part,$db)
{
//convert $whereclause to valid XPath Equilvent
$subst=array();
$subst["OR"]=" or ";
$subst["AND"]=" and ";
$subst["LEFTBRACKET"]=" ( ";
$subst["RIGHTBRACKET"]=" ) ";
//$subst["OR"]=" or ";
$this->build_outer_string($this->whereclause,"build_sql",$subst);
$sqlstr = "$start_part WHERE " . $this->SQLPart . ";";
//echo "sqlstr is $sqlstr\n";
//print_r($this->params);
$stmnt = $db->prepare($sqlstr);
for ($i=0;$i<count($this->params);$i++) 
{
//echo "Binding.." . $this->params[$i] . "\n";
$stmnt->bindParam($i+1,$this->params[$i]);
}
return $stmnt;
}


private function build_xpath($cmp,$subst,$combiner)
{
//echo $cmp . "\n";
$FD="NULL";
$OP="NULL";
$VL="NULL";
$r=$this->parse_command($cmp,$FD,$OP,$VL);
$VL=str_replace("\"","'",$VL);
if ($r === true)
 {

if ($OP == "_EQ_") $OP="=";
elseif ($OP == "_GT_") $OP=">";
elseif ($OP == "_LT_") $OP="<";
elseif ($OP == "_GE_") $OP=">=";
elseif ($OP == "_LE_") $OP="<=";
elseif ($OP == "_NE_") $OP="!=";
elseif ($OP == "_LIKE_") $OP="contains";
$value=str_replace("\"","'",$VL);

if ($OP == "contains")
{
$part = "contains($FD,$value)";
}
else
{
$part = $FD.$OP.$value;
}
$this->SQLPart = $this->SQLPart . $part;
//print_r($combiner);
if (is_array($combiner)) for ($i=0;$i<count($combiner);$i++) $this->SQLPart = $this->SQLPart . $subst[$combiner[$i]];
//if ($combiner !="") $this->SQLPart = $this->SQLPart . $subst[$combiner] ;
 }
}


private function build_sql($cmp,$subst,$combiner)
{
//echo $cmp . "\n";
$FD="NULL";
$OP="NULL";
$VL="NULL";
$r=$this->parse_command($cmp,$FD,$OP,$VL);
$VL=str_replace("\"","'",$VL);
if ($r === true)
 {

if ($OP == "_EQ_") $OP="=";
elseif ($OP == "_GT_") $OP=">";
elseif ($OP == "_LT_") $OP="<";
elseif ($OP == "_GE_") $OP=">=";
elseif ($OP == "_LE_") $OP="<=";
elseif ($OP == "_NE_") $OP="!=";
elseif ($OP == "_LIKE_") $OP="LIKE";
$value=str_replace("\"","",$VL);
$value=str_replace("'","",$VL);
$part = $FD.$OP."?";
//$part = $FD.$OP.":" . $FD . ":";
//echo "V: $value\n";
array_push($this->params,$value);

$this->SQLPart = $this->SQLPart . $part;
//print_r($combiner);
if (is_array($combiner)) for ($i=0;$i<count($combiner);$i++) $this->SQLPart = $this->SQLPart . $subst[$combiner[$i]];
//if ($combiner !="") $this->SQLPart = $this->SQLPart . $subst[$combiner] ;
 }
}


private function rec_compare($cmp,$arry)
{
//echo "inside reccompare\n";
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

//$this->rec_build_string($LHS,$custom_function,$arry,"AND");
private function rec_build_string($cmp,$custom_function,$arry,$COMPAREOP="")
{
$s = strpos($cmp," _AND_ ");
if ($s === false) 
 { 
$s = strpos($cmp," _OR_ ");
if ($s === false) {
call_user_func(array($this,$custom_function),$cmp,$arry,$COMPAREOP);
//call_user_func(array($this,$custom_function),$cmp,$arry,"OR");
return;
}
$v = explode (" _OR_ ",$cmp);
$this->rec_build_string($v[0],$custom_function,$arry,array("OR")) ;
$this->rec_build_string($v[1],$custom_function,$arry,$COMPAREOP);
return;
 }
$v = explode (" _AND_ ",$cmp);
$this->rec_build_string($v[0],$custom_function,$arry,array("AND")) ;
$this->rec_build_string($v[1],$custom_function,$arry,$COMPAREOP);
return;


}


 

private function parse_compare_outside($inputstr,$arry)
{
//echo "HERE !!!!again\n";
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
//echo "Comp is $comp\n";
//print_r($v);
//die();
if (trim($comp) == "_AND_") return $this->rec_compare($LHS,$arry) && $this->rec_compare($RHS,$arry);
if (trim($comp) == "_OR_") return $this->rec_compare($LHS,$arry) || $this->rec_compare($RHS,$arry);

}


private function build_outer_string($inputstr,$custom_function,$arry)
{
$ret = false;
$v = preg_split("/[\[\]]+/", $inputstr, -1, PREG_SPLIT_NO_EMPTY);
//echo $inputstr;
//print_r($v);
//die();
if (count($v) == 1) return $this->rec_build_string($inputstr,$custom_function,$arry);
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

if (trim($comp) == "_AND_") 
 { 
$this->rec_build_string($LHS,$custom_function,$arry,array("AND","LEFTBRACKET"));
$this->rec_build_string($RHS,$custom_function,$arry,array("RIGHTBRACKET"));
return true;
}
if (trim($comp) == "_OR_")
{
$this->rec_build_string($LHS,$custom_function,$arry,array("OR","LEFTBRACKET"));
$this->rec_build_string($RHS,$custom_function,$arry,array("RIGHTBRACKET"));
return true;

}
}


function parse_command($inputstr,&$field,&$operator,&$value)
{
$FD="NULL";
$OP="NULL";
$VL="NULL";
//$v =explode(" ",$inputstr);
preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $inputstr, $matches);
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
$value=$VL;
//echo "here!: $value\n";
return true;
}


}
?>
