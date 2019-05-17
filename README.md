# gwPHPTools
GeekWisdom PHP Objects

Tool Architecture

gwLogger: Simple Logging Platform

gwLogger->LogInfo ($LogItem, $LogLevel,$ltype,$message,$exception)

------------------

gwSettings: Simple Settings Get/Set

gwSettings->GetSetting($FromLocation,$SettingName,$DefaultValue)
gwSetting->WriteSetting($ToLocation,$SettingName,$SettingValue)

----------------------

gwDataTable: Data Table Object

gwCodes($filename,$suffix);

include("../org/geekwisdom/web/gwCodes.lib");
$n=new gwCodes("./codes/test.array",".code");
$e=0;
//To 'make' a code
$code["name"]="50% off";
$code["value"]=50;
$the_code=$n->make_code(json_encode($code));
//$the_code is a unique code that can be consumed later (eg: aCd2


To use the code

$use_code = $n->consume_code('ACd2')
//returns the array for 50% off

}

Note: If the 'special' option 'consumable' is set to 1 ($code["consumable"]=1) the 
code is deleted immediately after being used to prevent r-use
       
