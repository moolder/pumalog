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
?>
<hr>
<b>Hochgeladene Datei zum Eintrag &uuml;bernehmen:</b>
	<br>
<form action="index.php" method="POST">
	<input type="hidden" name="action" value="moveuploads">
	<input type="hidden" name="timestamp" value="<? print $timestamp; ?>">
<?

$UPDIR = opendir($uploadpath);
while (false !== $file = readdir($UPDIR)){
	$files[] = $file;
}
closedir($UPDIR);

sort($files);

foreach ($files as $nr => $file){
	if (!preg_match('/^\./', $file)){ 
		$checked = "";
		if (preg_match('/\.(jpg|flv|mp4)$/i', $file))
			$checked = " checked";
		?>
		<span class="filename"><input type="checkbox" name="move_file[]" value="<? print $uploadpath.$file; ?>"<? print $checked; ?>> <? print $file; ?></span><br>
		<?
	}
}
	
?>
<br><input type="submit" value=" &uuml;bernehmen ">


<?
print '<a href="index.php?view=normal';
print '#'.$timestamp.'">Nichts tun</a>'."\n";
?>
</form>

<br><br><br><br><br><br><br><br><br><br><br>
</body></html>