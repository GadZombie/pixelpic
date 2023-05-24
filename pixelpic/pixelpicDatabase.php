<?php

require_once 'pixelpicList.php';

class PixelpicDatabase
{
	private $picList = array();
			
	function __construct()
	{
		
	}

	function Add($name, PixelpicList $pic)
	{
		$this->picList[$name] = $pic;
	}

	function Get($name)
	{
		if (!isset($this->picList[$name]))
			throw new Exception("PixelpicDatabase: no picList = $name");
		return $this->picList[$name];
	}

	function Delete($name)
	{
		if (!isset($this->picList[$name]))
			throw new Exception("PixelpicDatabase: no picList = $name");
		 unset($this->picList[$name]);
	}

	function SetColors($colorsArray)
	{
		foreach ($this->picList as &$picList)
		{			
			$pl = (object) $picList;
			$pl->SetColors($colorsArray);
		}		
	}

}

$PixelpicDatabase = new PixelpicDatabase();
