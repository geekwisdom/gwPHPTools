<?php
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using
use \org\geekwisdom\GWDataRow;
use \org\geekwisdom\GWDataTable;
class student extends GWDataRow
{
public function __construct($name=null,$address=null)
    {
        if (is_array($name))
	{
	parent::__construct($name);
	}
	else
	{   
        if ($name !=null) $this->set("Name",$name);
 	if ($address !=null) $this->set("Address",$address);
	}
    }

public function getName()
{
return $this->get("Name");
}
}

echo "Testing Load XML...\n";
//$mydata=new GWDataTable("","root","student");
$mydata=new GWDataTable("","root","student");
$file=file_get_contents(__DIR__ . "/../tests/student.xml");
$mydata->loadXml($file);
//echo "Loaded Data is: \n";
//echo $mydata->toJSON();
//die();
echo "Testing Find...\n";
$ret=$mydata->find_row("[ Name _EQ_ 'Mike Gold' ]");
//echo "Found: " . $mydata->getRow($ret[0])->getName() . "\n";
echo $mydata->toJSON();

die();
//$ret=$mydata->find("Name='Mike Gold'");
//echo "Student Found is: " . $ret->getRow(0)->getName() . "\n";
die();
echo "Find Result is: \n";
echo $ret->toJSON();
echo "Attempt to Add Record: Brad Detchevery\n";
$newrow = new student("Brad 2","Another Addr");
//$newrow->set("Name","Brad Detchevery");
//$newrow->set("Address","Some Address");
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
