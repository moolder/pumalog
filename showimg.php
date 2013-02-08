<?
require_once("include_login.php");
require("function_getimgdata.php");
require("function_histogram.php");

require("config.php");


if(!isset($_GET["file"])) die("no file given");
$file = $_GET["file"];
$longside = $_GET["longside"];
$maxwidth = $_GET["maxwidth"];
$maxheight = $_GET["maxheight"];
$orientation = $_GET["orientation"];
$filtermode = $_GET["filtermode"];
/*if (isset($_GET["zoomSmallImages"])){
	$zoomSmallImages = true;
} else {
	$zoomSmallImages = false;
}*/
$zoomSmallImages = true; 

$cachefile = preg_replace('/[^A-Za-z0-9]/', "_", $file)."_".$longside."_".$maxwidth."_".$maxheight."_".$orientation."_".$filtermode.".jpg";

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

$orientation = $_GET["orientation"];

if (!isset($orientation)){
	$orientation = getImgOrientation($file);
}
	
$img = imagecreatefromjpeg($file) or die("404 $file");



# resize

#if($orientation == 6 or $orientation == 8){
#	list($height, $width) = getimagesize($file);
#} else {
	list($width, $height) = getimagesize($file);
#}
	
$new_width = $width;
$new_height = $height;

if(isset($longside) || isset($maxwidth) && isset($maxheight)){	

	
	if(isset($longside)){	
		if ($width > $height){
			$new_width = $longside;
			$percent = $width / $new_width;
			$new_height = round($height / $percent);
		} else {
			$new_height = $longside;
			$percent = $height / $new_height;
			$new_width = round($width / $percent);
		}
	}
	
	if(isset($maxwidth) && isset($maxheight)){
		if($orientation == 6 or $orientation == 8){
			$tmp = $maxwidth;
			$maxwidth = $maxheight;
			$maxheight = $tmp;
		}

		$percent = 1;
		if ($maxwidth < $width || $zoomSmallImages){
			$percent = $width / $maxwidth;
			$percent1 = $percent;
		}
		if ($maxheight < $height || $zoomSmallImages){
			$percent = max($percent, $height / $maxheight);
			$percent2 = $percent;
		}
		$new_height = round($height / $percent);
		$new_width = round($width / $percent);
	}

	if (false) {
		$FILE = fopen("debugout.txt", "w");
		fputs($FILE, "orientation = $orientation\n");
		fputs($FILE, "maxwidth = $maxwidth\n");
		fputs($FILE, "maxheight = $maxheight\n");
		fputs($FILE, "width = $width\n");
		fputs($FILE, "height = $height\n");
		fputs($FILE, "percent1 = $percent1\n");
		fputs($FILE, "percent2 = $percent2\n");
		fputs($FILE, "new_width = $new_width\n");
		fputs($FILE, "new_height = $new_height\n");
		fclose($FILE);
	}

	$img2 = imagecreatetruecolor($new_width, $new_height);

	imagecopyresampled ($img2, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	imagedestroy($img);
	$img = $img2;
}
	

#rotieren
if ($orientation == 6)
	$img = rotateImage($img, 90);

if ($orientation == 3)
	$img = rotateImage($img, 180);

if ($orientation == 8)
	$img = rotateImage($img, 270);

	
#contrast

if ($filtermode == "maxcontrast"){

	$lightcount = lightcount($img);
	$lightmap = lightmapMaxcontrast($lightcount);
	useLightmap($lightmap, $img);

	# TODO imagefilter() anschauen
	
} else if ($filtermode == "middle"){
	$lightcount = lightcount($img);
	$lightmap = lightmapMiddle($lightcount);
	if (false !== $lightmap){
		useLightmap($lightmap, $img);
	}
}
	
# cache results on disk
imagejpeg($img, $cachepath.$cachefile, 75);


# output image
if ($CACHE = fopen($cachepath.$cachefile, "rb")){
	header("Content-Type: image/jpg");
	while(!feof($CACHE)){
		print(fgets($CACHE, 16384));
	}
	fclose($CACHE);
}


function rotateImage($img, $rotation) {
  $width = imagesx($img);
  $height = imagesy($img);
  switch($rotation) {
    case 90: $newimg= @imagecreatetruecolor($height , $width );break;
    case 180: $newimg= @imagecreatetruecolor($width , $height );break;
    case 270: $newimg= @imagecreatetruecolor($height , $width );break;
    case 0: return $img;break;
    case 360: return $img;break;
  }
  if($newimg) {
    for($i = 0;$i < $width ; $i++) {
      for($j = 0;$j < $height ; $j++) {
        $reference = imagecolorat($img,$i,$j);
        switch($rotation) {
          case 90: if(!@imagesetpixel($newimg, ($height - 1) - $j, $i, $reference )){return false;}break;
          case 180: if(!@imagesetpixel($newimg, $width - $i, ($height - 1) - $j, $reference )){return false;}break;
          case 270: if(!@imagesetpixel($newimg, $j, $width - $i, $reference )){return false;}break;
        }
      }
    } return $newimg;
  }
  return false;
}

#you can call it this way:
#$img = rotateImage($imagetorotate,"degrees");


?>