<?php

class Joint
{
	public $x, $y;
	public $name;
	function __construct($name, $x, $y)
	{
		$this->name = $name;
		$this->x = $x;
		$this->y = $y;
	}
}

class Pixelpic
{
	private $picData;
	private $colors = array();
	private $joints = array();
	private $id, $group, $category;

	private $width;
	private $height;
	
	function __construct()
	{
		
	}

	function CharToPicData($char)
	{
		return ord($char) - ord('0');
	}
	
	public function CreateFromString($strData)
	{
		$this->picData = array();

		$this->width = 0;
		$this->height = 0;
		
		if (preg_match("/\//i", $strData))
		{
			$strData = preg_replace("/\x0D/", "", $strData);
			$strData = preg_replace("/\x0A/", "", $strData);
		}
		else
		{
			$strData = preg_replace("/\x0A/", "", $strData);	
			$strData = preg_replace("/\x0D/", "/", $strData);
		}
	
		$x = 0;
		$y = 0;
		$this->picData[] = array();
		for ($a = 0; $a < strlen($strData); $a++)
		{
			$data = $strData[$a];
			
			if ($data == '/')
			{
				$y++;
				$x = 0;
				$this->picData[$y] = array();
			}
			else
			{
				$this->picData[$y][$x] = $this->CharToPicData($data);
				$x++;
				if ($x > $this->width)
					$this->width = $x;
			}
		}

		if (count($this->picData[$y]) == 0)
		{
			unset($this->picData[$y]);
			$y--;
		}
		
		$this->height = $y + 1;
	}

	function SetColors($colorsArray)
	{
		$this->colors = $colorsArray;
	}
	
	function GetWidth()
	{
		return $this->width;
	}

	function GetHeight()
	{
		return $this->height;
	}

	function GetPixelData($x, $y)
	{
		if ($x >= 0 && $x < $this->width && $y >= 0 && $y < $this->height)
		{
			return $this->picData[$y][$x];
		}
		else
			return null;
	}

	function GetPixelColor($img, $x, $y)
	{
		$data = $this->GetPixelData($x, $y);
		if ($data != null)
		{
			if ($data >= 0 && $data < count($this->colors))
				return imagecolorallocatealpha($img, $this->colors[$data][0], $this->colors[$data][1], $this->colors[$data][2], $this->colors[$data][3]);
		}
		else
			return null;
	}
	
	function AddJoint(Joint $joint)
	{
		$this->joints[$joint->name] = $joint;
	}

	function AddJointXY($name, int $x, int $y)
	{
		$joint = new Joint($name, $x, $y);
		$this->AddJoint($joint);
	}

	function SetJointsFromJson($joints)
	{		
		unset($this->joints);
		$json = json_decode($joints);
		if (json_last_error() != 0)
		{
			$error = json_last_error_msg();
			throw new Exception ($error);
		}

		foreach ($json as $obj)
		{
			$name = $obj->name;
			$x = $obj->x;
			$y = $obj->y;
			
			if ($name != '' && is_numeric($x) && is_numeric($y)) 
			{
				$joint = new Joint($name, $x, $y);
				$this->AddJoint($joint);				
			}
			else
				throw new Exception ('Invalid JSON data: ' . $obj);
		}
	}

	function GetJoint($name)
	{
		if (isset($this->joints[$name]))
			return $this->joints[$name];
		else
			return null;
	}	
	
	function HasJoint($name)
	{		
		if (isset($this->joints[$name]))
			return true;
		else
			return false;
	}	
	
	function SetIdAndGroup($id, $group, $category)
	{
		$this->id = $id;
		$this->group = $group;
		$this->category = $category;
	}
	
	function GetGroup()
	{
		return $this->group;
	}

	function GetCategory()
	{
		return $this->category;
	}
}
