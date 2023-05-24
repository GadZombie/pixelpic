<?php

require_once 'pixelpic.php';

class PixelDraw
{
	private $img;
	private $width, $height;
	
	function __construct($img)
	{
		$this->img = $img;
		
		$this->width = imagesx($img);
		$this->height = imagesy($img);
	}

	public function Draw($x, $y, $r, $g, $b, $a)
	{
		$color = imagecolorallocatealpha($this->img, $r, $g, $b, $a);
		imagesetpixel($this->img, $x, $y, $color);		
	}
	
	public function DrawPixelpic($x, $y, Pixelpic $pic)
	{
		for ($py = 0; $py < $pic->GetHeight(); $py++)
			for ($px = 0; $px < $pic->GetWidth(); $px++)
			{	
				$color = $pic->GetPixelColor($this->img, $px, $py);
				if ($color != null)
				{
					imagesetpixel($this->img, $x + $px, $y + $py, $color);
				}
			}
	}

	public function GetWidth()
	{
		return $this->width;
	}

	public function GetHeight()
	{
		return $this->height;
	}
	
	public function GetColor($r, $g, $b, $a)
	{
		return imagecolorallocatealpha($this->img, $r, $g, $b, $a);
	}
	
	
}