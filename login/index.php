<?

##Zum TEST, in Produktion am besten ausschalten. 
#Ermoeglicht Nutzung ohne passwortschutz, wenn localhost anfragt
#if ($_SERVER["HTTP_HOST"] == "localhost" || $_SERVER["HTTP_HOST"] == "gandalf")
#	$_SERVER["PHP_AUTH_USER"] = "moolder";


if (!isset($_SERVER["PHP_AUTH_USER"]) && isset($_SERVER["REMOTE_USER"])){
        $_SERVER["PHP_AUTH_USER"] = $_SERVER["REMOTE_USER"];
}
        

# Wenn kein Benutzer über simple auth: Fehler
if (!isset($_SERVER["PHP_AUTH_USER"]))
	die("Dieses Verzeichnis muss passwortgeschuetzt sein!");

if (!isset($_COOKIE["apptest"]))
	die("Sie muessen Cookies akzeptieren, um diese Seite zu nutzen!");
	

# ab hier ist klar, dass der benutzer authentisch ist
	
# Wenn kein passender cookie (falscher nutzername oder falsche zufallszahl): cookie neu setzen
$rndfile = "data/rndfile.txt";
$RND = fopen($rndfile, "r") or die("could not open rndfile not reading");
$rndnr = Array();
while (false !== $line = fgets($RND, 16000)){
	$line = chop($line);
	if (preg_match('/^([^\t]+)\t([^\t]+)$/', $line, $OUT))
		$rndnr[$OUT[1]] = $OUT[2];
}
fclose($RND);

#erst prüfen ob cookies gesetzt sind
$appLoginSet = true;
if (!isset($_COOKIE["applogin"]) || !isset($_COOKIE["apppwd"]))
	$appLoginSet = false;

#falls cookies nicht gesetzt oder nicht zur Zufallsnummer passen: neu setzen
if (!$appLoginSet || !isset($rndnr[$_COOKIE["applogin"]]) || $rndnr[$_COOKIE["applogin"]] != $_COOKIE["apppwd"]){
	if (!isset($rndnr[$_SERVER["PHP_AUTH_USER"]])){
		$newrnd ="";
		for($i = 0; $i<16; $i++){
			$newrnd .= rand(1, 256*256-1);
		}
		setcookie("applogin", $_SERVER["PHP_AUTH_USER"],  time() + 3600*24*30, "/") or die("could not set cookie applogin");
		setcookie("apppwd", $newrnd, time() + 3600*24*30, "/") or die("could not set cookie apppwd");

		# datei zu cookies passend schreiben
		$rndnr[$_SERVER["PHP_AUTH_USER"]] = $newrnd;

		$RND = fopen($rndfile, "w") or die("could not open rndfile not writing");
		foreach($rndnr as $username => $rnd){
			fputs($RND, $username."\t".$rnd."\n");
		}
		fclose($RND);
	} else {
		setcookie("applogin", $_SERVER["PHP_AUTH_USER"],  time() + 3600*24*30, "/") or die("could not set cookie applogin");
		setcookie("apppwd", $rndnr[$_SERVER["PHP_AUTH_USER"]], time() + 3600*24*30, "/") or die("could not set cookie apppwd");
	}
}

# redirect zurück auf aufrufende URL
header("Location: ".$_REQUEST["backurl"]);

# nur zum test
#header("Location: test.php");

#das sollte man nie sehen, ausser die backurl war nicht angegeben:
?>Erfolgreich eingeloggt. <a href="<? print $_REQUEST["backurl"]; ?>">Zurueck</a>