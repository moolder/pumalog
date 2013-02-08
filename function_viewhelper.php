<?
require_once("include_login.php");
function htmlquote($string){
	#return htmlentities($string, ENT_COMPAT, "UTF-8");
	return $string; # TODO convert umlaute manually to &ouml; etc.
}

function isios(){
	#return true;
	return preg_match('/AppleWebKit.+Mobile/', $_SERVER["HTTP_USER_AGENT"]);
}

function isipad(){
	return isios() and 
	preg_match('/iPad/', $_SERVER["HTTP_USER_AGENT"]);
}

function issmallscreen(){
	return isios() and !isipad();
}

function iconwidth(){
	if (isipad()) return " width=\"40\" ";
	if (issmallscreen()) return " width=\"80\" ";
	return "";
}

function iosheader($content = "user-scalable=yes, width=1000", $capable="no"){
	if (isipad()) {
		?>
		<meta name="viewport" content="<? print $content; ?>"/>
		<meta name="apple-mobile-web-app-capable" content="<? print $capable; ?>" />

		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<link rel="apple-touch-icon" href="icons/ios-icon.png"/>

		<link rel="apple-touch-startup-image" href="startup.png" />
		<?
	} else if (isios()) {
		?>
		<meta name="viewport" content="<? print $content; ?>"/>
		<link rel="apple-touch-icon" href="icons/ios-icon.png"/>
		<?
	}
}

function styleheader(){
/* <link rel="stylesheet" type="text/css" href="formate.css">
*/ ?>
<style type="text/css">
<!--
<? 
include("formate.css.php"); 
?>
-->
</style>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<?
}
?>