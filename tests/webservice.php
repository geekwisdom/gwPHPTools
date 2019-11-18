<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require 'vendor/autoload.php';
use org\geekwisdom\GWEZWebService;
use org\geekwisdom\GWDataTable;
use org\geekwisdom\GWDataRow;
$ServiceName="";
$Operation="";
$Params="";
$Format="";
$POSTDATA = file_get_contents('php://input');
$isxml=stripos($POSTDATA,"<?xml");
if ($isxml !== false)
{
//Ths is a service post (SOAP/XML)
$data=$POSTDATA;
$serviceData = new GWDataTable();
$serviceData->loadXml($data);
$ServiceName=$serviceData->getTableName();
$TopRow=$serviceData->getRow(0);
$Operation=$TopRow->get("Name");
$Params=$TopRow->get("Params");
//TODO: Allow for Params to also be in format <Param1></Param1><Param2></Param2>...<ParamN>
$Format=@$TopRow->get("Format");
if ($Format == "") $Format = "XML";
}
else 
{ 
//This is a REST CALL 
$ServiceName = @$_GET["Service"];
$Operation=@$_GET["Operation"];
$Params = @$_GET["Params"];
$Format=@$_GET["Format"];
if ($Format == "") $Format = "JSON";
}

if ($ServiceName == "") $ServiceName = @$_GET["Service"];
if ($ServiceName == "") $ServiceName = @$_POST["Service"];
if ($Operation == "" ) $Operation=@$_POST["Operation"];
if ($Params == "") $Params = @$_POST["Params"];
if ($Format == "") $Format=@$_GET["Format"];

if (($ServiceName == "") && ($Operation == ""))
{
header('HTTP/1.0 403 Forbidden');
die();
}
$user=@$_SERVER['PHP_AUTH_USER'];
$myWebServiceServer = new GWEZWebService($ServiceName,"./data/",$user);
$PArray=explode(" ",$Params);
$result=$myWebServiceServer->Fulfill($Operation,$PArray,$Format);
if ($Format == "JSON")
 {
header("Content-type: text/plain");
 }
else
 {
header("Content-type: text/xml");
 }
echo $result;
	
//$myWebService = new GWEZWebService("abc","/home/adminbrad/php/gw-php-tools/services/","adminbrad123");
//$Params=Array();
//$Params[0]=1;
//$Params[1]=2;
//$result=$myWebServiceServer->Fulfill("Add",$Params,"JSON");
//$rstr=$result->toXML();

?>
