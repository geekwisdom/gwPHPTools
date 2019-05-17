<? include (dirname(__FILE__) . "/../org/geekwisdom/settings/gwSettings.php");
$connectstr="mysql:host=192.168.0.15;dbname=braddb;charset=utf8;uid=adminbrad;pw=blc4fr";
$mysm = new gwSettings();
$r = $mysm->GetSetting($connectstr,"LogVerbosity","0");
echo "R is $r";
?>
