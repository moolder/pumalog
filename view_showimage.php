<?
require_once("include_login.php");
require("function_getimgdata.php");
require("function_viewhelper.php");
?>
<html>
<head>
<? 
if(isipad()) {
	iosheader("user-scalable=no, width=1024"); 
} else {
	iosheader("user-scalable=no, width=640");
}
?>
<title><? print $pagetitle; ?></title>
<? 
styleheader();
?>
</head>
<body class="bodyshowimage" onload="javascript:preloadImages()">
<!-- <img src="headerimages/header.png" style="margin-left:-20px" height="200" width="640"><br>
-->
<?

if(!isset($_REQUEST["timestamp"]))
	die("timestamp nicht gesetzt");
$timestamp=$_REQUEST["timestamp"];
	
if(!isset($contentmetadata[$timestamp]))
	die("timestamp $timestamp unbekannt");

if(!isset($_REQUEST["image"]))
	die("image nicht gesetzt");
$r_imgfile=$_REQUEST["image"];
	
#print '<h1>Bilder bearbeiten</h1>';
#print '<h2>Eintragsdatum: '.$timestamp."</h2>\n";
#print ''.$contentmetadata[$timestamp]["Subject"].'<br>';
#
#if(isset($contenttxt[$timestamp])){
#	print $contenttxt[$timestamp]."<br>\n";
#}

$wascurrent = false;
$nextimg = "";
if(isset($contentimg[$timestamp])){
	foreach($contentimg[$timestamp] as $nr2 => $imgfile){
		if ($wascurrent) {
#			$imgorientation = "";
#			if(isset($contentmetadata[$timestamp][$imgfile."_orientation"])) {
#				$imgorientation = $contentmetadata[$timestamp][$imgfile."_orientation"];
#			} else {
#				$imgorientation = getImgOrientation($imgfile);
#			}
#
#			$nextimg = '\'showimg.php?file='.$imgfile.'&maxwidth=';
#			$nextimg = $nextimg.'\'+(window.innerWidth-32)+\'';
#			$nextimg = $nextimg.'&maxheight=';
#			$nextimg = $nextimg.'\'+window.innerHeight+\'';
#			$nextimg = $nextimg.'&orientation='.$imgorientation.'\'';

			$wascurrent = false;
		}
			
	
		if ($imgfile == $r_imgfile) {
			$wascurrent = true;
			$imgorientation = "";
			if(isset($contentmetadata[$timestamp][$imgfile."_orientation"])) {
				$imgorientation = $contentmetadata[$timestamp][$imgfile."_orientation"];
			} else {
				$imgorientation = getImgOrientation($imgfile);
			}

			$jsimg = '<script type="text/javascript">'."\n";

#			$jsimg = $jsimg.'document.write(\'<img border="0" \');'."\n";
#			$jsimg = $jsimg.'document.write(\'alt="Bild wird geladen..." \')'."\n";
#			$jsimg = $jsimg.'document.write(\'src="showimg.php?file='.$imgfile.'&maxwidth=\')'."\n";
#			$jsimg = $jsimg.'document.write(window.innerWidth-32)'."\n";
#			$jsimg = $jsimg.'document.write(\'&maxheight=\')'."\n";
#			$jsimg = $jsimg.'document.write(window.innerHeight)'."\n";
#			$jsimg = $jsimg.'document.write(\'&orientation='.$imgorientation.'">\')'."\n";

			$maxwidth = '\')'."\n".'document.write(window.innerWidth-32)'."\n".'document.write(\'';
			$maxheight = '\')'."\n".'document.write(window.innerHeight)'."\n".'document.write(\'';

			$jsimg = $jsimg.'document.write(\''.getImageElement($imgfile, $contentmetadata[$timestamp], "", $maxwidth, $maxheight);
			$jsimg = $jsimg.'\')'."\n".'</script>';
			$jsimg = $jsimg.'<noscript>';
			$jsimg = $jsimg.'<a href="'.$imgfile.'"><img border="0" src="showimg.php?file='.$imgfile.'&longside=1000&orientation='.$imgorientation.'"></a> '."\n";
			$jsimg = $jsimg.'</noscript>';

			print $js_getwidthheight.'<a name="'.$imgfile.'"><div class="imgshowimage">'.$jsimg.'</div> '."\n";

			$m = "";
			if ($viewmode=="monthly"){
				require("function_month.php");
				$m = "&showmonth=".getMonthFromTimestamp($timestamp);
			}

			$cssclass = "navishowimage";
			if (isios()) $cssclass = "navishowimageios";
			
			print '<div class="'.$cssclass.'" style="float:right">'."\n";
			print '<a class="navispanshowimage" href="index.php?';
			print 'view=normal';
			print $m;
			print '#'.$timestamp.'">';
			if (!issmallscreen()) print 'Zur&uuml;ck zum ';
			print 'Eintrag</a><br>'."\n";
			
			if (isset($contentimg[$timestamp][$nr2-1])){
				print '<a class="navispanshowimage" href="index.php?view=showimage&timestamp='.$timestamp.'&image='.$contentimg[$timestamp][$nr2-1].'">';
				if (!issmallscreen()) {
					print 'Vorheriges Bild'; 
				} else { 
					print '&lt;&lt;';
				}
				print '</a><br> '."\n";
			} else {
				print '<br>';
			}
			
			if (isset($contentimg[$timestamp][$nr2+1])){
				print '<a class="navispanshowimage" href="index.php?view=showimage&timestamp='.$timestamp.'&image='.$contentimg[$timestamp][$nr2+1].'">';

				if (!issmallscreen()) {
					print 'N&auml;chstes Bild'; 
				} else { 
					print '&gt;&gt;';
				}
				
				print '</a><br> '."\n";

			} else {
				print '<br>';
			}

			print '<a class="navispanshowimage" href="'.$imgfile.'">';
			if (!issmallscreen()) {
				print 'Originalbild anzeigen'; 
			} else { 
				print 'Orig.';
			}
			print '</a><br>'."\n";
			print '</div>';

			print '<div style="clear:left;height:0px"></div>';

			
			#print '<a href="index.php?view=editimage&timestamp='.$timestamp.'&action=deleteimage&image='.$imgfile.$requestmode.'#'.$imgfile.'">';
			#print '<img border="0" src="icons/badge-circle-cross-24-ns.png"></a><br><br>';
			#print '<a href="index.php?view=editimage&timestamp='.$timestamp.'&action=rotateimage&image='.$imgfile.$requestmode.'&orientation='.$rotateclockwise.'#'.$imgfile.'">';
			#print '<img border="0" src="icons/rotateclockwise.png" '.iconwidth().'></a><br>';
			#print '<a href="index.php?view=editimage&timestamp='.$timestamp.'&action=rotateimage&image='.$imgfile.$requestmode.'&orientation='.$rotatecounterclockwise.'#'.$imgfile.'">';
			#print '<img border="0" src="icons/rotatecounterclockwise.png" '.iconwidth().'></a><br>';
			#print '<a href="index.php?view=editimage&timestamp='.$timestamp.'&action=rotateimage&image='.$imgfile.$requestmode.'&orientation='.$rotateupsidedown.'#'.$imgfile.'">';
			#print '<img border="0" src="icons/rotateupsidedown.png" '.iconwidth().'><br>';


		}
	}
}


#outputExifData($imgfile);

?>
Inhalte stammen von: http://www.web-toolbox.net/webtoolbox/bilder/images-vorladen-script01.htm#ixzz1ToVFgguX
www.clickstart.de

<script type="text/javascript" language="JavaScript">
<!--

// dieser Array dient nur zur Auflistung der Bilder
var BildListe = new Array();


BildListe[0] = <? print $nextimg; ?>;
//BildListe[1] = "../images/img02.jpg";
//BildListe[2] = "../images/img03.jpg";
//BildListe[3] = "../images/img04.jpg";


// vorzuladene Bilder werden nun in den neuen Array 'Bilder' geschrieben

var Bilder = new Array()

function preloadImages()
{
  for (i = 0; i < BildListe.length; i++) {
    Bilder[i] = new Image();
    Bilder[i].src = BildListe[i];
    //alert(BildListe[i]);
  }
}


//-->
</script>
</body></html>