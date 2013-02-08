<?
require_once("include_login.php");
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
<img src="icons/addentry1_step1.png" border="0" height="80" width="640"
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
	
#foreach($items as $nr => $timestamp){
	if (!(isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "addentry"))
		print "<h1>Text bearbeiten</h1>\n";
	print "<h2>Eintragsdatum: ".$timestamp."</h2>\n";
	print '<form action="index.php?action=savetext';
	if (isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "addentry")
		print '&view=editimage&mode=addentry';	
	print '#'.$timestamp.'" method="post">'."\n";
	print '<input name="subject" size="80" value="'.$contentmetadata[$timestamp]["Subject"].'"><br>';
	#print '<input name="timestampnew" size="30" value="'.$timestamp.'"><br>';
	print '<input name="timestamp" type="hidden" value="'.$timestamp.'">';
	#print $contentmetadata[$timestamp]["From"]."<br>\n";

	print '<textarea name="content" rows="10" cols="80">';
	if(isset($contenttxt[$timestamp])){
		print $contenttxt[$timestamp];
	}
	print "</textarea>\n";

	print '<br><input type="submit" value=" Speichern ">'."\n";
	print '<a href=index.php?action=deleteentry&timestamp='.$timestamp.'>Abbrechen</a> </form>';

	if(isset($contentimg[$timestamp])){
		print '<br>';
		foreach($contentimg[$timestamp] as $nr2 => $imgfile){
			$imgorientation = "";
			if(isset($contentmetadata[$timestamp][$imgfile."_orientation"]))
				$imgorientation = "&orientation=".$contentmetadata[$timestamp][$imgfile."_orientation"];
			
			print '<img border="0" src="showimg.php?file='.$imgfile.'&longside=320'.$imgorientation.'"> <!-- [delete] [rotate left] [rotate right] --><br> '."\n";
		}
	}

#	print "<hr>\n";
#}

?></body></html>