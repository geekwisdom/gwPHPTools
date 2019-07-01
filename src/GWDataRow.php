<?php
namespace org\geekwisdom;

class GWDataRow
{
private $item=array();

function __construct ($ary=null)
{
if ($ary !== null)
{
$this->item=$ary;
}
}

function set ($name,$val)
{
$this->item[$name]=$val;
}

function get ($name)
{
$a=$this->item;
$r=$a[$name];
return $r;
}

function toArray()
{
return $this->item;
}

}

