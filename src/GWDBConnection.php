<?
namespace org\geekWisdom;
use \PDO;
class GWDBConnection extends PDO
{
public function __construct($connectstr)
{
//If $connectstr is a file read the contents of the file instead
if (file_exists($connectstr))
{
$cArray=file($connectstr);
if (count ($cArray) == 1) $ar=explode(";",$cArray);
else $ar=$CArray;
}
else
{
$ar=explode(";",$connectstr);
}
$newstr="";
$un="";
$pw="";
for ($i=0;$i<count($ar);$i++)
 {
 $itv = $ar[$i];
 if (strpos($itv,"uid=") !== false) {  $un=$itv; }
 elseif (strpos($itv,"pw=") !== false ) { $pw=$itv; }
 else { $newstr = $newstr . ";" . $itv;}
 }
if (substr($newstr,0,1) == ";") $newstr=substr($newstr,1);
$u=explode("=",$un);
$un=$u[1];
$p=explode("=",$pw);
$pw=$p[1];
parent::__construct($newstr,$un,$pw);
}
}
?>
