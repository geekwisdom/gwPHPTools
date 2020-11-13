<?php	
// FOr GEEKWISDOM.ORG THE GAME THESE ARE THE SETTINGS YOU WANT
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using
use \org\geekwisdom\GWDataRow;
use \org\geekwisdom\GWDataTable;
use \org\geekwisdom\GWSecSharedKeyCrypt;
echo "Encyption travel bug...\n";
$cipher=file_get_contents("dec.txt");
$myenc = new GWSecSharedKeyCrypt("TB8RDFJ",200,md5("geekwisdom.org"));
echo "Message gen is done!\n";
$answer = $myenc->decrypt($cipher);
echo "Result is\n";
echo $answer;
//print_r($mymessage);
?>
