<?php
session_start();
header('Content-type: image/png');
srand((double)microtime()*1000000);
// Create a new image instance
$im = imagecreatetruecolor(170, 60);
$back_color = imagecolorallocate($im, 255, 255,255);
$text_color = imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255));

// Make the background white
imagefilledrectangle($im, 1, 1, 168, 58, $back_color);
function random_char()
{
	$char = rand(0,2);
	switch($char)
	{
	case 0: return chr(rand(48,57));
	case 1: return chr(rand(65,90));
	case 2: return chr(rand(97,122));
	}
}

$string='';
for($i=0;$i<5;$i++)
{
	$char = random_char();
	imagettftext($im, rand(20,40), rand(-20,20), 10+30*$i, 45, $text_color, "times.ttf", $char);
	$string .= $char;
}

for($i=0;$i<200;$i++) 
{ 
	$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
	imagesetpixel($im, rand(2,167) , rand(2,57) , $randcolor);
} 


imagepng($im);
imagedestroy($im);


$_SESSION['captcha'] = $string;

?>
