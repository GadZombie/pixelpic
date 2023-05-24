<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<head>
	<title>Pixelpic Avatar example</title>
	<meta name="Author" content="Grzegorz Drozd"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8 ?>" />
	<link rel="stylesheet" href="styles.css" type="text/css"/>
	

</head>

<body>

<?php

function str_rand(int $length = 64){ // 64 = 32
	$length = ($length < 4) ? 4 : $length;
	return bin2hex(random_bytes(($length-($length%2))/2));
}

for ($y = 0; $y <= 4; $y++)
{
	for ($x = 0; $x <= 12; $x++)
	{
		$str = str_rand(20);
		echo "<img src=\"pixelpic_avatar/avatar.php?i=$str\" width=\"150px\" height=\"150px\" class=\"test_avatar\"/>";
	}
	echo "<br/>";
}


for ($x = 0; $x <= 4; $x++)
{
	echo "<img src=\"pixelpic_avatar/avatar.php\" width=\"150px\" height=\"150px\" class=\"test_avatar\"/>";
}
echo "<br/>";



?>

</body>
</html>