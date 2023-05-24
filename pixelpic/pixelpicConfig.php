<?php

class PixelPicConfig
{
	public $width = 17;
	public $height = 17;	

	public $outputWidth = 150;
	public $outputHeight = 150;	

	//Enable this if something's wrong and you don't see any picture. This will send exceptions to the output and you can read it in a webbrowser.
	public $printErrorsInOutput = true;

}

$pixelPicConfig = new PixelPicConfig();
