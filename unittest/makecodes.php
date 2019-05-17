<?
//make codes
include("../org/geekwisdom/web/gwCodes.lib");
$n=new gwCodes("./codes/test.array",".code");
$e=0;
for ($i=1;$i<10;$i++)
{
$a=Array();
$a["name"]="Brad";
$a["consumable"]=1;
$e=$n->make_code(json_encode($a));
$v=$n->consume_code($e);
//echo $i . " " . $e . " " . $v . "\n";
print_r($v);
}
?>
