<?php
namespace org\geekwisdom;
use org\geekwisdom\GWException;

require_once __DIR__ . '/../../../autoload.php'; // Autoload files using
class GWParsedCommand
{
private $Field;
private $Operator;
private $Value;

function __construct ($thecommand)
{
$this->doParse($thecommand);
}
//construct the $data Array  from xmo

function doParse ($thecommand)
{
$FD="NULL";
$OP="NULL";
$VL="NULL";
preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $thecommand, $matches);
$v=$matches[0];
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
$this->Field=$FD;
$this->Operator=$OP;
$this->Value=$VL;
//echo "here!: $value\n";
return;

}


function getField() { return $this->Field; }
function getOperator() { return $this->Operator; }
function getValue() { return $this->Value; }
}
?>
