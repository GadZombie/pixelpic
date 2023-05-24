<?php

class RandomFactory
{
	function __construct()
	{
		$this->make_seed();
	}
	
	function make_seed()
	{
		list($usec, $sec) = explode(' ', microtime());
		return $sec + $usec * 1000000;
	}

	function SetStartSeed($seed = null)
	{
		if (isset($seed))
			mt_srand($seed);
		else
			mt_srand($this->make_seed());
	}
	
	function GetRandom($min, $max)
	{
		return $min + (mt_rand() % ($max - $min + 1));
	}
	
		
}

$RandomFactory = new RandomFactory();
