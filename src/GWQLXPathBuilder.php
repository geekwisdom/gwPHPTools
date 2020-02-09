<?php
namespace org\geekwisdom;
use org\geekwisdom\GWException;

require_once __DIR__ . '/../../../autoload.php'; // Autoload files using 

class GWQLXPathBuilder implements GWQLCmdBuilderInterface
{
protected $mymap = array();
protected $Params;
protected $commandPart="";
protected $runningString="";

function __construct ()
{
$this->mymap["OR"]="or";
$this->mymap["AND"]="and";
$this->mymap["OPENBRACKET"]="(";
$this->mymap["CLOSEBRACKET"]=")";
$Params = array();
}


function buildString($inputstr, $substs, $allowedFields)
{ 
//echo $cmp . "\n";
$b1 = strpos($inputstr,"[") !== false;
$b2 = strpos($inputstr,"]") !== false;
//echo "input str is $inputstr\n";
if (!($b1 || $b2)) throw new GWException ("ERROR: MISSING OPENING/CLOSING BRACKET",99);
$OP="";
$newstring = str_replace("[","",$inputstr);
$newstring = str_replace("]","",$newstring);
$bracketstring = "[ " . $newstring . " ]";
$mycmd = new GWParsedCommand($bracketstring);
$OP = $mycmd->getOperator();
if ($OP == "_EQ_") $OP="=";
elseif ($OP == "_GT_") $OP=">";
elseif ($OP == "_LT_") $OP="<";
elseif ($OP == "_GE_") $OP=">=";
elseif ($OP == "_LE_") $OP="<=";
elseif ($OP == "_NE_") $OP="!=";
elseif ($OP == "_LIKE_") $OP="contains";
if ($OP == "") throw new GWException("ERROR: MISSING/INCORRECT OPERATOR",98);
$value = trim($mycmd->getValue());
$part ="";
$field = $mycmd->getField();

if (count($allowedFields) > 0)
 {
  if (array_key_exists($field,$allowedFields)) $field=$allowedFields[$field];
  else { throw new GWException("INVALID FIELD: " . $field,95);}
 }


if ($OP == "contains")
{
$part = "contains($field,$value)";
}
else
{
$part = $field.$OP.$value;
}

//$replace = str_replace(trim($newstring),$part,$newstring);
$replace = str_replace(trim($newstring),$part,$inputstr);
$this->runningString = $this->runningString . $replace;
$this->runningString = str_replace($newstring,$part,$this->runningString);
$this->commandPart = $this->commandPart . $part;
if (count($substs) > 0 )
  { 
   for ($i=0;$i<count($substs);$i++) $this->runningString = $this->runningString . " " . $this->mymap[$substs[$i]] . " ";
   }
return;
}

function getParams()
{
return $Params;
}

function getFinalCmd() 
{ 
//echo "R:  is ". $this->runningString;
$this->commandPart = trim($this->runningString);
$this->commandPart = str_replace("[",$this->mymap["OPENBRACKET"],$this->runningString);
$this->commandPart = str_replace("]",$this->mymap["CLOSEBRACKET"],$this->commandPart);
if (substr($this->commandPart,0,1) == "(") $this->commandPart = substr($this->commandPart,1);
if (substr($this->commandPart,strlen($this->commandPart)-1,1) == ")") $this->commandPart = substr($this->commandPart,0,strlen($this->commandPart)-2);
//echo "C: is " . $this->commandPart;
return $this->commandPart;
}
}
?>
