<?
require_once("include_login.php");
require("function_getimgdata.php");
require("function_viewhelper.php");
require("function_month.php");
?>
<html>
<head>
<? iosheader(); ?>
<title><? print $pagetitle; ?></title>
<? 
styleheader();
?>
</head>
<body>
<?
if (isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "addentry") {
?>
<img src="icons/addentry1_step2.png" border="0" height="80" width="640"
  style="margin-top:10px;margin-bottom:10px"><br>
<?
} else {
?>
<img src="headerimages/header.png" style="margin-left:-20px" height="200" width="640"><br>
<?
}

if(!isset($_REQUEST["timestamp"]))
	die("timestamp nicht gesetzt");
$timestamp=$_REQUEST["timestamp"];
	
if(!isset($contentmetadata[$timestamp]))
	die("timestamp $timestamp unbekannt");
	
#print '<form action="index.php?action=saveimage" method="post">'."\n";
if (!(isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "addentry"))
	print '<h1>Bilder bearbeiten</h1>';
print '<h2>Eintragsdatum: '.$timestamp."</h2>\n";
print ''.$contentmetadata[$timestamp]["Subject"].'<br>';
#print '<input name="timestampold" type="hidden" value="'.$timestamp.'">';
#print $contentmetadata[$timestamp]["From"]."<br>\n";

if(isset($contenttxt[$timestamp])){
	print $contenttxt[$timestamp]."<br>\n";
}

if (isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "addentry")
	$requestmode = "&mode=addentry";

if(isset($contentimg[$timestamp])){
	print '<br>';
	foreach($contentimg[$timestamp] as $nr2 => $imgfile){
		$imgorientation = "";
		if(isset($contentmetadata[$timestamp][$imgfile."_orientation"])) {
			$imgorientation = $contentmetadata[$timestamp][$imgfile."_orientation"];
		} else {
			$imgorientation = getImgOrientation($imgfile);
		}

			
		# 1 = no change needed
		# 6 = rotate clockwise
		# 8 = rotate counterclockwise
		# 3 = upside down

		#default: $imgorientation == 1
		$rotateclockwise = 6;
		$rotatecounterclockwise = 8;
		$rotateupsidedown = 3;

		if ($imgorientation == 3){ # vorher: upside down
			$rotateclockwise = 8; # nachher: counterclockwise
			$rotatecounterclockwise = 6; # nachher: clockwise
			$rotateupsidedown = 1; # nachher: no change needed
		}
		
		if ($imgorientation == 6){ # vorher: clockwise
			$rotateclockwise = 3; # nachher: upside down
			$rotatecounterclockwise = 1; # nachher: no change needed
			$rotateupsidedown = 8; # nachher: counterclockwise
		}

		if ($imgorientation == 8){ # vorher: counterclockwise
			$rotateclockwise = 1; # nachher: no change needed
			$rotatecounterclockwise = 3; # nachher: upside down
			$rotateupsidedown = 6; # nachher: clockwise
		}

		print '<a name="'.$imgfile.'"><div style="float:left;padding-right:10px">';
		print getImageElement($imgfile, $contentmetadata[$timestamp], "", 320);
		print '<br><img width="256" height="60" src="showhistogram.php?file='.$imgfile.'">';
		print '</div> '."\n";
		
		print '<a href="index.php?view=editimage&timestamp='.$timestamp.'&action=deleteimage&image='.$imgfile.$requestmode.'#'.$imgfile.'">';
		print '<img border="0" src="icons/badge-circle-cross-24-ns.png"></a><br><br>';

		print '<a href="index.php?view=editimage&timestamp='.$timestamp.'&action=rotateimage&image='.$imgfile.$requestmode.'&orientation='.$rotateclockwise.'#'.$imgfile.'">';
		print '<img border="0" src="icons/rotateclockwise.png" '.iconwidth().'></a><br>';

		print '<a href="index.php?view=editimage&timestamp='.$timestamp.'&action=rotateimage&image='.$imgfile.$requestmode.'&orientation='.$rotatecounterclockwise.'#'.$imgfile.'">';
		print '<img border="0" src="icons/rotatecounterclockwise.png" '.iconwidth().'></a><br>';

		print '<a href="index.php?view=editimage&timestamp='.$timestamp.'&action=rotateimage&image='.$imgfile.$requestmode.'&orientation='.$rotateupsidedown.'#'.$imgfile.'">';
		print '<img border="0" src="icons/rotateupsidedown.png" '.iconwidth().'></a><br>';

		print '<form action="index.php'.$requestmode.'#'.$imgfile.'">';
		print '<select name="filtermode">';

		$filtermodeNoneSelected = "";
		$filtermodeMaxcontrastSelected = "";
		$filtermodeMiddleSelected = "";
		$filtermode = $contentmetadata[$timestamp][$imgfile."_filtermode"];
		if ($filtermode == "none") $filtermodeNoneSelected = " selected";
		if ($filtermode == "maxcontrast") $filtermodeMaxcontrastSelected = " selected";
		if ($filtermode == "middle") $filtermodeMiddleSelected = " selected";

		print '<option value="none"'.$filtermodeNoneSelected.'>Kein Filter</option>';
		print '<option value="maxcontrast"'.$filtermodeMaxcontrastSelected.'>Max. Kontrast</option>';
		print '<option value="middle"'.$filtermodeMiddleSelected.'>Mitten anheben</option>';
		print '</select>';
		print '<input type="hidden" name="timestamp" value="'.$timestamp.'">';
		print '<input type="hidden" name="view" value="editimage">';
		print '<input type="hidden" name="action" value="setfiltermode">';
		print '<input type="hidden" name="image" value="'.$imgfile.'"><br>';
		print '<input type="submit" value="Filter &auml;ndern">';
		print '</form>';
		
		#print '<a href="index.php?view=editimage&timestamp='.$timestamp.'&action=rotateimage&image='.$imgfile.$requestmode.'&orientation='.$rotateupsidedown.'#'.$imgfile.'">';
		#print '<img border="0" src="icons/rotateupsidedown.png" '.iconwidth().'><br>';
		
		
		print '<div style="clear:left"></div></a><br><br>';
					
	}
}

?>

<!-- The data encoding type, enctype, MUST be specified as below -->
<a name="uploadform" />
<form enctype="multipart/form-data" action="index.php#uploadform" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="1500000" />
    <input type="hidden" name="action" value="addimage" />
    <input type="hidden" name="view" value="editimage" />
<? if (isset($_REQUEST["mode"])) { ?>
    <input type="hidden" name="mode" value="<? print $_REQUEST["mode"]; ?>" />
<? } ?>
    <input type="hidden" name="timestamp" value="<? print $timestamp; ?>" />
    <!-- Name of input element determines name in $_FILES array -->
    Bild hinzuf&uuml;gen: <input name="userfile" type="file" />
    <input type="submit" value="hochladen" />
</form>

<?
print '<a href="index.php?';
if (isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "addentry")
	print 'view=edittimestamp'.$requestmode.'&timestamp='.$timestamp;
else 
	print 'view=normal';
print "&showmonth=".getMonthFromTimestamp($timestamp);
print '#'.$timestamp.'">Fertig</a>'."\n";
?>

<br><br><br><br><br><br><br><br><br><br><br>
</body></html>