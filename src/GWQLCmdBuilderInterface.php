<?php
namespace org\geekwisdom;
use org\geekwisdom\GWException;

require_once __DIR__ . '/../../../autoload.php'; // Autoload files using 

interface GWQLCmdBuilderInterface 
{
function buildString($inputstr, $substs, $allowedFields);
function getFinalCmd();
}
?>
