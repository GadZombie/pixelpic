<?php

class ImageFactory
{
	function CreateTransparentImage($width, $height)
	{
		$img = imagecreatetruecolor($width, $height);
		imagesavealpha($img, true);
		$transparentColor = imagecolorallocatealpha($img, 0, 0, 0, 127);
		imagefill($img, 0, 0, $transparentColor);
		return $img;
	}
	
	function CreateOutputImage($imgInput, $outputWidth, $outputHeight)
	{
		$imgOutput = $this->CreateTransparentImage($outputWidth, $outputHeight);
		imagecopyresampled(
				$imgOutput, $imgInput,
				0, 0, // $dst_x , int $dst_y , 
				0, 0, //$src_x , int $src_y , 
				$outputWidth, $outputHeight, // int $dst_w , int $dst_h , 
				imagesx($imgInput), imagesy($imgInput)
				);
		imagepng($imgOutput);		
		
		return $imgOutput;
	}
}

$ImageFactory = new ImageFactory();
