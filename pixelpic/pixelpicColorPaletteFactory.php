<?php

define('MaxAlpha', 127);

class Color
{
	private $components = array(); //r, g, b, a
	
	function AsArray()
	{
		$res = array();
		$res[] = $this->components['r'];
		$res[] = $this->components['g'];
		$res[] = $this->components['b'];
		$res[] = $this->components['a'];
		return $res;		
	}

	function FromHexColor($hex) //#rrggbb
	{
		unset($this->components);
		$hex = trim($hex);
		if (strlen($hex) != 7)
			throw new Exception ("Invalid hex color value = $hex");
		
		$hex = substr($hex, 1, 6);
		
		$r = substr($hex, 0, 2);
		$this->components['r'] = hexdec($r);
		
		$g = substr($hex, 2, 2);
		$this->components['g'] = hexdec($g);
		
		$b = substr($hex, 4, 2);
		$this->components['b'] = hexdec($b);
		
		$this->components['a'] = 0;// MaxAlpha;
	}
}

class ColorSet
{
	private $set = array();
	
	function Clear()
	{
		unset($this->set);
		$this->set = array();
	}
	
	function Add(Color $color)
	{
		$this->set[] = $color;
	}
	
	function AddFromString($colorStr)
	{
		$color = new Color();
		$color->FromHexColor($colorStr);
		$this->Add($color);
	}
	
	function AddList($listStr) //#rrggbb,#rrggbb,...
	{
		$list = explode(',', $listStr);
		$this->Clear();
		if (count($list) > 0)
		{
			foreach ($list as $l)
			{
				$this->AddFromString($l);
			}
		}
	}	
	
	function GetRandomColor()
	{
		global $RandomFactory;
		
		$idx = $RandomFactory->GetRandom(0, count($this->set) - 1);
		return $this->set[$idx]->AsArray();
	}
}

class ColorPalette
{
	private $palette = array();
	
	function Clear()
	{
		unset($this->set);
		$this->palette = array();
	}
	
	function AddColorSet(ColorSet $colorSet)
	{
		$this->palette[] = $colorSet;
	}
	
	function NewColorSet($colorSetStr)
	{
		$colorSet = new ColorSet();
		$colorSet->AddList($colorSetStr);
		$this->AddColorSet($colorSet);
	}
	
	function AddListOfSets($listSetsStr) //#rrggbb,#rrggbb,#rrggbb\n#rrggbb,#rrggbb\n#rrggbb,#rrggbb,#rrggbb,#rrggbb
	{
		$list = explode('\n', $listSetsStr);
		$this->Clear();
		if (count($list) > 0)
		{
			foreach ($list as $l)
			{
				$this->NewColorSet($l);
			}
		}
	}	
	
	function GetRandomColor($colorSetIndex)
	{
		return $this->palette[$colorSetIndex]->GetRandomColor();
	}
	
}

class ColorPaletteFactory
{
	private $paletteList = array();
	
	function __construct()
	{
		$this->GeneratePalettes();
	}
	
	function AddPalette($paletteStr)
	{
		$palette = new ColorPalette();
		$palette->AddListOfSets($paletteStr);
		$this->paletteList[] = $palette;
	}
	
	function Clear()
	{
		unset($this->set);
	}
	
	function GeneratePalettes()
	{
		$this->Clear();
		
		//every palette contains 5 ColorSets
		//in every ColorSet contains a few colors for an element:
		// * 1 - dark: i.e. shoes, eyes, hair
		// * 2 - mid-dark: any elements, additions
		// * 3 - medium: body, clothes, etc.
		// * 4 - mid-bright: face, hands, legs, skin
		// * 5 - bright: bright details
		
		$this->AddPalette(
				'#000000\n'. //0
				'#072a0a,#3d2219,#2c2101,#0c1034,#030303\n'. //1
				'#164c55,#5b1616,#20315e,#565656,#604d20,#225526\n'. //2
				'#2b69b8,#3ba887,#a94037,#755aa2,#978c38,#247f32\n'. //3
				'#e7d795,#dfcaab,#e7d5cb,#f0d9c5,#edebb0,#dfd6b6\n'. //4
				'#bbd1fa,#eadeee,#f8e2e7,#bbfffe,#c2ffbb,#fffbbb' //5
				);
		
	}	
	
	function GetPaletteCount()
	{
		return count($this->paletteList);
	}
	
	function GetRandomColors($paletteCount)
	{
		global $RandomFactory;
		$result = array();
		
		$paletteIdx = $RandomFactory->GetRandom(0, $this->GetPaletteCount() - 1);
		$palette = $this->paletteList[$paletteIdx];
		
		for ($a = 0; $a < $paletteCount; $a++)
		{
			$result[] = $palette->GetRandomColor($a);
		}
		
		return $result;
	}
	
}

$ColorPaletteFactory = new ColorPaletteFactory();
