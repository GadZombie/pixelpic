<?php

//require_once 'pixelpicList.php';
require_once 'pixelpicDBConfig.php';
require_once 'pixelpicRandom.php';

class PixelpicDBSQL
{
	private $picList = array();
	private $mysqli;
			
	function __construct()
	{
		$this->mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DBNAME);		
		if ($this->mysqli->connect_errno)
			throw new Exception("Database connect error");
	}
	
	function __destruct()
	{
		$this->mysqli->close();
	}
	
	function Delete($name)
	{
		throw new Exception('Not implemented');
	}

	function Get($elementType, $index)
	{
		$qryRes = $this->mysqli->query
			("SELECT f.id, f.picdata, f.joints, f.groupid, f.category, f.elementtype " .
				" FROM " . tabFigures . " AS f " .
				" JOIN " . tabElementTypes . " AS t ON t.id = f.elementType " .
				" WHERE t.name = \"$elementType\" " .
				" ORDER BY f.id" .
				" LIMIT $index, 1");
		if (!$qryRes)
			throw new Exception($this->mysqli->error);
		
		$res = $this->GetPicFromSQLResult($qryRes);
		$qryRes->free();
		if (!isset($res))
			throw new Exception("PixelpicDBSQL: no pic = $elementType $index");
		return $res;
	}

	function GetById($elementType, $id)
	{
		$qryRes = $this->mysqli->query
			("SELECT f.id, f.picdata, f.joints, f.groupid, f.category, f.elementtype " .
				" FROM " . tabFigures . " AS f " .
				" JOIN " . tabElementTypes . " AS t ON t.id = f.elementType " .
				" WHERE t.name = \"$elementType\" AND f.id = $id " .
				" ORDER BY f.id");
		if (!$qryRes)
			throw new Exception($this->mysqli->error);
		
		$res = $this->GetPicFromSQLResult($qryRes);
		$qryRes->free();
		if (!isset($res))
			throw new Exception("PixelpicDBSQL: no pic = $elementType id = $id");
		return $res;
	}

	function SetColors($colorsArray)
	{
		foreach ($this->picList as &$picList)
		{			
			$pl = (object) $picList;
			$pl->SetColors($colorsArray);
		}		
	}

	function GetCount($elementType)
	{
		$qryRes = $this->mysqli->query
			("SELECT count(*) FROM " . tabFigures . " AS f " .
				"JOIN " . tabElementTypes . " AS t ON t.id = f.elementType " .
				"WHERE t.name = \"$elementType\"");
		if (!$qryRes)
			throw new Exception($this->mysqli->error);
		if ($row = $qryRes->fetch_row())
			$res = $row[0];
		$qryRes->free();
		if (!isset($res))
			throw new Exception('PixelpicDBSQL GetCount: no SQL result');
		return $res;
	}
	
	function GetCountByGroupCategory($elementType, $groupName, $category)
	{
		$qryRes = $this->mysqli->query
			("SELECT count(*) FROM " . tabFigures . " AS f " .
				"JOIN " . tabElementTypes . " AS t ON t.id = f.elementType " .
				"WHERE t.name = \"$elementType\" AND " .
				"f.category = $category AND " .
				"f.groupid = $groupName");
		if (!$qryRes)
			throw new Exception($this->mysqli->error);
		
		if ($row = $qryRes->fetch_row())
			$res = $row[0];
		$qryRes->free();
		if (!isset($res))
			throw new Exception('PixelpicDBSQL GetCountByGroupCategory: no SQL result');
		return $res;
	}	
	
	function GetCountByCategory($elementType, $category)
	{
		$qryRes = $this->mysqli->query
			("SELECT count(*) FROM " . tabFigures . " AS f " .
				"JOIN " . tabElementTypes . " AS t ON t.id = f.elementType " .
				"WHERE t.name = \"$elementType\" AND " .
				"f.category = $category");
		if (!$qryRes)
			throw new Exception($this->mysqli->error);
		
		if ($row = $qryRes->fetch_row())
			$res = $row[0];
		$qryRes->free();
		if (!isset($res))
			throw new Exception('PixelpicDBSQL GetCountByCategory: no SQL result');
		return $res;
	}
	
	private function GetPicFromSQLResult($result)
	{
		$row = $result->fetch_assoc();
		if (isset($row))
		{
			$pic = new Pixelpic();
			$pic->CreateFromString($row['picdata']);
			$pic->SetIdAndGroup($row['id'], $row['groupid'], $row['category']);
			$pic->SetJointsFromJson($row['joints']);
			return $pic;
		}
		else
			return null;	
	}
	
	function GetByGroupCategory($elementType, int $index, $groupName, $category)
	{
		$qryRes = $this->mysqli->query
			("SELECT f.id, f.picdata, f.joints, f.groupid, f.category, f.elementtype " .
				" FROM " . tabFigures . " AS f " .
				" JOIN " . tabElementTypes . " AS t ON t.id = f.elementType " .
				" WHERE t.name = \"$elementType\" AND " .
				" f.category = $category AND " .
				" f.groupid = $groupName" .
				" ORDER BY f.id" .
				" LIMIT $index, 1");
		if (!$qryRes)
			throw new Exception($this->mysqli->error);
		
		return $this->GetPicFromSQLResult($qryRes);
	}	
	
	function GetByCategory($elementType, int $index, $category)
	{
		$qryRes = $this->mysqli->query
			("SELECT f.id, f.picdata, f.joints, f.groupid, f.category, f.elementtype " .
				" FROM " . tabFigures . " AS f " .
				" JOIN " . tabElementTypes . " AS t ON t.id = f.elementType " .
				" WHERE t.name = \"$elementType\" AND " .
				" f.category = $category " .
				" ORDER BY f.id" .
				" LIMIT $index, 1");
		if (!$qryRes)
			throw new Exception($this->mysqli->error);
		
		return $this->GetPicFromSQLResult($qryRes);
	}	
	
	function GetRandByGroupCategory($elementType, $groupName, $category)
	{	
		global $RandomFactory;
		$count = $this->GetCountByGroupCategory($elementType, $groupName, $category);
		if ($count <= 0)
			throw new Exception("PixelpicDBSQL GetRandByGroupCategory: list is empty for groupname $groupName");
	
		$id = $RandomFactory->GetRandom(0, $count - 1);
		$result = $this->GetByGroupCategory($elementType, $id, $groupName, $category);
		
		return $result;
	}

	function GetRandByCategory($elementType, $category)
	{	
		global $RandomFactory;
		$count = $this->GetCountByCategory($elementType, $category);
		if ($count <= 0)
			throw new Exception("PixelpicDBSQL GetRandByCategory: list is empty for category $category");
	
		$id = $RandomFactory->GetRandom(0, $count - 1);
		$result = $this->GetByCategory($elementType, $id, $category);
		
		return $result;
	}


	function GetRand($elementType)
	{
		global $RandomFactory;
		$count = $this->GetCount($elementType);
		if ($count <= 0)
			throw new Exception("PixelpicList GetRand: list is empty");
	
		$id = $RandomFactory->GetRandom(0, $count - 1);
		$result = $this->Get($elementType, $id);
		return $result;
	}

}

$PixelpicDBSQL = new PixelpicDBSQL();
