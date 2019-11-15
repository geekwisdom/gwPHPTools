<?php
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using 
use \org\geekwisdom\GWDataIO;
use \org\geekwisdom\GWDataFileIO;
use \org\geekwisdom\GWDataRow;
class StudentService extends GWDataIO
{
public function __construct($configFile = null)
 {
 $configFile = __DIR__ . "/../tests/dataIOTest.config";
  parent::__construct($configFile,"student");
 }
}

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


//$test="\org\geekwisdom\GWDataFileIO";
//$myobject = new GWDataFileIO;
$students= new StudentService();
//$myobject = new gwDataIO("dataIOTest.config");
$students->insert('{"Name":"Brad","Address":"Test","ID":"4"}');
$all=$students->search("ID > 0");
echo "Name is: " . $all->getRow(0)->getName() . "\n";
//echo $all->toXML();
//echo $myobject->toXML();

?>
