<?php

interface PixelPicDBIntf {
	function Get($name);
	function Delete($name);
	function SetColors($colorsArray);
}