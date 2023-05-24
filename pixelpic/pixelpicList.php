<?php

require_once 'pixelpicRandom.php';

class PixelpicList
{
	private $picList = array();
			
	function __construct()
	{
		
	}

	function Add(Pixelpic $pic)
	{
		$this->picList[] = $pic;
	}

	function Get(int $index)
	{
		if (!isset($this->picList[$index]))
			throw new Exception("PixelpicList: no index $index");
		return $this->picList[$index];
	}

	function Delete(int $index)
	{
		if (!isset($this->picList[$index]))
			throw new Exception("PixelpicList: no index $index");
		 unset($this->picList[$index]);
	}

	function SetColors($colorsArray)
	{
		foreach ($this->picList as &$pic)
		{	
			$p = (object) $pic;
			$p->SetColors($colorsArray);
		}		
	}

	function GetCount()
	{
		return count($this->picList);;
	}

	function GetCountByGroupCategory($groupName)
	{
		$result = 0;
		foreach ($this->picList as &$pic)
		{
			$p = (object) $pic;
			if ($p->GetGroup() == $groupName)
				++$result;
		}		
		return $result;
	}

	function GetByGroupCategory(int $index, $groupName)
	{
		$n = 0;
		foreach ($this->picList as &$pic)
		{
			$p = (object) $pic;
			if ($p->GetGroup() == $groupName)
			{
				if ($n == $index)
					return $p;
				++$n;
			}
		}		
		return null;
	}
	
	function GetRandByGroupCategory($groupName)
	{
		$count = $this->GetCountByGroupCategory($groupName);
		if ($count <= 0)
			throw new Exception("PixelpicList GetRandByGroupCategory: list is empty for groupname $groupName");
	
		$id = $RandomFactory->GetRandom(0, $count - 1);
		$result = $this->GetByGroupCategory($id, $groupName);
		
		return $result;
	}

	function GetRand()
	{
		$count = $this->GetCount();
		if ($count <= 0)
			throw new Exception("PixelpicList GetRand: list is empty");
	
		$id = $RandomFactory->GetRandom(0, $count - 1);
		$result = $this->Get($id);
		return $result;
	}

	
}
