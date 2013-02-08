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
<img src="icons/addentry1_stepX.png" border="0" height="80" width="640"
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
	print '<h1>Berechtigungen bearbeiten</h1>';
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
<b>Eintrag ist sichtbar f&uuml;r:</b>
	<br>
<form action="index.php" method="POST">
	<input type="hidden" name="action" value="savepermissions">
	<input type="hidden" name="timestamp" value="<? print $timestamp; ?>">
<?
foreach(getallusers() as $nr => $user){
	$checked = "";
	if (cansee($timestamp, $contentmetadata[$timestamp], $user)) 
		$checked = "checked";
	
	$iseditor = "";
	if (canedit($user))
		$iseditor = " (Vollzugriff)";

?>
	<input type="checkbox" name="users_cansee[]" value="<? print $user; ?>"<? print $checked; ?>> <? print $user.$iseditor; ?><br>

<?
}
?>
	<br>
	<input type="submit" value=" speichern ">
</form>
<hr>
<?
print '<a href="index.php?';
if (isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "addentry")
	print 'view=edittimestamp'.$requestmode.'&timestamp='.$timestamp;
else 
	print 'view=normal';
print '#'.$timestamp.'">Keine &Auml;nderung</a>'."\n";
?>

<br><br><br><br><br><br><br><br><br><br><br>
</body></html>