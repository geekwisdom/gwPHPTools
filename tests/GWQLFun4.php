<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using
use \org\geekwisdom\GWException;
use \org\geekwisdom\GWDataTable;
use \org\geekwisdom\GWDataRow;
use \org\geekwisdom\GWQL;
use \org\geekwisdom\GWSafePDO;
//$COMPARESTRING="[ [ AGE _EQ_ 19 ] _OR_ [ NAME _EQ_ BRADR _OR_ NAME _LIKE_ DAVE ] ] _AND_ [ AGE _EQ_ 9 _OR_ AGE _LT_ 12 ]";
//$COMPARESTRING="[ AGE _GE_ 7 _AND_ AGE _LE_ 15 ]";
//$COMPARESTRING='[ [ Name _EQ_ "Mike Gold" _OR_  Name _EQ_ "Mahesh Chand" ] _AND_ [  Address _EQ_ "NewYork" ] ]';
//$COMPARESTRING='[ [ Name _EQ_ "Mike Gold" _OR_  Name _EQ_ "Mahesh Chand" ] _AND_ [  Address _EQ_ "NewYork" ] ]';
//$COMPARESTRING='[ [ Name _EQ_ "Mike Gold" ] _OR_  [ Name _EQ_ "Mahesh Chand"  _AND_  Address _EQ_ "NewYork" ] ]';

$COMPARESTRING_A='[ [ A _EQ_ "2" ] _AND_  [ B _EQ_ "3"  _OR_  C _EQ_ "1" ] ]';
$COMPARESTRING_B='[ [ A _EQ_ "2"  _AND_ B _EQ_ "3" ] _OR_  [ C _EQ_ "1" ] ]';
$SQL_A='[ SettingName _EQ_ "LogVerbosity" _AND_ SettingName _EQ_ "1" ]';
$SQL_B='[ SettingName _EQ_ "LogVerbosity" ]';
$SQL_C='[ SettingValue _GT_ "25" ]';

//$COMPARESTRING='[ Name _EQ_ "Mike Gold" ]';

$connectstr="mysql:host=192.168.0.15;dbname=braddb;charset=utf8;uid=adminbrad;pw=blc4fr";
$PD = new GWSafePDO($connectstr);


$qltester = new GWQL($SQL_C);
$qry = $qltester->getSQLCommand("SELECT * FROM settings",$PD);
$qry->execute();
$rows=$qry->fetch(PDO::FETCH_ASSOC);
echo "Rows is\n";
//$rows=$stmt->fetchAll(PDO::FETCH_NUM);
if ($rows) print_r($rows);
//echo "Query is $qry\n";
die();
//$qltester = new GWQL($COMPARESTRING_B);
//$qry = $qltester->getXPath();
//echo "Query is $qry\n";

//die();
echo "Testing Load XML...\n";
$mydata=new GWDataTable();
$file=file_get_contents(__DIR__ . "/../tests/student.xml");
$mydata->loadXml($file);
echo "Testing Find...\n";
$ret=$mydata->find($qry);
echo "Find Result is: \n";
echo $ret->toXML();
?>
