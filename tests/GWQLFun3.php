<?php
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using
use \org\geekwisdom\GWException;
use \org\geekwisdom\GWDataTable;
use \org\geekwisdom\GWDataRow;
use \org\geekwisdom\GWQL;

//$COMPARESTRING="[ [ AGE _EQ_ 19 ] _OR_ [ NAME _EQ_ BRADR _OR_ NAME _LIKE_ DAVE ] ] _AND_ [ AGE _EQ_ 9 _OR_ AGE _LT_ 12 ]";
//$COMPARESTRING="[ AGE _GE_ 7 _AND_ AGE _LE_ 15 ]";
//$COMPARESTRING='[ [ Name _EQ_ "Mike Gold" _OR_  Name _EQ_ "Mahesh Chand" ] _AND_ [  Address _EQ_ "NewYork" ] ]';
//$COMPARESTRING='[ [ Name _EQ_ "Mike Gold" _OR_  Name _EQ_ "Mahesh Chand" ] _AND_ [  Address _EQ_ "NewYork" ] ]';
//$COMPARESTRING='[ [ Name _EQ_ "Mike Gold" ] _OR_  [ Name _EQ_ "Mahesh Chand"  _AND_  Address _EQ_ "NewYork" ] ]';

$COMPARESTRING_A='[ [ A _EQ_ "2" ] _AND_  [ B _EQ_ "3"  _OR_  C _EQ_ "1" ] ]';
$COMPARESTRING_B='[ [ A _EQ_ "2"  _AND_ B _EQ_ "3" ] _OR_  [ C _EQ_ "1" ] ]';

echo "Testing Load XML...\n";
$mydata=new GWDataTable();
$file=file_get_contents(__DIR__ . "/../tests/student.xml");
$mydata->loadXml($file);
echo "Testing Find...\n";
//$qry = "[ ID _EQ_ 1 ]";
$qry = $COMPARESTRING_A;
//$qry = "[ Name _LIKE_ \"Mike\" ]";
//$qry = "[ Name _LIKE_ \"Mike\" ]";
$ret=$mydata->find($qry);
echo "Find Result is: \n";
echo $ret->toXML();
?>
