<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using
use \org\geekwisdom\GWException;
use \org\geekwisdom\GWQL;

//$COMPARESTRING="[ [ AGE _EQ_ 19 ] _OR_ [ NAME _EQ_ BRADR _OR_ NAME _LIKE_ DAVE ] ] _AND_ [ AGE _EQ_ 9 _OR_ AGE _LT_ 12 ]";
//$COMPARESTRING="[ [ AGE _GE_ 7 ] _AND_ [ AGE _GE_ 12 _OR_ AGE _LT_ 15 ] ]";
//$COMPARESTRING="[ [ AGE _GE_ 7 ] _OR_ [ AGE _LT_ 12 _AND_ AGE _EQ_ 99 ] ]";
$COMPARESTRING="[ [ AGE _GE_ 7 _OR_ AGE _LT_ 12 ] _AND_ [ AGE _EQ_ 99 ] ]";
$COMPARESTRING="[ [ AGE _EQ_ 7 _OR_ AGE _LT_ 12 ] _OR_ [ AGE _EQ_ 99 ] ]";
//$COMPARESTRING='[ NAME _EQ_ "BRAD DETCHEVERY" ]';
//preg_match_all("/\[[^\]]*\]/", $COMPARESTRING, $matches);
//preg_match('/\(.*\)/',$COMPARESTRING,$matches,PREG_OFFSET_CAPTURE);
//print_r($matches);
//die();
$b=Array();
for ($i=0;$i<100;$i++)
 {
$a=Array();
$a["NAME"]="BRAD DETCHEVERY";
$a["AGE"]="$i";
array_push($b,$a);
}
$qltester = new GWQL($COMPARESTRING);
//$r = $qltester->getXPath($b);
$r = $qltester->find($b);
//if ($r == true) echo "COMPARE TRUE";
//if ($r == false) echo "COMPARE FALSE";
//echo "\nR is $r\n";
echo "Found Matches are: \n";
for ($i=0;$i<count($r);$i++)
 {
  print_r($b[$r[$i]]);
 }
?>
