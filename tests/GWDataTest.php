<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using
use \org\geekwisdom\GWDataRow;
use \org\geekwisdom\GWDataTable;

echo "Testing Load XML...\n";
$mydata=new GWDataTable();
$file=file_get_contents("student.xml");
$mydata->loadXml($file);
echo "Loaded Data is: \n";
echo $mydata->toXML();
echo "Testing Find...\n";
$ret=$mydata->find("Name='Mike Gold'");
echo "Find Result is: \n";
echo $ret->toXML();
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
