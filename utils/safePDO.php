<?
class safePDO extends PDO
{
public function __construct($connectstr)
{
//$connectstr="mysql:host=192.168.0.15;dbname=braddb;charset=utf8;uid=adminbrad;pw=blc4fr";
$ar=explode(";",$connectstr);
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
