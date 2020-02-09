<?php
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using
use \org\geekwisdom\GWDataRow;
use \org\geekwisdom\GWDataTable;

echo "Testing Load XML...\n";
$mydata=new GWDataTable();
$file=file_get_contents("/home/adminbrad/gw/gwAddressValidator/data/CA_ADDR.xml");
$mydata->loadXml($file);
$c=$mydata->length();
$ary=Array();
for ($i=0;$i<$c;$i++)
 {
$ar=Array();
$test=$mydata->getRow($i)->toArray();
$POSTAL=$test["POSTALCODE"];
foreach ($test as $key => $val) 
 {
 $ar[$key] = $val;
 }
$ary[$POSTAL]=$ar;
}
$arJSON=json_encode($ary);
echo $arJSON;
//print_r($ary);
?>
