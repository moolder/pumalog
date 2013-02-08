<?
require_once("include_login.php");
require("function_getimgdata.php");
require("function_viewhelper.php");
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
<img src="icons/addentry1_step3.png" border="0" height="80" width="640"
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
	print '<h1>Eintragsdatum anpassen</h1>';
print '<h2>Altes Eintragsdatum: '.$timestamp."</h2>\n";
print ''.$contentmetadata[$timestamp]["Subject"].'<br>';
#print '<input name="timestampold" type="hidden" value="'.$timestamp.'">';
#print $contentmetadata[$timestamp]["From"]."<br>\n";


if(isset($contenttxt[$timestamp])){
	print $contenttxt[$timestamp]."<br>\n";
}

print '<hr>Alternative 1: <a href="index.php?view=normal#'.$timestamp.'">Keine &Auml;nderung</a><br><br>';

print '<hr>Alternative 2: Neues Eintragsdatum aus Bild &uuml;bernehmen:<br><br>';

if(isset($contentimg[$timestamp])){
	print '<br>';
	foreach($contentimg[$timestamp] as $nr2 => $imgfile){
		$imgorientation = "";
		if(isset($contentmetadata[$timestamp][$imgfile."_orientation"])) {
			$imgorientation = $contentmetadata[$timestamp][$imgfile."_orientation"];
		} else {
			$imgorientation = getImgOrientation($imgfile);
		}

		print '<a name="'.$imgfile.'"><div style="float:left;padding-right:10px"><img border="0" src="showimg.php?file='.$imgfile.'&longside=320&orientation='.$imgorientation.'"></div> '."\n";
		
		#print '<img border="0" src="icons/badge-circle-cross-24-ns.png"><br><br>';

		$newtimestamp=getimgtimestamp($imgfile);

		print '<a href="index.php?view=edittimestamp&timestamp='.$timestamp.'&newtimestamp='.$newtimestamp;
		if (isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "addentry")
			print '&mode=addentry';
		print '#editfields">';
		print '<img border="0" src="icons/setclock.png" '.iconwidth().'></a><br><pre>';


		print '</pre><div style="clear:left"></div></a><br>';
					
	}
}

print '<hr><a name="editfields">Eintragsdatum &auml;ndern zu:';
print '<form method="POST" action="index.php?action=changetimestamp&timestamp='.$timestamp;
print '"><pre>';

if(isset($_REQUEST["newtimestamp"])){
	if(preg_match('/^(\d{1,4})-(\d{1,2})-(\d{1,2})_(\d{1,2})-(\d{1,2})-(\d{1,2})$/', $_REQUEST["newtimestamp"], $OUT)){
		$newts_year = $OUT[1];
		$newts_month = $OUT[2];
		$newts_day = $OUT[3];
		$newts_hour = $OUT[4];
		$newts_minute = $OUT[5];
		$newts_second = $OUT[6];
	}
}

print 'Jahr:    <input name="newts_year" size="4" value="'.$newts_year.'">'."\n";
print 'Monat:   <input name="newts_month" size="2" value="'.$newts_month.'">'."\n";
print 'Tag:     <input name="newts_day" size="2" value="'.$newts_day.'">'."\n";
print 'Stunde:  <input name="newts_hour" size="2" value="'.$newts_hour.'">'."\n";
print 'Minute:  <input name="newts_minute" size="2" value="'.$newts_minute.'">'."\n";
print 'Sekunde: <input name="newts_second" size="2" value="'.$newts_second.'"></pre>'."\n";
print '<input type="submit" value="&auml;ndern"> ';

print '</form>'."\n";

?>


<br><br><br><br><br><br><br><br><br><br><br>
</body></html>