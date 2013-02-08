<?
require_once("include_login.php");
require("function_getimgdata.php");
require("function_histogram.php");

require("config.php");

if(!isset($_GET["file"])) die("no file given");
$file = $_GET["file"];

$cachefile = preg_replace('/[^A-Za-z0-9]/', "_", $file)."_histogram.jpg";

if ($usefilecache && is_file($cachepath.$cachefile)){
	$CACHE = fopen($cachepath.$cachefile, "rb");
	header("Content-Type: image/jpg");
	while(!feof($CACHE)){
		print(fgets($CACHE, 16384));
	}
	fclose($CACHE);
	touch($cachepath.$cachefile);
	exit;
}

$testwidth = 250;
$testheight = 250;
$histheight = 60;
$backgroundcolor = (220 << 16) + (220 << 8) + 220; #offwhite
$linecolor = 0; #black


$img = imagecreatefromjpeg($file) or die("404 $file");

$img2 = imagecreatetruecolor($testwidth, $testheight);
#imagefill($img2, 1, 1, 255<<16);

$width = imagesx($img);
$height = imagesy($img);
imagecopyresampled ($img2, $img, 0, 0, 0, 0, $testwidth, $testheight, $width, $height);

$lightcount = lightcount($img2);
$pixelnum = $testheight*$testwidth;

$img3 = imagecreatetruecolor(256, $histheight);
imagefill($img3, 1, 1, $backgroundcolor);

#test
#$lightcount[128] = $pixelnum / 2;
#$lightcount[129] = $pixelnum / 3;
#$lightcount[130] = $pixelnum / 4;

$maxlightcount = getmaxlightcount($lightcount);

$middle = getmiddle($lightcount);

for ($gray = 0; $gray < 256; $gray++){
	imageline($img3, $gray, 0, $gray, 6, ($gray << 16) + ($gray << 8) + $gray);
	imageline($img3, $gray, $histheight -1, $gray, $histheight - 1 - $lightcount[$gray] / $maxlightcount * $histheight, $linecolor);
}

#http://de.wikipedia.org/wiki/Gewichtung#Berechnung

imageline($img3, $middle, 0, $middle, 10, 255 << 16);

imagestring($img3, 1, $middle, 14, "middle: $middle", 255<<16);


# cache results on disk
imagejpeg($img3, $cachepath.$cachefile);

#output histogram image
header("Content-Type: image/jpg");



# output image
if ($CACHE = fopen($cachepath.$cachefile, "rb")){
	header("Content-Type: image/jpg");
	while(!feof($CACHE)){
		print(fgets($CACHE, 16384));
	}
	fclose($CACHE);
}


?>