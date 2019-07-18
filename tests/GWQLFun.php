<?php
//$COMPARESTRING="[ NAME _EQ_ BRAD _AND_ AGE _EQ_ 42 ]";
$COMPARESTRING="[ [ NAME _EQ_ BRADLEY ] _OR_ [ NAME _EQ_ BRADR _OR_ NAME _LIKE_ DAVE ] ] _AND_ [ AGE _EQ_ 42 _OR_ AGE _LT_12 ]";
//preg_match_all("/\[[^\]]*\]/", $COMPARESTRING, $matches);
//preg_match('/\(.*\)/',$COMPARESTRING,$matches,PREG_OFFSET_CAPTURE);
//print_r($matches);
//die();
$a=Array();
$a["NAME"]="BRAD";
$a["AGE"]="42";
$r = parse_compare_outside($COMPARESTRING,$a);
if ($r === true) echo "COMPARE IS TRUE\n";
if ($r === false) echo "COMPARE IS FALSE\n";
echo "\nr is $r\n";


function compare ($cmp,$arry)
{
$FD="NULL";
$OP="NULL";
$VL="NULL";
$r=parse_compare($cmp,$FD,$OP,$VL);
if ($r === true)
 {
if (! array_key_exists($FD,$arry)) return "ARRAY MISSING FIELD $FD\N";
echo "FD is $FD\n";
echo "OP is $OP\n";
echo "VL is $VL\n";

if ($OP == "_EQ_")
  {
   echo $arry[$FD] . " = " . $VL . "\n";
  if ($arry[$FD] == $VL) return true;
  else return false;
  }


elseif ($OP == "_GT_")
  {
   echo $arry[$FD] . " > " . $VL . "\n";
  if ($arry[$FD] > $VL) return true;
  else return false;
  }


elseif ($OP == "_LT_")
  {
   echo $arry[$FD] . " < " . $VL . "\n";
  if ($arry[$FD] < $VL) return true;
  else return false;
  }

elseif ($OP == "_GE_")
  {
   echo $arry[$FD] . " >= " . $VL . "\n";
  if ($arry[$FD] >= $VL) return true;
  else return false;
  }

elseif ($OP == "_LE_")
  {
   echo $arry[$FD] . " <- " . $VL . "\n";
  if ($arry[$FD] <= $VL) return true;
  else return false;
  }

elseif ($OP == "_NE_")
  {
   echo $arry[$FD] . " <> " . $VL . "\n";
  if ($arry[$FD] != $VL) return true;
  else return false;
  }

elseif ($OP == "_LIKE_")
  {
   echo $arry[$FD] . " LIKE " . $VL . "\n";
   $p = strpos($arry[$FD],$VL);
  if ($p === false) return false;
  else return true;
  }



 }
else
{
echo "\nR is $r\n";
}
}

function rec_compare($cmp,$arry)
{
$s = strpos($cmp," _AND_ ");
if ($s === false) 
 { 
$s = strpos($cmp," _OR_ ");
if ($s === false) return compare($cmp,$arry);
$v = explode (" _OR_ ",$cmp);
return rec_compare($v[0],$arry) || rec_compare($v[1],$arry);
 }
$v = explode (" _AND_ ",$cmp);
return rec_compare($v[0],$arry) && rec_compare($v[1],$arry);


}


 

function parse_compare_outside($inputstr,$arry)
{
$ret = false;
$v = preg_split("/[\[\]]+/", $inputstr, -1, PREG_SPLIT_NO_EMPTY);
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

if (trim($comp) == "_AND_") return rec_compare($LHS,$arry) && rec_compare($RHS,$arry);
if (trim($comp) == "_OR_") return rec_compare($LHS,$arry) || rec_compare($RHS,$arry);
}
function parse_compare($inputstr,&$field,&$operator,&$value)
{
$FD="NULL";
$OP="NULL";
$VL="NULL";
$v =explode(" ",$inputstr);
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
if ($item > 3) return "PARSE ERROR! 1 $item";
if ($FD == "NULL") return "PARSE ERROR! 2";
if ($OP == "NULL") return "PARSE ERROR! 3";
if ($VL == "NULL") return "PARSE ERROR! 4";
$field=$FD;
$operator=$OP;
$value=$VL;
return true;
}

?>
