<?

$maxwidth = 1920;
$maxheight = 1080;

require_once("include_login.php");
require("function_getimgdata.php");
require("function_viewhelper.php");

foreach($items as $nr => $timestamp){
	
	if(isset($contentimg[$timestamp])){
		foreach($contentimg[$timestamp] as $nr2 => $imgfile){
			$imgorientation = "";
			if(isset($contentmetadata[$timestamp][$imgfile."_orientation"]))
				$imgorientation = "&orientation=".$contentmetadata[$timestamp][$imgfile."_orientation"];
			
			print getImageElementURL($imgfile, $contentmetadata[$timestamp], "", $maxwidth, $maxheight)."\n";
		}
	}
}



	
function showDatum($ts){
	if(preg_match('/(\d\d\d\d)-(\d\d)-(\d\d)_(\d\d)-(\d\d)-(\d\d)/', $ts, $OUT)){
		$y = $OUT[1];
		$m = $OUT[2];
		$d = $OUT[3];
		$h = $OUT[4];
		$min = $OUT[5];
		$s = $OUT[6];
		return "$d.$m.$y, $h:$min";
	}
	return $ts;
}

function quotestring($string){
	$FILE = fopen("umlautcodierung_html.txt", "r");
	while(!feof($FILE))
		if(preg_match('/^([^\t]+)\t(.*)\n$/', fgets($FILE, 16000), $OUT))
			$htmlconvert[$OUT[1]] = $OUT[2];
	fclose($FILE);
	foreach($htmlconvert as $vorher => $nachher)
		$string = str_replace($vorher, $nachher, $string);
	return $string;
}


?>