<?
require_once("include_login.php");
require("function_getimgdata.php");
require("function_viewhelper.php");

$headimage = "headerimages/header.png";
	
if ($viewmode=="monthly"){
	require("function_month.php");

	$showmonth = $_REQUEST["showmonth"];
	if (!isset($showmonth))
		$showmonth = getCurrMonth();
	if (!checkMonthFormat($showmonth))
		die("Fehlerhafte Monatsangabe");
	$nextmonth = getNextMonth($showmonth);
	$prevmonth = getPrevMonth($showmonth);

	$headimage_m = "headerimages/header_".$showmonth.".png";
	if (is_file($headimage_m)){
		$headimage = $headimage_m;
	}
}
?>
<html>
<head>

<? 
iosheader();
?>

<title><? print $pagetitle; ?></title>
<? 
styleheader();
?>
</head>
<body>

<div class="main">
<a href="index.php"><img src="<? print $headimage; ?>" style="margin-left:-20px" height="200" width="640"
border="0"></a><br>
<div class="logininfo">Angemeldet als <? print $_COOKIE["applogin"]; ?></div>

<?


		 

if (isset($infotext)){
	print "<div class=\"infotext\">$infotext</div>\n";
	print "<img src=\"breakimg.png\" class=\"breakimg\"><br>\n";
}


if ($viewmode == "monthly"){
	monthselect($showmonth, $nextmonth, $prevmonth);
}

if (canedit()){
	print "<div style=\"margin-left:-20px;margin-top:10px; margin-bottom:0px\">";
	print "<a href=\"index.php?action=addentry&view=edit&mode=addentry&timestamp=";
	print date("Y-m-d_H-i-s");
	print "\"><img src=\"icons/addentry1_button.png\" border=\"0\" width=\"640\" height=\"40\">";
	print "</a></div>\n";
}

	
$shownEntryCount = 0;
foreach($items as $nr => $timestamp){

	if (!preg_match('/^'.$showmonth.'/', $timestamp)) continue;
	
	$shownEntryCount++;
	
	#if ($shownEntryCount <> 8) continue;

	print "<a name=\"".$timestamp."\">";
	print "<div class=\"eintrag\">\n";
	print "<h1>".htmlquote($contentmetadata[$timestamp]["Subject"])."</h1>\n";

	if (canedit()){
		print '<div style="float:right"><a href="index.php?view=edit&timestamp='.$timestamp.'">';
		print '<img src="icons/page-pencil-24-ns.png" title="Text bearbeiten" border="0"'.iconwidth().'></a> ';
		print '<a href="index.php?view=editimage&timestamp='.$timestamp.'">';
		print '<img src="icons/editimage.png" title="Bilder bearbeiten" border="0"'.iconwidth().'></a> ';
		print '<a href="index.php?view=edittimestamp&timestamp='.$timestamp.'">';
		print '<img src="icons/clock.png" title="Zeitpunkt &auml;ndern" border="0"'.iconwidth().'></a> ';
		print '<a href="index.php?view=upload&timestamp='.$timestamp.'">';
		print '<img src="icons/upload.png" title="Hochgeladene Dateien anh&auml;ngen" border="0"'.iconwidth().'></a> ';
		print '<a href="index.php?view=editpermissions&timestamp='.$timestamp.'">';
		print '<img src="icons/permissions.png" title="Sichtbarkeit bearbeiten" border="0"'.iconwidth().'></a> ';
		print '<a href="index.php?action=deleteentry&timestamp='.$timestamp.'" onClick="javascript:return(confirm(\'Eintrag wirklich l&ouml;schen?\'))">';
		print '<img src="icons/badge-circle-cross-24-ns_grey.png" title="Eintrag l&ouml;schen" border="0" '.iconwidth().'></a></div>'."\n";
	}

	print "<h2 class=\"eintragsdatum\">".showDatum($timestamp)."</h2>\n";
	#print $contentmetadata[$timestamp]["From"];



	if(isset($contenttxt[$timestamp])){
		print "<p>".htmlquote($contenttxt[$timestamp])."</p>\n";
	}

	if(isset($contentflv[$timestamp]) && !isios()){

		print "<div class=\"flvcontainer\">\n";
		foreach($contentflv[$timestamp] as $nr2 => $flvfile){
/*			
?>
<object type="application/x-shockwave-flash" data="player_flv_maxi.swf" width="640" height="480">
     <param name="movie" value="player_flv_maxi.swf" />
     <param name="FlashVars" value="flv=<? print $flvfile; ?>" />
</object>
<?
*/
?>
<object class="playerpreview" type="application/x-shockwave-flash" data="player_flv_maxi.swf" width="640" height="480">
	<param name="movie" value="/medias/player_flv_maxi.swf">
	<param name="allowFullScreen" value="true">
	<? /*
	<param name="FlashVars" value="flv=showdata.php?file=<? print $flvfile; ?>&amp;width=640&amp;height=480&amp;showstop=1&amp;showvolume=1&amp;showtime=1&amp;startimage=player_startimage.jpg&amp;showfullscreen=1&amp;bgcolor1=189ca8&amp;bgcolor2=085c68&amp;playercolor=085c68">
	*/ ?>
	<param name="FlashVars" value="flv=showdata.php?file=<? print $flvfile; ?>&amp;width=640&amp;height=480&amp;showstop=1&amp;showvolume=1&amp;showtime=1&amp;startimage=player_startimage.jpg&amp;showfullscreen=1&amp;bgcolor1=e0e6c1&amp;bgcolor2=e0e6c1&amp;playercolor=e0e6c1&amp;buttoncolor=000000&amp;buttonovercolor=202020&amp;sliderovercolor=A0A0A0&amp;showiconplay=1&amp;iconplaybgalpha=30&amp;showmouse=autohide">
	<p>Video</p>
</object>

<?

		}
		print "</div>\n";
	}

	if(isset($contentmp4[$timestamp]) && isios()){
?>
<br><div class="imgcontainer">
<? foreach($contentmp4[$timestamp] as $nr2 => $mp4file){ ?>
<? /* does not work - why? ?>
<video src="showdata.php?file=<? print $mp4file; ?>" controls width="620" height="465" poster="player_startimage.jpg">
 video not supported
</video>
<? */ ?> 
<? /* does not work - why? ?>
<a href="showdata.php?file=<? print $mp4file; ?>"><img src="player_startimage.jpg" width="620" height="465"></a>
<? */ ?> 
<a href="<? print $mp4file; ?>"><img src="player_startimage.jpg" width="620" height="465"></a>
<? } ?>
</div>
<?
	}

	if(isset($contentimg[$timestamp])){
		print "<div class=\"imgcontainer\">\n";
		foreach($contentimg[$timestamp] as $nr2 => $imgfile){
			$imgorientation = "";
			if(isset($contentmetadata[$timestamp][$imgfile."_orientation"]))
				$imgorientation = "&orientation=".$contentmetadata[$timestamp][$imgfile."_orientation"];
			
			$imglongside = 480;
			if(count($contentimg[$timestamp]) == 1)
				$imglongside = 700; #960
			
			if ($useshowimg){
				if(count($contentimg[$timestamp]) > 1)
					print '<div class="imgplaceholder">';
				print '<a href="index.php?view=showimage&timestamp='.$timestamp.'&image='.$imgfile.'">';
				print getImageElement($imgfile, $contentmetadata[$timestamp], "", $imglongside);
				#'<img border="0" src="showimg.php?file='.$imgfile.'&longside='.$imglongside.$imgorientation.'">';
				print '</a>';
				if(count($contentimg[$timestamp]) > 1)
					print '</div>';
				print ' '."\n";
			} else {
				print '<a href="'.$imgfile.'"><img border="0" src="showimg.php?file='.$imgfile.'&longside='.$imglongside.$imgorientation.'"></a> '."\n";
			}
		}
		print '<div class="imgplaceholderclear"></div>';
		print "</div>\n";
	}

	print "<br><img src=\"breakimg.png\" class=\"breakimg\">\n";
	print "</div>\n";

}

if ($shownEntryCount == 0)
	print "<div class=\"monthselect\">Keine Eintr&auml;ge für ".getTextualMonth($showmonth)."</div><br><br>";

if ($viewmode == "monthly"){
	monthselect($showmonth, $nextmonth, $prevmonth);
}

	
?>

<? /* ?>
<div class="rightnav">
Neuer Eintrag<br><br>
<b>Monat aus&auml;hlen</b><br>
Juli 2011 (13 Eintr&auml;ge)<br>
Juni 2011 (15 Eintr&auml;ge)<br>
Mai 2011 (17 Eintr&auml;ge)<br>
</div>
<? */ ?>
<br><br>
</body></html>

<?
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


function monthselect($showmonth, $nextmonth, $prevmonth) {
	#print 'Angezeigt werden Eintr&auml;ge aus dem '.getTextualMonth($showmonth)."<br>\n";
	print '<div class="monthselect">';
	print '<a href="index.php?showmonth='.$nextmonth.'">&lt;&lt;&lt; '.getTextualMonth($nextmonth, issmallscreen()).'</a> | ';
	print '<b>'.getTextualMonth($showmonth, issmallscreen())."</b> | \n";
	print '<a href="index.php?showmonth='.$prevmonth.'">'.getTextualMonth($prevmonth, issmallscreen()).' &gt;&gt;&gt;</a><br>';
	print '</div>';
}


?>