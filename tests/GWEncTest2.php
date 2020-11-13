<?php	
// FOr GEEKWISDOM.ORG THE GAME THESE ARE THE SETTINGS YOU WANT
require_once __DIR__ . '/../../../autoload.php'; // Autoload files using
use \org\geekwisdom\GWDataRow;
use \org\geekwisdom\GWDataTable;
use \org\geekwisdom\GWSecSharedKeyCrypt;
echo "Encyption travel bug...\n";
$myenc = new GWSecSharedKeyCrypt("gamemaster@geekwisdom.org",200,md5("geekwisdom.org"));
echo "Message gen is done!\n";
$mymessage = $myenc->encrypt("The .ova file is also a truecrypt keyflie!","org.geekwisdom.aes-256-cbc");
echo "Result is\n";
//echo $mymessage;
echo base64_encode($mymessage);
//print_r($mymessage);
?>
