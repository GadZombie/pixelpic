<?php

class FigureElement
{
	public $pic;
	public $x, $y;
	
	function __construct($pic)
	{
		$this->pic = $pic;
		$this->x = 0;
		$this->y = 0;
	}
}

class PixelpicFigure
{
	private $Element = array();
			
	function __construct()
	{
		
	}

	function SetElement($name, Pixelpic $pic)
	{
		$this->Element[$name] = new FigureElement($pic);				
	}

	function GetElement($name)
	{
		if (!isset($this->Element[$name]))
			throw new Exception("PixelpicFigure: GetElement: No element = $name");		
		return $this->Element[$name];
	}

	private function SetPositionBody($body, $x, $y)
	{
		if ($body == null)
			throw new Exception('No body element in figure');

		$joint = $body->pic->GetJoint(jCenter);	
		if ($joint == null)
			throw new Exception('No center joint in body element');
		
		$body->x = $x - $joint->x;
		$body->y = $y - $joint->y;	
	}
	
	private function SetPositionElement($BaseElement, $BaseJointName, $SlaveElement, $SlaveJointName)
	{
		$jointBase = $BaseElement->pic->GetJoint($BaseJointName);	
		if ($jointBase != null)
		{
			if ($SlaveElement == null)
				throw new Exception('No $BaseJointName element in figure');

			$jointSlave = $SlaveElement->pic->GetJoint($SlaveJointName);	
			if ($jointSlave == null)
				throw new Exception('No $SlaveJointName joint in $BaseJointName pixelpic');

			$SlaveElement->x = $BaseElement->x + $jointBase->x - $jointSlave->x;
			$SlaveElement->y = $BaseElement->y + $jointBase->y - $jointSlave->y;			
		}	
	}

	
	function ElementToJoint($element)
	{
		switch ($element)
		{
			case lsBody:
				return jCenter;
			case lsHead:
				return jHead;
			case lsLArm:
				return jLArm;
			case lsRArm:
				return jRArm;
			case lsLLeg:
				return jLLeg;
			case lsRLeg:
				return jRLeg;
			default:
				throw new Exception("Invalid element name $element");
		}
	}
	
	function HasElement($element)
	{
		if (isset($this->Element[$element]))
		{
			if ($this->Element[lsBody]->pic->HasJoint($this->ElementToJoint($element)))
				return true;
			else
				return false;
		}
		else
			return false;	
	}

	function SetPositions($x, $y)
	{
		$this->SetPositionBody($this->Element[lsBody], $x, $y);
		
		if ($this->HasElement(lsHead))
			$this->SetPositionElement($this->Element[lsBody], jHead, $this->Element[lsHead], jAny);
		if ($this->HasElement(lsLArm))
			$this->SetPositionElement($this->Element[lsBody], jLArm, $this->Element[lsLArm], jAny);
		if ($this->HasElement(lsRArm))
			$this->SetPositionElement($this->Element[lsBody], jRArm, $this->Element[lsRArm], jAny);
		if ($this->HasElement(lsLLeg))
			$this->SetPositionElement($this->Element[lsBody], jLLeg, $this->Element[lsLLeg], jAny);
		if ($this->HasElement(lsRLeg))
			$this->SetPositionElement($this->Element[lsBody], jRLeg, $this->Element[lsRLeg], jAny);
	}
	
	function GetBounds(&$minX, &$maxX, &$minY, &$maxY)
	{

		
		foreach ($this->Element as &$element)
		{
			$el = (object) $element;
			
			if (!isset($minX) || $el->x < $minX)
				$minX = $el->x;
			
			$test = $el->x + $el->pic->GetWidth() - 1;
			if (!isset($maxX) || $test > $maxX)
				$maxX = $test;
			
			if (!isset($minY) || $el->y < $minY)
				$minY = $el->y;
			
			$test = $el->y + $el->pic->GetHeight() - 1;
			if (!isset($maxY) || $test > $maxY)
				$maxY = $test;			
		}
	}
		
	function GetFinalSize(&$finalWidth, &$finalHeight)
	{
		$this->SetPositions(0, 0);
		
		unset($minX);
		unset($minY);
		unset($maxX);
		unset($maxY);
		$this->GetBounds($minX, $maxX, $minY, $maxY);
		$finalWidth = ($maxX - $minX + 1);
		$finalHeight = ($maxY - $minY + 1);
		
		if ($finalWidth > $finalHeight)
			$finalHeight = $finalWidth;
		else
			$finalWidth = $finalHeight;
		
		$finalHeight += 2;
		$finalWidth += 2;
	}
	
	function SetPositionsToCenter($width, $height)
	{
		$this->SetPositions(0, 0);
		
		unset($minX);
		unset($minY);
		unset($maxX);
		unset($maxY);
		$this->GetBounds($minX, $maxX, $minY, $maxY);

		$centerX = (int)ceil($width / 2);
		$centerY = (int)ceil($height / 2);
		
		$figureCenterX = (int) ceil( ($maxX - $minX + 1) / 2 + $minX );
		$figureCenterY = (int) ceil( ($maxY - $minY + 1) / 2 + $minY );
		
		$deltaX = $centerX - $figureCenterX;
		$deltaY = $centerY - $figureCenterY;
		
		foreach ($this->Element as &$element)
		{
			$el = (object) $element;
			$el->x += $deltaX;
			$el->y += $deltaY;
		}
		
	}	

	public function Draw(PixelDraw $pixelDraw)
	{
		foreach ($this->Element as $key => &$element)
		{
			$el = (object) $element;
			if ($this->HasElement($key))
				$pixelDraw->DrawPixelpic($el->x, $el->y, $el->pic);
		}
	}
	
	public function SetColors($colorsArray)
	{
		foreach ($this->Element as $key => &$element)
		{			
			$el = (object) $element;

			if ($this->HasElement($key))
				$el->pic->SetColors($colorsArray);
		}		
	}
}
