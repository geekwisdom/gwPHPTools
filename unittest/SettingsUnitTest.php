<? include (dirname(__FILE__) . "/../org/geekwisdom/settings/gwSettings.lib");
$connectstr="mysql:host=192.168.0.15;dbname=braddb;charset=utf8;uid=adminbrad;pw=blc4fr";
$mysm = new GWSettings();
//$r = $mysm->GetSetting($connectstr,"LogVerbosity","0");
$r = $mysm->GetSetting("./test.config","test","0");
echo "R is $r\n";	
$r = $mysm->GetSettingReverse("./test.config","Cool Dude","0");
echo "R is $r\n";

?>
