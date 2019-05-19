<?php
include("gwDataTable.lib");
$file=file_get_contents("abc.xml");
//$xml =simplexml_load_file("student.xml");
//$result=$xml->xpath("/xmlDS/Student[Name='Mike Gold']"); 
//print_r($result);
//die();

$mydata=new gwDataTable();
//echo $file;
$mydata->loadXml($file);
$ret=$mydata->find("starts-with(MethodName,'GetSetting')");
//$row = new gwDataColumn(array("Name"=>"Brad", "Age"=>"37", "FavColor"=>"Blue"));
//$row2 = new gwDataColumn(array("Name"=>"Robyn", "Age"=>"14", "FavColor"=>"Purple"));
//$row->set("Name","Joe");
//$mydata->add($row);
//$mydata->add($row2);
echo $ret->getRow(0)->get("MethodType");
die();
$xml=$ret->toXml();
echo $xml;
die();
$c=$mydata->length();

//echo $c;
for ($i=0;$i<$c;$i++)
 { 
echo "i is $i\n";
$test=$mydata->getRow($i);
echo "Name is: " . $test->get("Name") . "\n";
 }
?>
