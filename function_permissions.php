<?
require_once("include_login.php");
$userinfo["canedit"]["moolder"] = true;
$userinfo["canedit"]["annaya"] = true;

// wenn Benutzer das Recht "cansee" hat, darf er alle Eintraege sehen
$userinfo["cansee"]["moolder"] = true;
$userinfo["cansee"]["annaya"] = true;

function getallusers(){
	$users[] = "moolder";
	$users[] = "annaya";
	$users[] = "ursula";
	$users[] = "peter";
	$users[] = "gerd";
	
	return $users;
}
	
function getuser(){
	return strtolower($_COOKIE["applogin"]);
}

function canedit($user = 4711){
	global $userinfo, $testmode;
	if ($user == 4711) $user = getuser();
	
	// wenn kein Benutzer gesetzt: alle duerfen editieren
	if ($user == "" && $testmode) return true;
	
	// sonst: Nur Benutzer mit Recht "canedit" duerfen editieren
	return $userinfo["canedit"][$user];
}

function cansee($timestamp, $metadata, $user = 4711){
	global $userinfo;
	if ($user == 4711) $user = getuser();
	
	// wenn kein Benutzer gesetzt: Alle duerfen den Eintrag sehen
	if ($user == "") return true;

	// wenn Benutzer das Recht "cansee" hat, darf er alle Eintraege sehen
	if  ($userinfo["cansee"][$user]) return true;
	
	// falls keine Metadaten zu berechtigungen: Alle duerfen Eintrag sehen
	$mdcansee = $metadata["cansee"];
	if (!isset($mdcansee)) return true;

	// sonst: Recht den Eintrag zu sehen laut Metadaten
	$mdusers = split_mdcansee($mdcansee);
	foreach ($mdusers as $nr => $mduser)
		if (strtolower($mduser) == $user)
			return true;
	
	return false;
}

function split_mdcansee($mdcansee){
	return preg_split('/ +/', $mdcansee);
}
