<?php

require_once 'pixelpicRandom.php';

class ParamIn
{
	function Get()
	{
		global $RandomFactory;
		
		if (isset($_GET['i']))
			$parSeed = $_GET['i'];
		else
			$parSeed = '';

		if ($parSeed == '')
			$RandomFactory->SetStartSeed();
		else
		{
			$RandomFactory->SetStartSeed($this->GetSeedFrom($parSeed));
		}
	}
	
	function GetSeedFrom($strSeed)
	{
		$strSeed = trim($strSeed);
		
		$seed = 0;
		
		for ($a = 0; $a <= strlen($strSeed) - 1; $a++)
		{
			$seed += ( ord($strSeed[$a]) * ($a + 1) ) % (pow(2, 32) - 1);
		}
		
		return $seed;
	}
	
}

$ParamIn = new ParamIn();