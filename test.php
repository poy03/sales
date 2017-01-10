<?php

class Class2
{
	public $y; 
	function __construct()
	{
		$this->y = "TEST2";
		# code...
	}
}
class ClassName
{
	public $x;
	function __construct()
	{
		$this->x = new Class2;
		# code...
	}
}

$ClassName = new ClassName;
var_dump($ClassName->x->y);