<?php
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using
use \org\geekwisdom\GWDataRow;
use \org\geekwisdom\GWDataTable;

echo "Testing Load XML...\n";
$mydata=new GWDataTable();
$file=file_get_contents(__DIR__ . "/../tests/student.xml");
$mydata->loadXml($file);
echo "Loaded Data is: \n";
echo $mydata->toXML();
echo "Testing Find...\n";
//$ret=$mydata->find("Name='Mike Gold'");
$ret=$mydata->find("[ Name _LIKE_ \"Mike\" _OR_ ID _EQ_ 1 ]");
echo "Find Result is: \n";
echo $ret->toXML();
die();
//**************
echo "Testing Find Row...\n";
$retary=$mydata->find_row("[ Name _EQ_ \"Mike Gold\" _OR_ ID _EQ_ 1 ]");
for ($i=0;$i<count($retary);$i++)
 {
 $row=$mydata->getRow($retary[$i]);
 echo "Name is: " . $row->get("Name") . "\n";
 }
//**************
echo "Attempt to Add Record: Brad Detchevery\n";
$newrow = new GWDataRow();
$newrow->set("Name","Brad Detchevery");
$newrow->set("Address","Some Address");
$mydata->Add($newrow);
echo "Add Result: \n";
echo $mydata->toXML();
echo "Testing Row by Row Print..\n";
$c=$mydata->length();
for ($i=0;$i<$c;$i++)
 { 
echo "i is $i\n";
$test=$mydata->getRow($i);
echo "Name is: " . $test->get("Name") . "\n";
 }
?>
