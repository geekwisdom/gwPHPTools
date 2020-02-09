<?php
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using 
use \org\geekwisdom\GWParsedCommand;
$myobject = new GWParsedCommand("[ NAME _EQ_ \"Brad Detchevery\" ]");
echo "Field: " . $myobject->getField() . "\n";
echo "Operator: " . $myobject->getOperator() . "\n";
echo "Value: " . $myobject->getValue() . "\n";

?>
