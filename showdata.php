<?
require_once("include_login.php");

# TODO use mime.types data from apache
$mimetype["jpg"] = "image/jpg";
$mimetype["flv"] = "video/x-flv";
$mimetype["mp4"] = "video/mp4";

#Test
#$_GET["file"] = "content/content_2011-09-25_22-47-23_100.mp4";

if(!isset($_GET["file"])) die("no file given");
$file = $_GET["file"];

$ending = "";
preg_match('/\.([^\.]+)$/', $file, $OUT);
if (isset($OUT[1]))
	$ending = $OUT[1];

$count = 666;
while ($count > 0 and $max++ < 10)
	$file = preg_replace('/\.\./', 'DOUBLE_DOT_REMOVED', $file, -1 , &$count );

if ($max > 9) die("loop-e-doo");

if (!preg_match('/^content\//', $file))
	die("wrong path, possible attack");
	
$FILEHANDLE = fopen($file, "rb") or die("404: ".$file);
if (isset($mimetype[$ending])){
	header("Content-Type: ".$mimetype[$ending]);
} else {
	die("unknown mime type: ".$file);
}
while(!feof($FILEHANDLE)){
	print(fgets($FILEHANDLE, 16174));
}
fclose($FILEHANDLE);

?>