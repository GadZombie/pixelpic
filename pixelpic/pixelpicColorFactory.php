<?php

class ColorFactory
{
	private $palette = array();
	private $paletteScale = 5;
	
	function __construct()
	{
		$this->GeneratePalette();
	}
	
	function GetRamp($r1, $g1, $b1, $r2, $g2, $b2, $count)
	{
		$res = array();
		for ($a = 0; $a < $count; $a++)
		{
			$rampr = ($r2 - $r1) / ($count - 1);
			$rampg = ($g2 - $g1) / ($count - 1);
			$rampb = ($b2 - $b1) / ($count - 1);
			
			$res[$a]['r'] = (int) floor($r1 + $a * $rampr);
			$res[$a]['g'] = (int) floor($g1 + $a * $rampg);
			$res[$a]['b'] = (int) floor($b1 + $a * $rampb);
		}
		
		return $res;
	}
	
	function GeneratePalette()
	{
		unset($this->palette);
		
		$this->palette[0] = $this->GetRamp(1, 1, 1, 250, 250, 250, $this->paletteScale);
		$this->palette[1] = $this->GetRamp(50, 50, 0, 250, 250, 0, $this->paletteScale);
		$this->palette[2] = $this->GetRamp(73, 34, 18, 222, 126, 100, $this->paletteScale);
		$this->palette[3] = $this->GetRamp(0, 30, 0, 0, 250, 0, $this->paletteScale);
		$this->palette[4] = $this->GetRamp(0, 10, 50, 0, 70, 250, $this->paletteScale);
		$this->palette[5] = $this->GetRamp(40, 10, 50, 200, 30, 250, $this->paletteScale);
		$this->palette[6] = $this->GetRamp(144, 112, 88, 246, 218, 203, $this->paletteScale);
	}	
	
	function GetPaletteColorCount()
	{
		return count($this->palette);
	}
	
	function GetPaletteScale()
	{
		return $this->paletteScale;
	}
	
	//$brightness = 0..paletteScale - 1
	function GetRandomColor($brightness)
	{
		$r = rand(0, 255);
		$g = rand(0, 255);
		$b = rand(0, 255);
	}
	
	function GetColor($color, $brightness)
	{
		if ($color < 0 || $color >= $this->GetPaletteColorCount() ||
			$brightness < 0 || $brightness >= $this->GetPaletteScale())
			throw new Exception ("Invalid color: $color, $brightness");
		
		$res = array();
		$res[] = $this->palette[$color][$brightness]['r'];
		$res[] = $this->palette[$color][$brightness]['g'];
		$res[] = $this->palette[$color][$brightness]['b'];
		$res[] = 0;
		return $res;		
	}
	
	function GetRandomColors($brightnessArray)
	{
		global $RandomFactory;
		$result = array();
		foreach ($brightnessArray as $br)
		{
			$color = $RandomFactory->GetRandom(0, $this->GetPaletteColorCount() - 1);
			$brightness = $br;
			
			$result[] = $this->GetColor($color, $brightness);
		}
		return $result;
	}
	
}

$ColorFactory = new ColorFactory();
