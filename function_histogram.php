<?
require_once("include_login.php");



function lightcount($img){
	require("config.php");
	$width =  imagesx($img);
	$height = imagesy($img);
	$pixelnum = $width * $height;
	for ($posx = 0; $posx < $width; $posx++){
		for ($posy = 0; $posy < $height; $posy++){
			$color = imagecolorat($img, $posx, $posy);
			$r = ($color >> 16) & 0xFF;
			$g = ($color >> 8) & 0xFF;
			$b = $color & 0xFF;
			$gray = floor( ($r+$g+$b) / 3);
			$lightcount[$gray]++;
		}
	}

	if($debuglightcount){
		$TMP = fopen("lightcount.txt", "w");
		for($gray = 0; $gray < 256; $gray++){
			fputs($TMP, $gray."\t".$lightcount[$gray]."\n");
		}
		fclose($TMP);
	}
	return $lightcount;
}

# 10 = black
# 240 = white
# 10 => 0
# 240 => 255
# 240-10 = 230 Schritte für 255 grauwerte
# 11 => floor(255 / 230 * (11-10))
function lightmapMaxcontrast($lightcount){
	require("config.php");
	$blackpct = 0;
	$blackstart = 0;
	
	for ($gray = 0; $gray < 256; $gray++){
		$pixelnum += $lightcount[$gray];
	}
	
	while ($blackpct < $pixelnum / 100 * $blackpercent){
		$blackpct += $lightcount[$blackstart];
		$blackstart++;
	}
	$blackstart--;

	$whitepct = 0;
	$whitestart = 255;
	while ($whitepct < $pixelnum / 100 * $whitepercent){
		$whitepct += $lightcount[$whitestart];
		$whitestart--;
	}
	$whitestart++;

	for($gray = 0; $gray < 256; $gray++){
		if ($gray < $blackstart){
			$lightmap[$gray] = 0;
		} else if ($gray > $whitestart){
			$lightmap[$gray] = 255;
		} else {
			$lightmap[$gray] = floor(255 / ($whitestart-$blackstart) * ($gray-$blackstart));
		}
	}

	if($debuglightmap){
		$TMP = fopen("lightmap.txt", "w");
		for($gray = 0; $gray < 256; $gray++){
			fputs($TMP, $gray."\t".$lightmap[$gray]."\n");
		}
		fclose($TMP);
	}
	return $lightmap;
}

function lightmapMiddle($lightcount){
	require("config.php");
	
	$middle = getMiddle($lightcount);
	
	if ($middle >= $targetmiddle)
		return false;

	for($gray = 0; $gray < 256; $gray++){
		if ($gray < $middle){
			$lightmap[$gray] = floor($gray / $middle * 128);
		} else {
			$lightmap[$gray] = floor(127+($gray-$middle) / (255-$middle) * 128);
		}
	}

	if($debuglightmap){
		$TMP = fopen("lightmap.txt", "w");
		fputs($TMP, "middle: $middle\n");
		for($gray = 0; $gray < 256; $gray+= 1){
			if ($gray == $middle) fputs($TMP, "---\n");

			fputs($TMP, $gray."\t".$lightmap[$gray]."\n");
		}
		fclose($TMP);
	}
	return $lightmap;
}

function useLightmap($lightmap, $img){
	require("config.php");
	$width =  imagesx($img);
	$height = imagesy($img);
	for ($posx = 0; $posx < $width; $posx++){
		for ($posy = 0; $posy < $height; $posy++){
			$color = imagecolorat($img, $posx, $posy);
			$r = ($color >> 16) & 0xFF;
			$g = ($color >> 8) & 0xFF;
			$b = $color & 0xFF;
			$r2 = $lightmap[$r];
			$g2 = $lightmap[$g];
			$b2 = $lightmap[$b];
			$color2 = ($r2 << 16) + ($g2 << 8) + $b2;
			imagesetpixel($img, $posx, $posy, $color2);
		}
	}
}

function getmaxlightcount($lightcount){
	for ($gray = 10; $gray < 246; $gray++){
		$maxlightcount = max($maxlightcount, $lightcount[$gray]);
	}
	$maxlightcount *= 1.2;
	return $maxlightcount;
}

function getmiddle($lightcount){
	for ($gray = 10; $gray < 246; $gray++){
		$lightsum += $lightcount[$gray];
		$graysum += $gray * $lightcount[$gray];
	}
	$middle = floor($graysum / $lightsum);
	return $middle;
}

?>