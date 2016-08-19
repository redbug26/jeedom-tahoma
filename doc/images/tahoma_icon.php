<?php

header('Content-Type: image/png');

if (!function_exists('imagecreatetruecolor')) {
	// Need php5-gd
	readfile("tahoma_icon.png");
	exit;
}

$pos = $_REQUEST["pos"];

$im = imagecreatetruecolor(210, 190);
imagesavealpha($im, true);

$transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
$storeColor = imagecolorallocate($im, 255, 195, 103);
$triangleColor = imagecolorallocate($im, 255, 136, 1);
$backgroundColor = imagecolorallocate($im, 255, 240, 223);
$skyColor = imagecolorallocate($im, 3, 166, 255);
$lineColor = imagecolorallocate($im, 255, 180, 94);

imagealphablending($im, false);
imagefilledrectangle($im, 0, 0, imagesx($im) - 1, imagesy($im) - 1, $transparent);
imagealphablending($im, true);

imagefilledrectangle($im, 15, 5, 195, 175, $skyColor);

imagefilledrectangle($im, 15, 5, 195, 25, $storeColor);

$end = 25 + $pos * 1.5;
$alternate = true;

$posy = $end;
while ($posy > 25) {
	$newposy = $posy - 7;
	if ($newposy < 25) {
		$newposy = 25;
	}

	imagefilledrectangle($im, 15, $newposy, 195, $posy, $alternate ? $storeColor : $backgroundColor);

	$alternate = !$alternate;

	$posy = $newposy;
}

imagefilledrectangle($im, 103, 25, 107, $end, $lineColor);
imagefilledpolygon($im, array(90, $end, 120, $end, 105, $end + 15), 3, $triangleColor);

// $text_color = imagecolorallocate($im, 233, 14, 91);
// imagestring($im, 4 , 93, 7,  sprintf("%d%%", $pos), $text_color);

imagepng($im);
imagedestroy($im);

?>