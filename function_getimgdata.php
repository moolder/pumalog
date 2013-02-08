<?
require_once("include_login.php");
function getImgOrientation($file){
	# 1 = no change needed
	# 6 = rotate clockwise
	# 3 = upside down
	# 8 = rotate counterclockwise
	$exif = exif_read_data($file, 0, true) or die("404 $file");
	return $exif["IFD0"]["Orientation"];
}

function getImgTimestamp($file){
	$exif = exif_read_data($file, 0, true) or die("404 $file");
	$timestamp=$exif["IFD0"]["DateTime"];
	$timestamp=preg_replace('/:/','-', $timestamp);
	$timestamp=preg_replace('/ /','_', $timestamp);
	return $timestamp;
}

function outputExifData($file){
	$exif = exif_read_data($file, 0, true) or die("404 $file");
	print '<pre>';
	print_r($exif);
	print '</pre>';
}

function getImageElement($imgfile, $metadata, $extraURL, $longsideOrMaxwidth, $maxheight = -1){
	return '<img border="0" src="'.getImageElementURL($imgfile, $metadata, $extraURL, $longsideOrMaxwidth, $maxheight).'">';

}


function getImageElementURL($imgfile, $metadata, $extraURL, $longsideOrMaxwidth, $maxheight = -1){

	$imgorientation = "";
	if(isset($metadata[$imgfile."_orientation"])) {
		$imgorientation = '&orientation='.$metadata[$imgfile."_orientation"];
	} else {
		$imgorientation = '&orientation='.getImgOrientation($imgfile);
	}

	$dimensions = '&longside='.$longsideOrMaxwidth;
	if ($maxheight > -1)
		$dimensions = '&maxwidth='.$longsideOrMaxwidth.'&maxheight='.$maxheight;

	if (isset($metadata[$imgfile."_filtermode"]))
		if ($metadata[$imgfile."_filtermode"] != "none")
			$filtermode = "&filtermode=".$metadata[$imgfile."_filtermode"];

	return 'showimg.php?file='.$imgfile.$dimensions.$imgorientation.$filtermode.$extraURL;

}


?>