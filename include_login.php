<?
$rndfile = "login/data/rndfile.txt";
$RND = fopen($rndfile, "r") or die("could not open rndfile not reading");
$rndnr = Array();
while (false !== $line = fgets($RND, 16000)){
	$line = chop($line);
	if (preg_match('/^([^\t]+)\t([^\t]+)$/', $line, $OUT))
		$rndnr[$OUT[1]] = $OUT[2];
}
fclose($RND);

$appLoginSet = true;
if (!isset($_COOKIE["applogin"]) || !isset($_COOKIE["apppwd"]))
	$appLoginSet = false;

#falls cookies nicht gesetzt oder nicht zur Zufallsnummer passen: redirect
if (!$appLoginSet || !isset($rndnr[$_COOKIE["applogin"]]) || $rndnr[$_COOKIE["applogin"]] != $_COOKIE["apppwd"]){

	setcookie("apptest", "Hello World");

	header('Location: login/?backurl='.$_SERVER["REQUEST_URI"]);

	die("es wurde kein gueltiger Login angegeben");
	
}	

?>